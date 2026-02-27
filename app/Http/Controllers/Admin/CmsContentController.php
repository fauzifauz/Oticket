<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsContent;

class CmsContentController extends Controller
{
    public function index()
    {
        $contents = CmsContent::all();
        return view('admin.cms.index', compact('contents'));
    }

    public function update(Request $request)
    {
        $imageKeys = CmsContent::where('type', 'image')->pluck('key')->toArray();
        $validationRules = [];
        foreach ($imageKeys as $key) {
            $validationRules[$key] = 'nullable|image|max:5120';
        }

        $request->validate($validationRules, [
            '*.max' => 'File must be uploaded within the allowed size limit (Max: 5MB for images/documents).',
        ]);

        $data = $request->except('_token', '_method');

        foreach ($data as $key => $value) {
            $content = CmsContent::where('key', $key)->first();
            if (!$content) continue;

            if ($request->hasFile($key) && $content->type === 'image') {
                $file = $request->file($key);
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('images/cms', $filename, 'public');
                $content->update(['value' => $path]);
            } elseif ($content->type !== 'image') {
                $content->update(['value' => $value]);
            }
        }

        \Cache::forget('cms_contents');

        return redirect()->back()->with('success', 'Content updated successfully.');
    }
}
