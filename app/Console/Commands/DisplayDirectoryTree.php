<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Directory;
use App\Models\File;

class DisplayDirectoryTree extends Command
{
    protected $signature = 'directory:tree';
    protected $description = 'Display the directory tree structure';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Start with the 'root' node
        $this->line('root');

        // Fetch root-level directories and files
        $rootDirectories = Directory::whereNull('parent_id')->get();
        $rootFiles = File::whereNull('directory_id')->get();

        // Merge directories and files into a single collection
        $rootItems = collect()->concat($rootDirectories)->concat($rootFiles);

        $rootItemCount = $rootItems->count();

        foreach ($rootItems as $index => $item) {
            $isLast = ($index === $rootItemCount - 1);
            $prefix = '';
            $connector = $isLast ? '└── ' : '├── ';

            if ($item instanceof Directory) {
                $this->displayDirectory($item, $prefix, $connector, $isLast);
            } elseif ($item instanceof File) {
                $this->line($prefix . $connector . $item->name);
            }
        }
    }

    private function displayDirectory($directory, $prefix, $connector, $isLast)
    {
        $this->line($prefix . $connector . $directory->name);

        $newPrefix = $prefix . ($isLast ? '    ' : '│   ');

        // Fetch subdirectories and files
        $subDirectories = $directory->subDirectories()->get() ?? collect();
        $files = $directory->files()->get() ?? collect();

        // Merge subdirectories and files, subdirectories first
        $subItems = collect()->concat($subDirectories)->concat($files);

        $subItemCount = $subItems->count();

        foreach ($subItems as $index => $item) {
            $isSubLast = ($index === $subItemCount - 1);
            $subConnector = $isSubLast ? '└── ' : '├── ';

            if ($item instanceof Directory) {
                $this->displayDirectory($item, $newPrefix, $subConnector, $isSubLast);
            } elseif ($item instanceof File) {
                $this->line($newPrefix . $subConnector . $item->name);
            }
        }
    }
}