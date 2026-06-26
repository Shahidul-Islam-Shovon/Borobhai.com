<?php
namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type'    => 'required|in:post,user',
            'id'      => 'required|integer',
            'reason'  => 'required|in:spam,harassment,fake,inappropriate,other',
            'details' => 'nullable|string|max:500',
        ]);

        $meId = Auth::id();

        // নিজেকে report করা যাবে না
        if ($request->type === 'user' && $request->id == $meId) {
            return response()->json(['success' => false, 'message' => 'You cannot report yourself.']);
        }

        // একই জিনিস বারবার report করা যাবে না
        $exists = Report::where('reporter_id', $meId)
            ->where('reportable_type', $request->type)
            ->where('reportable_id', $request->id)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'You have already reported this.']);
        }

        Report::create([
            'reporter_id'     => $meId,
            'reportable_type' => $request->type,
            'reportable_id'   => $request->id,
            'reason'          => $request->reason,
            'details'         => $request->details,
        ]);

        return response()->json(['success' => true, 'message' => 'Report submitted. Our team will review it.']);
    }
}