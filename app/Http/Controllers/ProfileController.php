<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Post;

class ProfileController extends Controller
{
    // ==========================================
    // প্রিমিয়াম প্রোফাইল ভিউ (নিজের বা অন্যের)
    // ==========================================
    public function show($id = null)
    {
        $user = $id ? User::findOrFail($id) : Auth::user();
        $isOwner = Auth::id() === $user->id;
        $postCount = Post::where('user_id', $user->id)->count();

        return view('profile.show', compact('user', 'isOwner', 'postCount'));
    }

    // ==========================================
    // ট্যাব কন্টেন্ট (AJAX) — Posts / Photos
    // ==========================================
    public function tabContent(Request $request, $id = null)
    {
        $user = $id ? User::findOrFail($id) : Auth::user();
        $tab  = $request->query('tab', 'posts');

        // ----- POSTS ট্যাব -----
        if ($tab === 'posts') {
            $posts = Post::with([
                        'user',
                        'parentPost.user',
                        'likes',
                        'comments' => function ($q) {
                            $q->with('user')->latest()->limit(10);
                        }
                    ])
                    ->withCount('comments')
                    ->where('user_id', $user->id)   // নিজের পোস্ট + শেয়ার করা (সব এই user এর তৈরি)
                    ->latest()
                    ->get();

            $html = '';
            foreach ($posts as $post) {
                $html .= view('partials.post-card', compact('post'))->render();
            }

            return response()->json([
                'success' => true,
                'html'    => $html,
                'count'   => $posts->count(),
            ]);
        }

        // ----- PHOTOS & VIDEOS ট্যাব -----
        if ($tab === 'media') {
            $media = [];

            // প্রোফাইল পিকচার + কভার ফটো (থাকলে আগে দেখাও)
            if (!empty($user->cover_photo)) {
                $media[] = ['type' => 'image', 'url' => asset('storage/' . str_replace('//', '/', $user->cover_photo))];
            }
            if (!empty($user->profile_picture)) {
                $media[] = ['type' => 'image', 'url' => asset('storage/' . str_replace('//', '/', $user->profile_picture))];
            }

            // সব পোস্টের মিডিয়া
            $posts = Post::with('parentPost')
                    ->where('user_id', $user->id)
                    ->latest()
                    ->get();

            foreach ($posts as $post) {
                $this->collectMedia($post, $media);
                if ($post->parentPost) {
                    $this->collectMedia($post->parentPost, $media);
                }
            }

            return response()->json([
                'success' => true,
                'media'   => $media,
                'count'   => count($media),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid tab'], 400);
    }

    /**
     * একটি পোস্ট থেকে সব ছবি+ভিডিও সংগ্রহ করে $media array তে যোগ করো
     */
    private function collectMedia($post, &$media)
    {
        // ছবি
        if (!empty($post->images)) {
            $imgs = is_array($post->images) ? $post->images : json_decode($post->images, true);
            if (is_array($imgs)) {
                foreach ($imgs as $img) {
                    $clean = str_replace('//', '/', $img);
                    $media[] = [
                        'type' => 'image',
                        'url'  => asset('storage/' . $clean),
                    ];
                }
            }
        }

        // ভিডিও
        if (!empty($post->video) && $post->video !== 'null') {
            $decoded = is_array($post->video) ? $post->video : json_decode($post->video, true);
            if (is_array($decoded)) {
                foreach ($decoded as $vid) {
                    $clean = str_replace('//', '/', trim($vid, '"[] '));
                    if (!empty($clean)) {
                        $media[] = ['type' => 'video', 'url' => url('stream/video/' . $clean)];
                    }
                }
            } else {
                $clean = str_replace('//', '/', trim($post->video, '"[] '));
                if (!empty($clean)) {
                    $media[] = ['type' => 'video', 'url' => url('stream/video/' . $clean)];
                }
            }
        }
    }

    // ==========================================
    // প্রোফাইল তথ্য আপডেট (AJAX, লোড-ফ্রি)
    // ==========================================
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'bio'         => 'nullable|string|max:1000',
            'phone'       => 'nullable|string|max:30',
            'location'    => 'nullable|string|max:255',
            'department'  => 'nullable|string|max:100',
            'session'     => 'nullable|string|max:50',
            'section'     => 'nullable|string|max:50',
            'semester'    => 'nullable|string|max:50',
            'interests'   => 'nullable|string|max:255',
            'skills'      => 'nullable|string|max:500',
            'linkedin_url'=> 'nullable|url|max:255',
            'github_url'  => 'nullable|url|max:255',
            'facebook_url'=> 'nullable|url|max:255',
        ]);

        if ($request->filled('skills')) {
            $skillsArray = array_values(array_filter(array_map('trim', explode(',', $request->skills))));
            $user->skills = $skillsArray;
        } else {
            $user->skills = null;
        }

        $user->name         = $validated['name'];
        $user->bio          = $validated['bio'] ?? null;
        $user->phone        = $validated['phone'] ?? null;
        $user->location     = $validated['location'] ?? null;
        $user->department   = $validated['department'] ?? null;
        $user->session      = $validated['session'] ?? null;
        $user->section      = $validated['section'] ?? null;
        $user->semester     = $validated['semester'] ?? null;
        $user->interests    = $validated['interests'] ?? null;
        $user->linkedin_url = $validated['linkedin_url'] ?? null;
        $user->github_url   = $validated['github_url'] ?? null;
        $user->facebook_url = $validated['facebook_url'] ?? null;

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'user'    => [
                'name'         => $user->name,
                'bio'          => $user->bio,
                'phone'        => $user->phone,
                'location'     => $user->location,
                'department'   => $user->department,
                'session'      => $user->session,
                'section'      => $user->section,
                'semester'     => $user->semester,
                'interests'    => $user->interests,
                'skills'       => $user->skills,
                'linkedin_url' => $user->linkedin_url,
                'github_url'   => $user->github_url,
                'facebook_url' => $user->facebook_url,
            ],
        ]);
    }

    // ==========================================
    // প্রোফাইল ছবি আপডেট (AJAX)
    // ==========================================
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'type'  => 'required|in:profile,cover',
        ]);

        $user = Auth::user();
        $type = $request->type;

        $field  = $type === 'cover' ? 'cover_photo' : 'profile_picture';
        $folder = $type === 'cover' ? 'covers' : 'profiles';

        if ($user->$field && Storage::disk('public')->exists($user->$field)) {
            Storage::disk('public')->delete($user->$field);
        }

        $path = $request->file('photo')->store($folder, 'public');
        $user->$field = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' photo updated!',
            'url'     => asset('storage/' . $path),
        ]);
    }

    // ==========================================
    // Breeze ডিফল্ট মেথডগুলো (অক্ষত)
    // ==========================================
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = $request->file('profile_picture')->store('profiles', 'public');
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->is_super_admin) {
            return back()->withErrors(['userDeletion' => 'সুপার অ্যাডমিন অ্যাকাউন্ট ডিলিট করা নিষিদ্ধ!']);
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}