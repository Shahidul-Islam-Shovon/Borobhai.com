<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;

class DocumentController extends Controller
{
    // ==========================================
    // নতুন document আপলোড (বা এডিট)
    // ==========================================
    public function store(Request $request)
    {
        $request->merge([
            'publication_year' => $request->publication_year ?: null,
        ]);

        $rules = [
            'id'               => 'nullable|integer',
            'title'            => 'required|string|max:255',
            'type'             => 'required|string|max:50',
            'topic'            => 'nullable|string|max:255',
            'description'      => 'nullable|string|max:2000',
            'publication_year' => 'nullable|digits:4',
            // pdf, word, ppt, txt — যেকোনো document (max 20MB)
            'file'             => 'nullable|file|max:20480|mimes:pdf,doc,docx,ppt,pptx,txt,odt,rtf',
        ];

        // নতুন হলে ফাইল আবশ্যক
        if (!$request->filled('id')) {
            $rules['file'] = 'required|file|max:20480|mimes:pdf,doc,docx,ppt,pptx,txt,odt,rtf';
        }

        $data = $request->validate($rules);

        // এডিট
        if ($request->filled('id')) {
            $doc = Document::where('id', $request->id)->where('user_id', Auth::id())->firstOrFail();
        } else {
            $doc = new Document();
            $doc->user_id = Auth::id();
        }

        $doc->title            = $request->title;
        $doc->type             = $request->type;
        $doc->topic            = $request->topic;
        $doc->description      = $request->description;
        $doc->publication_year = $request->publication_year;

        // নতুন ফাইল এলে আপলোড (পুরনোটা মুছে)
        if ($request->hasFile('file')) {
            if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }
            $file = $request->file('file');
            $doc->file_path = $file->store('documents', 'public');
            $doc->file_name = $file->getClientOriginalName();
            $doc->file_type = strtolower($file->getClientOriginalExtension());
            $doc->file_size = $file->getSize();
        }

        $doc->save();

        return response()->json([
            'success' => true,
            'message' => 'Document saved!',
            'item'    => $this->formatDoc($doc->fresh()),
        ]);
    }

    // ==========================================
    // delete
    // ==========================================
    public function destroy($id)
    {
        $doc = Document::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }
        $doc->delete();

        return response()->json(['success' => true, 'message' => 'Document removed']);
    }

    // ==========================================
    // ফরম্যাট হেল্পার (JS এ সহজে ব্যবহারের জন্য)
    // ==========================================
    private function formatDoc($d)
    {
        return [
            'id'               => $d->id,
            'title'            => $d->title,
            'type'             => $d->type,
            'topic'            => $d->topic,
            'description'      => $d->description,
            'publication_year' => $d->publication_year,
            'file_name'        => $d->file_name,
            'file_type'        => $d->file_type,
            'file_url'         => asset('storage/'.$d->file_path),
            'readable_size'    => $d->readable_size,
        ];
    }
}