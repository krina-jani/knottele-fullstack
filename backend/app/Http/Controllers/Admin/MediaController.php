<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaController extends Controller
{
    private ?ImageManager $imageManager = null;

    public function __construct()
    {
         if (class_exists(Driver::class) && extension_loaded('gd')) {
            $this->imageManager = new ImageManager(new Driver());
        }
    }

    public function index()
    {
        return view('admin.media.index');
    }

    /**
     * Get Media Data for Modals/AJAX
     */
    public function getData(Request $request)
    {
        $directory = public_path('images');
        $files = \Illuminate\Support\Facades\File::files($directory);
        
        $mediaList = [];
        $idCounter = 1;
        
        foreach ($files as $file) {
            $fileName = $file->getFilename();
            $mimeType = mime_content_type($file->getPathname()) ?: 'application/octet-stream';
            $size = $file->getSize();
            $url = asset('images/' . $fileName);

            // Format size for humans
            $units = ['B', 'KB', 'MB', 'GB'];
            $pow = $size > 0 ? floor(log($size, 1024)) : 0;
            $formattedSize = round($size / (1024 ** $pow), 2) . ' ' . $units[$pow];
            
            // Format date
            $createdAtFormatted = date('M d, Y H:i', filectime($file->getPathname()));

            $mediaList[] = [
                'id' => $idCounter++,
                'url' => $url,
                'thumbnail_url' => $url, // Expected by index.blade.php
                'thumb_url' => $url,     // Expected by create.blade.php
                'path' => 'images/' . $fileName,
                'file_path' => 'images/' . $fileName,
                'file_name' => $fileName,
                'filename' => $fileName,
                'name' => $fileName,
                'mime_type' => $mimeType,
                'size' => $size, 
                'size_formatted' => $formattedSize, 
                'is_image' => str_starts_with($mimeType, 'image/'),
                'created_at' => date('c', filectime($file->getPathname())), 
                'created_at_formatted' => $createdAtFormatted, 
                'alt_text' => null
            ];
        }

        // Apply search filter if requested
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $mediaList = array_filter($mediaList, function($item) use ($search) {
                return str_contains(strtolower($item['file_name']), $search);
            });
            $mediaList = array_values($mediaList);
        }

        // Mock pagination structure
        $perPage = 12;
        $page = (int) $request->input('page', 1);
        $total = count($mediaList);
        $totalPages = ceil($total / $perPage);
        
        $paginatedItems = array_slice($mediaList, ($page - 1) * $perPage, $perPage);

        return response()->json([
            'current_page' => $page,
            'data' => $paginatedItems,
            'last_page' => $totalPages,
            'total' => $total,
            'per_page' => $perPage,
            'to' => count($paginatedItems) > 0 ? ($page - 1) * $perPage + count($paginatedItems) : 0
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
             'files.*' => 'required|file|max:10240', // 10MB max per file
        ]);

        if (!$request->hasFile('files')) {
            return response()->json(['success' => false, 'message' => 'No files uploaded'], 400);
        }

        $uploadedMedia = [];
        $errors = [];

        foreach ($request->file('files') as $file) {
            try {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                $uniqueName = Str::slug($fileName) . '_' . time() . '_' . Str::random(5) . '.' . $extension;
                
                $storagePath = 'products/media/' . date('Y/m');
                $fullPath = $storagePath . '/' . $uniqueName;
                
                Storage::disk('public')->putFileAs($storagePath, $file, $uniqueName);
                
                // Create thumbnails if image
                $thumbnails = [];
                if (str_starts_with($file->getMimeType(), 'image/') && isset($this->imageManager)) {
                    $thumbnails = $this->createThumbnails($file, $storagePath, $uniqueName);
                }

                $media = Media::create([
                    'file_name' => $originalName,
                    'file_path' => $fullPath,
                    'disk' => 'public',
                    'mime_type' => $file->getMimeType(),
                    'file_type' => str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'document',
                    'file_size' => $file->getSize(),
                    'thumbnails' => $thumbnails ?: null,
                    'metadata' => [
                        'original_name' => $originalName,
                        'extension' => $extension,
                    ],
                    'uploaded_by' => auth()->id(), 
                    'uploader_type' => 'admin',
                ]);

                $uploadedMedia[] = [
                    'id' => $media->id,
                    'url' => asset(Storage::url($fullPath)),
                    'file_name' => $originalName
                ];

            } catch (\Exception $e) {
                $errors[] = "Failed to upload {$file->getClientOriginalName()}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => count($errors) === 0,
            'data' => count($uploadedMedia) > 0 ? $uploadedMedia[0] : null, // Backwards compatibility for single select UI
            'all_uploaded' => $uploadedMedia,
            'errors' => $errors
        ]);
    }

    private function createThumbnails($file, $storagePath, $fileName)
    {
        $thumbnails = [];
        try {
             $image = $this->imageManager->read($file->getRealPath());
             $originalName = pathinfo($fileName, PATHINFO_FILENAME);
             $extension = pathinfo($fileName, PATHINFO_EXTENSION);

             // Small
             $smallName = $originalName . '_small.' . $extension;
             $smallImage = clone $image;
             $smallImage->cover(150, 150);
             Storage::disk('public')->put($storagePath . '/' . $smallName, (string) $smallImage->encodeByExtension($extension));
             $thumbnails['small'] = $storagePath . '/' . $smallName;

        } catch(\Exception $e) {
            // Squelch image error to ensure primary upload succeeds
        }
        
        return $thumbnails;
    }
}
