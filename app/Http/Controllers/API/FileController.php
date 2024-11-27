<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Directory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    // GET /api/files
    public function index()
    {
        $files = File::all();
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
            'directory_id' => 'required|exists:directories,id',
            'file'         => 'required|file',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $directory = Directory::find($request->directory_id);
        $path = $request->file('file')->store('files/' . $directory->id, 'public');

        $file = File::create([
            'name'         => $request->name,
            'path'         => $path,
            'directory_id' => $directory->id,
        ]);

        return response()->json($file, 201);
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
            'directory_id' => 'sometimes|exists:directories,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Update the name if provided
        if ($request->has('name')) {
            $file->name = $request->name;
        }
    
        // Update the directory if provided
        $directoryId = $request->directory_id ?? $file->directory_id;
        $directory = Directory::find($directoryId);
    
        // Handle file replacement
        if ($request->hasFile('file')) {
            // Delete old file
            Storage::disk('public')->delete($file->path);
    
            // Store new file in the updated or existing directory
            $path = $request->file('file')->store('files/' . $directory->id, 'public');
            $file->path = $path;
        }
    
        // Update the directory ID
        $file->directory_id = $directory->id;
    
        // Save changes
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
}