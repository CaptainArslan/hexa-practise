<?php

namespace App\Http\Controllers;

use App\Models\TempFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $folder = uniqid() . '-' . now()->timestamp;

            $path = Storage::put($folder, $file, 'public');

            TempFile::create([
                'folder' => $folder,
                'filename' => $this->getFileName($path),
                'path' => $path
            ]);

            return $folder;
        }
        return false;
    }

    private function getFileName($path)
    {
        $path = explode('/', $path);
        return end($path);
    }

    public function delete(Request $request)
    {
        dd($request->all());
        $tempFile = TempFile::where('folder', $request->folder)->first();
        if ($tempFile) {
            Storage::deleteDirectory($tempFile->folder);
            $tempFile->delete();
        }
        return true;
    }
}
