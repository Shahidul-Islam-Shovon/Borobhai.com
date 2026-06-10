<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Certification;

class ProfileDetailController extends Controller
{
    // ==========================================
    // EDUCATION
    // ==========================================
    public function storeEducation(Request $request)
    {
        // খালি স্ট্রিং date কে null করি (422 এড়াতে)
        $request->merge([
            'start_date' => $request->start_date ?: null,
            'end_date'   => $request->end_date ?: null,
        ]);

        $data = $request->validate([
            'id'          => 'nullable|integer',
            'degree'      => 'required|string|max:150',
            'institution' => 'required|string|max:255',
            'field'       => 'nullable|string|max:150',
            'result'      => 'nullable|string|max:50',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date',
            'is_current'  => 'nullable|boolean',
        ]);

        $data['user_id']    = Auth::id();
        $data['is_current'] = $request->boolean('is_current');
        if ($data['is_current']) $data['end_date'] = null;

        if ($request->filled('id')) {
            $edu = Education::where('id', $request->id)->where('user_id', Auth::id())->firstOrFail();
            $edu->update($data);
        } else {
            $edu = Education::create($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Education saved!',
            'item'    => $this->formatEducation($edu->fresh()),
        ]);
    }

    public function deleteEducation($id)
    {
        $edu = Education::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $edu->delete();
        return response()->json(['success' => true, 'message' => 'Education removed']);
    }

    // ==========================================
    // EXPERIENCE
    // ==========================================
    public function storeExperience(Request $request)
    {
        $request->merge([
            'start_date' => $request->start_date ?: null,
            'end_date'   => $request->end_date ?: null,
        ]);

        $data = $request->validate([
            'id'              => 'nullable|integer',
            'company'         => 'required|string|max:255',
            'designation'     => 'required|string|max:150',
            'location'        => 'nullable|string|max:150',
            'employment_type' => 'nullable|string|max:50',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date',
            'is_current'      => 'nullable|boolean',
            'description'     => 'nullable|string|max:1000',
        ]);

        $data['user_id']    = Auth::id();
        $data['is_current'] = $request->boolean('is_current');
        if ($data['is_current']) $data['end_date'] = null;

        if ($request->filled('id')) {
            $exp = Experience::where('id', $request->id)->where('user_id', Auth::id())->firstOrFail();
            $exp->update($data);
        } else {
            $exp = Experience::create($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Experience saved!',
            'item'    => $this->formatExperience($exp->fresh()),
        ]);
    }

    public function deleteExperience($id)
    {
        $exp = Experience::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $exp->delete();
        return response()->json(['success' => true, 'message' => 'Experience removed']);
    }

    // ==========================================
    // CERTIFICATION
    // ==========================================
    public function storeCertification(Request $request)
    {
        $request->merge([
            'issue_date' => $request->issue_date ?: null,
        ]);

        $data = $request->validate([
            'id'             => 'nullable|integer',
            'title'          => 'required|string|max:255',
            'organization'   => 'nullable|string|max:255',
            'issue_date'     => 'nullable|date',
            'credential_url' => 'nullable|url|max:255',
        ]);

        $data['user_id'] = Auth::id();

        if ($request->filled('id')) {
            $cert = Certification::where('id', $request->id)->where('user_id', Auth::id())->firstOrFail();
            $cert->update($data);
        } else {
            $cert = Certification::create($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Certification saved!',
            'item'    => $this->formatCertification($cert->fresh()),
        ]);
    }

    public function deleteCertification($id)
    {
        $cert = Certification::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cert->delete();
        return response()->json(['success' => true, 'message' => 'Certification removed']);
    }

    // ==========================================
    // ফরম্যাট হেল্পার (JS এ সহজে ব্যবহারের জন্য)
    // ==========================================
    private function formatEducation($e)
    {
        return [
            'id'          => $e->id,
            'degree'      => $e->degree,
            'institution' => $e->institution,
            'field'       => $e->field,
            'result'      => $e->result,
            'start_date'  => $e->start_date ? $e->start_date->format('Y-m-d') : null,
            'end_date'    => $e->end_date ? $e->end_date->format('Y-m-d') : null,
            'is_current'  => $e->is_current,
            'duration'    => $this->durationText($e->start_date, $e->end_date, $e->is_current),
        ];
    }

    private function formatExperience($e)
    {
        return [
            'id'              => $e->id,
            'company'         => $e->company,
            'designation'     => $e->designation,
            'location'        => $e->location,
            'employment_type' => $e->employment_type,
            'start_date'      => $e->start_date ? $e->start_date->format('Y-m-d') : null,
            'end_date'        => $e->end_date ? $e->end_date->format('Y-m-d') : null,
            'is_current'      => $e->is_current,
            'description'     => $e->description,
            'duration'        => $this->durationText($e->start_date, $e->end_date, $e->is_current),
        ];
    }

    private function formatCertification($c)
    {
        return [
            'id'             => $c->id,
            'title'          => $c->title,
            'organization'   => $c->organization,
            'issue_date'     => $c->issue_date ? $c->issue_date->format('Y-m-d') : null,
            'issue_year'     => $c->issue_date ? $c->issue_date->format('Y') : null,
            'credential_url' => $c->credential_url,
        ];
    }

    // "Jan 2020 - Present" টাইপ টেক্সট বানায়
    private function durationText($start, $end, $isCurrent)
    {
        if (!$start && !$end) return '';
        $s = $start ? $start->format('M Y') : '';
        $e = $isCurrent ? 'Present' : ($end ? $end->format('M Y') : '');
        if ($s && $e) return "$s - $e";
        return $s ?: $e;
    }
}