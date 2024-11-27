<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Directory;
use Illuminate\Support\Facades\Validator;

class DirectoryController extends Controller
{
    // GET /api/directories
    public function index()
    {
        $directories = Directory::whereNull('parent_id')->with('children')->get();
        return response()->json($directories, 200);
    }

    // GET /api/directories/{id}/sub-directories
    public function subDirectories($id)
    {
        $directory = Directory::find($id);
        if (!$directory) {
            return response()->json(['error' => 'Directory not found'], 404);
        }

        $subDirectories = $directory->children()->with('children')->get();
        return response()->json($subDirectories, 200);
    }

    // GET /api/directories/{id}/files
    public function files($id)
    {
        $directory = Directory::find($id);
        if (!$directory) {
            return response()->json(['error' => 'Directory not found'], 404);
        }

        $files = $directory->files;
        return response()->json($files, 200);
    }

    // POST /api/directories
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:directories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $directory = Directory::create([
            'name'      => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json($directory, 201);
    }

    // PUT /api/directories/{id}
    public function update(Request $request, $id)
    {
        $directory = Directory::find($id);
        if (!$directory) {
            return response()->json(['error' => 'Directory not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'      => 'sometimes|required|string|max:255',
            'parent_id' => 'sometimes|nullable|exists:directories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('name')) {
            $directory->name = $request->name;
        }

        if ($request->has('parent_id')) {
            $directory->parent_id = $request->parent_id;
        }

        $directory->save();

        return response()->json($directory, 200);
    }

    // DELETE /api/directories/{id}
    public function destroy($id)
    {
        $directory = Directory::find($id);
        if (!$directory) {
            return response()->json(['error' => 'Directory not found'], 404);
        }

        if ($directory->children()->count() > 0 || $directory->files()->count() > 0) {
            return response()->json(['error' => 'Directory is not empty'], 400);
        }

        $directory->delete();

        return response()->json(['message' => 'Directory deleted successfully'], 200);
    }
}