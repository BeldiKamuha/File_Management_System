<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Directory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    // GET /api/files
    public function index(Request $request)
    {
        $directoryId = $request->query('directory_id');

        // Log the received directory_id for debugging
        Log::info('Fetching files with directory_id:', ['directory_id' => $directoryId]);

        if ($directoryId === null || strtolower($directoryId) === 'null' || $directoryId === '') {
            // Fetch files in root directory (directory_id is NULL)
            $files = File::whereNull('directory_id')->get();
            Log::info('Fetched files in root directory:', ['count' => $files->count()]);
        } else {
            // Fetch files in the specified directory
            $files = File::where('directory_id', $directoryId)->get();
            Log::info('Fetched files in directory_id ' . $directoryId . ':', ['count' => $files->count()]);
        }

        return response()->json($files, 200);
    }

    // GET /api/files/{id}
    public function show($id)
    {
        $file = File::find($id);
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }
        return response()->json($file, 200);
    }

    // POST /api/files
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name'         => 'required|string|max:255',
        'directory_id' => 'nullable|exists:directories,id',
        'file'         => 'required|file',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $fileModel = new File();
    $fileModel->name = $request->input('name');
    $fileModel->directory_id = $request->input('directory_id');

    $uploadedFile = $request->file('file');
    $path = $uploadedFile->store('files');

    $fileModel->path = $path;
    $fileModel->save();

    return response()->json($fileModel, 201);
}

    // PUT /api/files/{id}
    public function update(Request $request, $id)
    {
        $file = File::find($id);
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'         => 'sometimes|required|string|max:255',
            'file'         => 'sometimes|file',
            'directory_id' => 'sometimes|nullable|exists:directories,id', // Allow null
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update the name if provided
        if ($request->has('name')) {
            $file->name = $request->name;
        }

        // Update the directory_id if provided
        if ($request->has('directory_id')) {
            $file->directory_id = $request->directory_id;
        }

        // Handle file replacement if a new file is provided
        if ($request->hasFile('file')) {
            // Delete the old file
            Storage::disk('public')->delete($file->path);

            // Store the new file
            $directoryId = $request->directory_id ?? $file->directory_id;
            $path = $request->file('file')->store('files/' . ($directoryId ?? 'root'), 'public');
            $file->path = $path;
        }

        $file->save();

        return response()->json($file, 200);
    }

    // DELETE /api/files/{id}
    public function destroy($id)
    {
        $file = File::find($id);
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Delete file from storage
        Storage::disk('public')->delete($file->path);
        $file->delete();

        return response()->json(['message' => 'File deleted successfully'], 200);
    }


public function download($id)
{
    $file = File::find($id);

    if (!$file || !Storage::exists($file->path)) {
        return response()->json(['error' => 'File not found'], 404);
    }

    return Storage::download($file->path, $file->name);
}

public function rename(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $file = File::find($id);

    if (!$file) {
        return response()->json(['error' => 'File not found'], 404);
    }

    $file->name = $request->input('name');
    $file->save();

    return response()->json($file, 200);
}

}