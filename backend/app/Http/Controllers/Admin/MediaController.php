<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        return view('admin.media.index');
    }

    /**
     * Get Media Data for Modals/AJAX (directly from MySQL Database)
     */
    public function getData(Request $request)
    {
        // Auto-seed if media table is empty
        if (Media::count() === 0) {
            $this->syncLocalImagesToDatabase();
        }

        $query = Media::query()->latest('id');

        // Search filter
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('file_name', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%")
                  ->orWhere('file_path', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('file_type', $request->type);
        }

        $perPage = (int) $request->input('per_page', 24);
        $mediaPaginated = $query->paginate($perPage);

        $data = $mediaPaginated->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'url' => $item->url,
                'thumbnail_url' => $item->thumb_url,
                'thumb_url' => $item->thumb_url,
                'full_url' => $item->full_url,
                'file_path' => $item->file_path,
                'file_name' => $item->file_name,
                'filename' => $item->file_name,
                'name' => $item->file_name,
                'mime_type' => $item->mime_type ?: 'image/jpeg',
                'size' => $item->file_size ?: 0,
                'size_formatted' => $this->formatSize($item->file_size ?: 0),
                'is_image' => str_starts_with($item->mime_type ?? 'image/', 'image/'),
                'created_at' => $item->created_at ? $item->created_at->toIso8601String() : now()->toIso8601String(),
                'created_at_formatted' => $item->created_at ? $item->created_at->format('M d, Y') : '',
                'alt_text' => $item->alt_text,
            ];
        });

        return response()->json([
            'current_page' => $mediaPaginated->currentPage(),
            'data' => $data,
            'last_page' => $mediaPaginated->lastPage(),
            'total' => $mediaPaginated->total(),
            'per_page' => $mediaPaginated->perPage(),
            'from' => $mediaPaginated->firstItem(),
            'to' => $mediaPaginated->lastItem(),
            'prev_page_url' => $mediaPaginated->previousPageUrl(),
            'next_page_url' => $mediaPaginated->nextPageUrl(),
            'links' => $mediaPaginated->linkCollection()->toArray(),
        ]);
    }

    /**
     * Upload Media from Desktop/Mobile Gallery
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'nullable|array',
            'files.*' => 'file|max:20480', // 20MB max
            'file' => 'nullable|file|max:20480',
        ]);

        $files = [];
        if ($request->hasFile('files')) {
            $files = $request->file('files');
        } elseif ($request->hasFile('file')) {
            $files = [$request->file('file')];
        }

        if (empty($files)) {
            return response()->json(['success' => false, 'message' => 'No files were uploaded.'], 400);
        }

        $uploadedMedia = [];
        $errors = [];

        $targetDirectory = public_path('images/products');
        if (!File::isDirectory($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true, true);
        }

        foreach ($files as $file) {
            try {
                $originalName = $file->getClientOriginalName();
                $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg');
                $rawName = pathinfo($originalName, PATHINFO_FILENAME);
                $cleanSlug = Str::slug($rawName);
                $uniqueName = ($cleanSlug ?: 'knotelle-product') . '_' . time() . '_' . Str::random(4) . '.' . $extension;

                $file->move($targetDirectory, $uniqueName);
                $relPath = 'images/products/' . $uniqueName;
                $fullDiskPath = $targetDirectory . DIRECTORY_SEPARATOR . $uniqueName;
                $fileSize = file_exists($fullDiskPath) ? filesize($fullDiskPath) : 0;

                $media = Media::create([
                    'file_name' => $originalName,
                    'file_path' => $relPath,
                    'disk' => 'local',
                    'mime_type' => 'image/' . ($extension === 'png' ? 'png' : ($extension === 'webp' ? 'webp' : 'jpeg')),
                    'file_type' => 'image',
                    'file_size' => $fileSize,
                    'alt_text' => $rawName,
                    'uploaded_by' => auth()->id() ?: 1,
                    'uploader_type' => 'admin',
                ]);

                $uploadedMedia[] = [
                    'id' => $media->id,
                    'url' => $media->url,
                    'thumb_url' => $media->thumb_url,
                    'thumbnail_url' => $media->thumbnail_url,
                    'full_url' => $media->full_url,
                    'file_name' => $originalName,
                    'size_formatted' => $this->formatSize($fileSize),
                ];
            } catch (\Exception $e) {
                $errors[] = "Failed to upload {$file->getClientOriginalName()}: " . $e->getMessage();
            }
        }

        $success = count($uploadedMedia) > 0;

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Files uploaded successfully!' : 'Failed to upload files',
            'data' => $success ? $uploadedMedia[0] : null,
            'all_uploaded' => $uploadedMedia,
            'errors' => $errors,
        ]);
    }

    /**
     * Delete Media
     */
    public function destroy($id)
    {
        $media = Media::findOrFail($id);

        // Delete physical file if inside public/images/products
        if (str_starts_with($media->file_path, 'images/products/')) {
            $path = public_path($media->file_path);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $media->delete();

        return response()->json(['success' => true, 'message' => 'Media deleted successfully']);
    }

    /**
     * Helper to auto-sync local Knotelle directory images into database
     */
    public function syncLocalImagesToDatabase(): void
    {
        $folders = [
            'images/products' => 'product',
            'images/categories' => 'category',
            'images/logo' => 'logo',
            'images/hero' => 'hero',
            'images/homepage' => 'homepage',
        ];

        foreach ($folders as $relFolder => $group) {
            $absPath = public_path($relFolder);
            if (!File::isDirectory($absPath)) {
                continue;
            }

            $files = File::files($absPath);
            foreach ($files as $file) {
                $fileName = $file->getFilename();
                $filePath = $relFolder . '/' . $fileName;

                // Check if already in Media table
                $exists = Media::where('file_path', $filePath)->first();
                if (!$exists) {
                    $ext = strtolower($file->getExtension());
                    $mime = ($ext === 'png') ? 'image/png' : (($ext === 'webp') ? 'image/webp' : 'image/jpeg');

                    Media::create([
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'disk' => 'local',
                        'mime_type' => $mime,
                        'file_type' => 'image',
                        'file_size' => $file->getSize(),
                        'alt_text' => pathinfo($fileName, PATHINFO_FILENAME),
                        'uploaded_by' => 1,
                        'uploader_type' => 'admin',
                    ]);
                }
            }
        }
    }

    private function formatSize(int $bytes): string
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = floor(log($bytes, 1024));
        return round($bytes / (1024 ** $pow), 1) . ' ' . $units[$pow];
    }
}
