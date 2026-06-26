<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function live(Request $request)
    {
        $q = trim($request->query('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['success' => true, 'results' => []]);
        }

        $like = '%' . $q . '%';
        $meId = Auth::id();

        $users = User::query()
            ->where('id', '!=', $meId)
            ->where('role', '!=', 'admin')
            ->where(function ($sub) use ($like) {
                $sub->where('name', 'like', $like)
                    ->orWhere('department', 'like', $like)
                    ->orWhere('skills', 'like', $like);
            })
            ->select('id', 'name', 'role', 'department', 'session', 'profile_picture')
            ->limit(8)
            ->get();

        $topicUsers = User::query()
            ->where('users.id', '!=', $meId)
            ->where('users.role', '!=', 'admin')
            ->whereHas('documents', function ($d) use ($like) {
                $d->where('topic', 'like', $like)->orWhere('title', 'like', $like);
            })
            ->with(['documents' => function ($d) use ($like) {
                $d->where('topic', 'like', $like)->orWhere('title', 'like', $like)
                  ->select('id', 'user_id', 'title', 'topic')->limit(1);
            }])
            ->select('id', 'name', 'role', 'department', 'session', 'profile_picture')
            ->limit(4)
            ->get();

        $merged = [];
        foreach ($users as $u) {
            $merged[$u->id] = [
                'id' => $u->id, 'name' => $u->name, 'role' => $u->role,
                'role_label' => ucfirst($u->role), 'sub' => $this->subLine($u),
                'avatar' => $this->avatarUrl($u),
                'initial' => mb_strtoupper(mb_substr($u->name ?? 'U', 0, 1)),
                'topic' => null,
            ];
        }
        foreach ($topicUsers as $u) {
            $doc = $u->documents->first();
            if (isset($merged[$u->id])) {
                $merged[$u->id]['topic'] = $doc->topic ?? $doc->title ?? null;
            } else {
                $merged[$u->id] = [
                    'id' => $u->id, 'name' => $u->name, 'role' => $u->role,
                    'role_label' => ucfirst($u->role), 'sub' => $this->subLine($u),
                    'avatar' => $this->avatarUrl($u),
                    'initial' => mb_strtoupper(mb_substr($u->name ?? 'U', 0, 1)),
                    'topic' => $doc->topic ?? $doc->title ?? null,
                ];
            }
        }

        $results = array_slice(array_values($merged), 0, 8);

        // Recent search save
        if (!empty($results)) {
            DB::table('recent_searches')->updateOrInsert(
                ['user_id' => $meId, 'query' => $q],
                ['updated_at' => now(), 'created_at' => now()]
            );
        }

        return response()->json(['success' => true, 'query' => $q, 'results' => $results]);
    }

    public function index(Request $request)
    {
        $q      = trim($request->query('q', ''));
        $filter = $request->query('filter', 'all');
        $meId   = Auth::id();
        $results = collect();
        $total   = 0;

        if (mb_strlen($q) >= 2) {
            $like = '%' . $q . '%';

            $query = User::query()
                ->where('id', '!=', $meId)
                ->where('role', '!=', 'admin');

            if ($filter === 'topic') {
                $query->whereHas('documents', function ($d) use ($like) {
                    $d->where('topic', 'like', $like)->orWhere('title', 'like', $like);
                });
            } elseif (in_array($filter, ['student', 'alumni', 'teacher'])) {
                $query->where('role', $filter)
                      ->where(function ($sub) use ($like) {
                          $sub->where('name', 'like', $like)
                              ->orWhere('department', 'like', $like)
                              ->orWhere('skills', 'like', $like)
                              ->orWhereHas('documents', function ($d) use ($like) {
                                  $d->where('topic', 'like', $like)->orWhere('title', 'like', $like);
                              });
                      });
            } else {
                $query->where(function ($sub) use ($like) {
                    $sub->where('name', 'like', $like)
                        ->orWhere('department', 'like', $like)
                        ->orWhere('skills', 'like', $like)
                        ->orWhereHas('documents', function ($d) use ($like) {
                            $d->where('topic', 'like', $like)->orWhere('title', 'like', $like);
                        });
                });
            }

            $results = $query
                ->with(['documents' => function ($d) use ($like) {
                    $d->where('topic', 'like', $like)->orWhere('title', 'like', $like)
                      ->select('id', 'user_id', 'title', 'topic')->limit(1);
                }])
                ->select('id', 'name', 'role', 'department', 'session', 'bio', 'profile_picture', 'skills')
                ->orderBy('name')
                ->paginate(12)
                ->withQueryString();

            $total = $results->total();

            // ✅ Friendship status — clean query
            $userIds = $results->pluck('id')->toArray();

            $friendships = Friendship::where(function ($q) use ($meId, $userIds) {
                $q->where('sender_id', $meId)->whereIn('receiver_id', $userIds);
            })->orWhere(function ($q) use ($meId, $userIds) {
                $q->where('receiver_id', $meId)->whereIn('sender_id', $userIds);
            })->get();

            $statusMap = [];
            foreach ($friendships as $f) {
                $otherId = $f->sender_id === $meId ? $f->receiver_id : $f->sender_id;
                if ($f->status === 'accepted') {
                    $statusMap[$otherId] = 'accepted';
                } elseif ($f->status === 'pending') {
                    $statusMap[$otherId] = $f->sender_id === $meId ? 'pending_sent' : 'pending_received';
                } elseif ($f->status === 'blocked') {
                    $statusMap[$otherId] = 'blocked';
                }
            }

            foreach ($results as $user) {
                $user->friendshipStatus = $statusMap[$user->id] ?? 'none';
            }
        }

        return view('search.results', compact('q', 'filter', 'results', 'total') + ['query' => $q]);
    }

    public function recentSearches()
    {
        $searches = DB::table('recent_searches')
            ->where('user_id', Auth::id())
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get(['id', 'query']);

        return response()->json(['success' => true, 'searches' => $searches]);
    }

    public function deleteSearch($id)
    {
        DB::table('recent_searches')
            ->where('id', $id)->where('user_id', Auth::id())->delete();
        return response()->json(['success' => true]);
    }

    public function clearSearches()
    {
        DB::table('recent_searches')->where('user_id', Auth::id())->delete();
        return response()->json(['success' => true]);
    }

    private function subLine(User $u): string
    {
        $parts = [];
        if (!empty($u->department)) $parts[] = $u->department;
        if (!empty($u->session))    $parts[] = $u->session;
        return implode(' · ', $parts);
    }

    private function avatarUrl(User $u): ?string
    {
        return $u->profile_picture ? asset('storage/' . $u->profile_picture) : null;
    }
}