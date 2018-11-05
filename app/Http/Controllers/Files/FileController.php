<?php

namespace App\Http\Controllers\Files;

use App\Http\Requests\Files\FileRequest;
use App\Http\Resources\Files\FileResource;
use App\Models\Files\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        $files = File::collect();

        return FileResource::collection($files);
    }

    public function show(File $file)
    {
        return new FileResource($file);
    }

    /**
     * @param File $file
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function open(File $file)
    {
        /**
         * Source from https://stackoverflow.com/a/25938629/5128251
         *
         * Opens the file without forcing browser to download it
         */
        $content = Storage::didsk($file->disk)->get($file->path);
        return response()->make($content, 200, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="'.$file->name.'"'
        ]);
    }

    public function store(FileRequest $request)
    {
        $disk = config('filesystems.default');
        $folder = 'files';

        $document = $request->file('document');
        $name = $this->getFileName($request, $document);

        $options = [
            'disk' => $disk,
            'ContentDisposition' => "filename=\"$name\"",
        ];

        $path = $document->store($folder, $options);

        $file = File::create([
            'name' => $name,
            'mime_type' => $document->getMimeType(),
            'size' => $document->getSize(), //bytes
            'extension' => $document->getClientOriginalExtension(),
            'disk' => $disk,
            'path' => $path,
            'notes' => $request->input('notes'),
            'is_public' => false,
            'meta' => $request->input('meta'),
        ]);

        return new FileResource($file);
    }

    public function update(File $file, FileRequest $request)
    {
        $file->update(
            $request->only(['name', 'notes', 'meta'])
        );
        $file->save();

        return new FileResource($file->refresh());
    }

    public function destroy(File $file)
    {
        DB::transaction(function () use ($file) {
            $storage_disk = Storage::disk($file->disk);

            // Delete file from disk
            if ($storage_disk->exists($file->path)) {
                $storage_disk->delete($file->path);
            }

            // Delete file meta from db
            return $file->delete();
        });

        return $this->respondNoContent();
    }

    /**
     * @param FileRequest $request
     * @param $document
     * @return array|null|string
     */
    protected function getFileName(FileRequest $request, $document)
    {
        $name = $request->input('name', $document->getClientOriginalName());
        // Check if name doesn't extension and append extension
        if (!preg_match("#^.*\.[^\\$]#i", $name)) {
            $name = $name . '.' . $document->getClientOriginalExtension();
        }
        return $name;
    }
}
