<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\BbNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // ReportController@store (বা আগের যেখানে /report POST handle হয়)
    public function store(Request $request)
    {
        $data = $request->validate([
            'type'    => 'required|in:post,job,user',
            'id'      => 'required|integer',
            'reason'  => 'required|string',
            'details' => 'nullable|string|max:500',
        ]);

        // একই ইউজার একই content একাধিকবার report করতে পারবে না
        $exists = \App\Models\Report::where('reporter_id', auth()->id())
            ->where('type', $data['type'])
            ->where('target_id', $data['id'])
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Already reported.']);
        }

        \App\Models\Report::create([
            'reporter_id' => auth()->id(),
            'type'        => $data['type'],
            'target_id'   => $data['id'],
            'reason'      => $data['reason'],
            'details'     => $data['details'] ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Report submitted.']);
    }
}