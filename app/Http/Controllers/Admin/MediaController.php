<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\CmsContent;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cmsImageDir = 'images/cms';
        $cmsDocDir = 'documents';
        $attachmentDir = 'attachments';
        
        $cmsImages = Storage::disk('public')->exists($cmsImageDir) ? Storage::disk('public')->files($cmsImageDir) : [];
        $cmsDocs = Storage::disk('public')->exists($cmsDocDir) ? Storage::disk('public')->files($cmsDocDir) : [];
        $attachments = Storage::disk('public')->exists($attachmentDir) ? Storage::disk('public')->allFiles($attachmentDir) : [];
        
        // 1. Brand Architecture Assets (Driven by CMS Configuration)
        // These are the 4 slots the user sees in CMS Settings
        $keys = ['login_bg_image', 'login_form_logo', 'hero_bg_image', 'navbar_logo'];
        $cmsBrandRecords = CmsContent::whereIn('key', $keys)
            ->orderByRaw("FIELD(`key`, '" . implode("','", $keys) . "')")
            ->get();

        $landingPageMedia = [];
        foreach ($cmsBrandRecords as $content) {
            $exists = $content->value && Storage::disk('public')->exists($content->value);
            $landingPageMedia[] = [
                'key' => $content->key,
                'label' => $content->label,
                'path' => $content->value,
                'name' => $content->value ? basename($content->value) : 'No Asset',
                'url' => $content->value ? asset('storage/' . $content->value) : null,
                'size' => $exists ? round(Storage::disk('public')->size($content->value) / 1024, 2) . ' KB' : '0 KB',
                'last_modified' => $exists ? date('Y-m-d H:i:s', Storage::disk('public')->lastModified($content->value)) : 'N/A',
                'is_active' => true,
                'type' => $content->value && $this->isImage($content->value) ? 'image' : 'document',
            ];
        }

        // 2. System Blob Registry (Physical files not in the 4 core slots or unreferenced)
        $usedPaths = $cmsBrandRecords->pluck('value')->filter()->toArray();
        $systemAssets = [];
        foreach (array_merge($cmsImages, $cmsDocs) as $file) {
            if (!in_array($file, $usedPaths)) {
                $systemAssets[] = [
                    'path' => $file,
                    'name' => basename($file),
                    'url' => asset('storage/' . $file),
                    'size' => round(Storage::disk('public')->size($file) / 1024, 2) . ' KB',
                    'last_modified' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file)),
                    'is_active' => false,
                    'type' => $this->isImage($file) ? 'image' : 'document',
                ];
            }
        }

        // 3. User Evidence Registry (Using Model for context)
        $userAttachmentQuery = \App\Models\TicketAttachment::with(['ticket.user', 'ticket.category'])->latest();

        if (request('search')) {
            $search = request('search');
            $userAttachmentQuery->where(function($q) use ($search) {
                $q->where('file_name', 'like', "%{$search}%")
                  ->orWhereHas('ticket', function($tq) use ($search) {
                      $tq->where('id', 'like', "%{$search}%")
                         ->orWhereHas('user', function($uq) use ($search) {
                             $uq->where('name', 'like', "%{$search}%");
                         });
                  });
            });
        }

        if (request('type')) {
            $type = request('type');
            if ($type === 'image') {
                $userAttachmentQuery->where(function($q) {
                    $q->where('file_path', 'like', '%.jpg')
                      ->orWhere('file_path', 'like', '%.jpeg')
                      ->orWhere('file_path', 'like', '%.png')
                      ->orWhere('file_path', 'like', '%.gif')
                      ->orWhere('file_path', 'like', '%.svg')
                      ->orWhere('file_path', 'like', '%.webp');
                });
            } elseif ($type === 'document') {
                $userAttachmentQuery->where(function($q) {
                    $q->where('file_path', 'not like', '%.jpg')
                      ->where('file_path', 'not like', '%.jpeg')
                      ->where('file_path', 'not like', '%.png')
                      ->where('file_path', 'not like', '%.gif')
                      ->where('file_path', 'not like', '%.svg')
                      ->where('file_path', 'not like', '%.webp');
                });
            }
        }

        $userAttachments = $userAttachmentQuery->get()->map(function($attachment) {
            $exists = \Illuminate\Support\Facades\Storage::disk('public')->exists($attachment->file_path);
            return [
                'path' => $attachment->file_path,
                'name' => $attachment->file_name,
                'url' => asset('storage/' . $attachment->file_path),
                'size' => $exists ? round(\Illuminate\Support\Facades\Storage::disk('public')->size($attachment->file_path) / 1024, 2) . ' KB' : '0 KB',
                'last_modified' => $attachment->created_at->format('Y-m-d H:i:s'),
                'type' => $this->isImage($attachment->file_path) ? 'image' : 'document',
                'employee' => $attachment->ticket->user->name ?? 'Unknown',
                'ticket_id' => $attachment->ticket->id ?? 'N/A',
                'ticket_uuid' => $attachment->ticket->uuid ?? null,
            ];
        });

        return view('admin.media.index', compact('landingPageMedia', 'systemAssets', 'userAttachments'));
    }

    private function isImage($path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $path = $request->path;

        $isActive = CmsContent::where('value', $path)->exists();
        if ($isActive) {
            return redirect()->back()->with('error', 'Cannot delete an active image being used in the Landing Page.');
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return redirect()->back()->with('success', 'Media deleted successfully.');
        }

        return redirect()->back()->with('error', 'File not found.');
    }
}
