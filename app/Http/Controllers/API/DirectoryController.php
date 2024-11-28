<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Directory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DirectoryController extends Controller
{
    // List directories, optionally filter by parent_id
    public function index(Request $request)
    {
        $parentId = $request->query('parent_id');

        if ($parentId === 'null' || $parentId === null) {
            $directories = Directory::whereNull('parent_id')->get();
        } else {
            $directories = Directory::where('parent_id', $parentId)->get();
        }

        return response()->json($directories, 200);
    }

    // Store a new directory
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'parent_id'  => 'nullable|exists:directories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $directory = new Directory();
        $directory->name = $request->input('name');
        $directory->parent_id = $request->input('parent_id');
        $directory->save();

        return response()->json($directory, 201);
    }

    // Show a specific directory
    public function show($id)
    {
        $directory = Directory::find($id);

        if (!$directory) {
            return response()->json(['error' => 'Directory not found'], 404);
        }

        return response()->json($directory, 200);
    }

    // Update a directory's name
    public function update(Request $request, $id)
    {
        $directory = Directory::find($id);

        if (!$directory) {
            return response()->json(['error' => 'Directory not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $directory->name = $request->input('name');
        $directory->save();

        return response()->json($directory, 200);
    }

    // Delete a directory if it is empty
    public function destroy($id)
    {
        $directory = Directory::find($id);

        if (!$directory) {
            return response()->json(['error' => 'Directory not found'], 404);
        }

        // Check if directory is empty (no subdirectories and no files)
        $hasSubDirectories = $directory->subDirectories()->exists();
        $hasFiles = $directory->files()->exists();

        if ($hasSubDirectories || $hasFiles) {
            return response()->json(['error' => 'Cannot delete a directory that is not empty'], 400);
        }

        $directory->delete();

        return response()->json(['message' => 'Directory deleted successfully'], 200);
    }

    // Get subdirectories of a directory
    public function subDirectories($id)
    {
        $directory = Directory::find($id);

        if (!$directory) {
            return response()->json(['error' => 'Directory not found'], 404);
        }

        $subDirectories = Directory::where('parent_id', $id)->get();

        return response()->json($subDirectories, 200);
    }

    // Get files within a directory
    public function files($id)
    {
        $directory = Directory::find($id);

        if (!$directory) {
            return response()->json(['error' => 'Directory not found'], 404);
        }

        $files = $directory->files()->get();

        return response()->json($files, 200);
    }
}