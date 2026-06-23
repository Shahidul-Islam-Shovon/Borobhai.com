<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIVE SEARCH (navbar dropdown) — JSON
    |--------------------------------------------------------------------------
    | টাইপ করার সাথে সাথে AJAX এ কল হয়। দ্রুত হালকা result দেয় (max 6)।
    | খোঁজে: name, department, skills(JSON), আর thesis topic (documents.topic)
    */
    public function live(Request $request)
    {
        $q = trim($request->query('q', ''));

        // খুব ছোট query তে কিছু খুঁজব না (অপ্রয়োজনীয় load কমাতে)
        if (mb_strlen($q) < 2) {
            return response()->json(['success' => true, 'results' => []]);
        }

        $like = '%' . $q . '%';
        $meId = Auth::id();

        // ----- ইউজার খোঁজা (name / department / skills) -----
        // admin বাদ, নিজেকে বাদ
        $users = User::query()
            ->where('id', '!=', $meId)
            ->where('role', '!=', 'admin')
            ->where(function ($sub) use ($like) {
                $sub->where('name', 'like', $like)
                    ->orWhere('department', 'like', $like)
                    ->orWhere('skills', 'like', $like);   // JSON string এও LIKE কাজ করে
            })
            ->select('id', 'name', 'role', 'department', 'session', 'profile_picture')
            ->limit(6)
            ->get();

        // ----- thesis topic খোঁজা (documents.topic) -----
        // ঐ document গুলোর owner user দেখাব
        $topicUsers = User::query()
            ->where('users.id', '!=', $meId)
            ->where('users.role', '!=', 'admin')
            ->whereHas('documents', function ($d) use ($like) {
                $d->where('topic', 'like', $like)
                  ->orWhere('title', 'like', $like);
            })
            ->with(['documents' => function ($d) use ($like) {
                $d->where('topic', 'like', $like)
                  ->orWhere('title', 'like', $like)
                  ->select('id', 'user_id', 'title', 'topic')
                  ->limit(1);
            }])
            ->select('id', 'name', 'role', 'department', 'session', 'profile_picture')
            ->limit(4)
            ->get();

        // ----- দুই সেট merge করি (id দিয়ে unique) -----
        $merged = [];

        foreach ($users as $u) {
            $merged[$u->id] = [
                'id'         => $u->id,
                'name'       => $u->name,
                'role'       => $u->role,
                'role_label' => ucfirst($u->role),
                'sub'        => $this->subLine($u),
                'avatar'     => $this->avatarUrl($u),
                'initial'    => mb_strtoupper(mb_substr($u->name ?? 'U', 0, 1)),
                'topic'      => null,
            ];
        }

        foreach ($topicUsers as $u) {
            $doc = $u->documents->first();
            if (isset($merged[$u->id])) {
                // আগেই আছে — শুধু topic যোগ করি
                $merged[$u->id]['topic'] = $doc->topic ?? $doc->title ?? null;
            } else {
                $merged[$u->id] = [
                    'id'         => $u->id,
                    'name'       => $u->name,
                    'role'       => $u->role,
                    'role_label' => ucfirst($u->role),
                    'sub'        => $this->subLine($u),
                    'avatar'     => $this->avatarUrl($u),
                    'initial'    => mb_strtoupper(mb_substr($u->name ?? 'U', 0, 1)),
                    'topic'      => $doc->topic ?? $doc->title ?? null,
                ];
            }
        }

        // array তে নিয়ে max 8 রাখি
        $results = array_slice(array_values($merged), 0, 8);

        return response()->json([
            'success' => true,
            'query'   => $q,
            'results' => $results,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FULL SEARCH PAGE
    |--------------------------------------------------------------------------
    | /search?q=...&filter=all|student|alumni|teacher|topic
    | পূর্ণ result, pagination সহ
    */
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
                // শুধু thesis topic দিয়ে
                $query->whereHas('documents', function ($d) use ($like) {
                    $d->where('topic', 'like', $like)
                      ->orWhere('title', 'like', $like);
                });
            } elseif (in_array($filter, ['student', 'alumni', 'teacher'])) {
                // নির্দিষ্ট role + name/dept/skills/topic
                $query->where('role', $filter)
                      ->where(function ($sub) use ($like) {
                          $sub->where('name', 'like', $like)
                              ->orWhere('department', 'like', $like)
                              ->orWhere('skills', 'like', $like)
                              ->orWhereHas('documents', function ($d) use ($like) {
                                  $d->where('topic', 'like', $like)
                                    ->orWhere('title', 'like', $like);
                              });
                      });
            } else {
                // all — name/dept/skills/topic সব
                $query->where(function ($sub) use ($like) {
                    $sub->where('name', 'like', $like)
                        ->orWhere('department', 'like', $like)
                        ->orWhere('skills', 'like', $like)
                        ->orWhereHas('documents', function ($d) use ($like) {
                            $d->where('topic', 'like', $like)
                              ->orWhere('title', 'like', $like);
                        });
                });
            }

            $results = $query
                ->with(['documents' => function ($d) use ($like) {
                    $d->where('topic', 'like', $like)
                      ->orWhere('title', 'like', $like)
                      ->select('id', 'user_id', 'title', 'topic')
                      ->limit(1);
                }])
                ->select('id', 'name', 'role', 'department', 'session', 'bio', 'profile_picture', 'skills')
                ->orderBy('name')
                ->paginate(12)
                ->withQueryString();

            $total = $results->total();
        }

        return view('search.results', [
            'query'   => $q,
            'filter'  => $filter,
            'results' => $results,
            'total'   => $total,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    // নামের নিচের sub-line: department · session
    private function subLine(User $u): string
    {
        $parts = [];
        if (!empty($u->department)) $parts[] = $u->department;
        if (!empty($u->session))    $parts[] = $u->session;
        return implode(' · ', $parts);
    }

    // avatar URL বা null (null হলে frontend initial দেখাবে)
    private function avatarUrl(User $u): ?string
    {
        return $u->profile_picture ? asset('storage/' . $u->profile_picture) : null;
    }
}