<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Directory;
use App\Models\File;

class DisplayDirectoryTree extends Command
{
    protected $signature = 'directory:tree';
    protected $description = 'Display the directory and file structure in a tree-like format';

    public function handle()
    {
        $this->line('root');
        $directories = Directory::whereNull('parent_id')->get();
    
        $items = $directories->concat(File::whereNull('directory_id')->get());
    
        $lastIndex = $items->count() - 1;
    
        foreach ($items as $index => $item) {
            $isLast = $index == $lastIndex;
    
            if ($item instanceof Directory) {
                $this->displayDirectory($item, '', $isLast);
            } else {
                // It's a file at the root level
                $connector = $isLast ? '└── ' : '├── ';
                $this->line($connector . $item->name);
            }
        }
    }

    protected function displayDirectory($directory, $prefix = '', $isLast = true)
    {
        // Determine the connector
        $connector = $isLast ? '└── ' : '├── ';
    
        // Display the directory name
        $this->line($prefix . $connector . $directory->name);
    
        // Prepare prefixes for children
        $prefix .= $isLast ? '    ' : '│   ';
    
        // Get subdirectories and files
        $children = $directory->children;
        $files = $directory->files;
    
        // Merge subdirectories and files into a single collection
        $items = $children->concat($files);
    
        $lastIndex = $items->count() - 1;
    
        foreach ($items as $index => $item) {
            $isItemLast = $index == $lastIndex;
    
            if ($item instanceof Directory) {
                $this->displayDirectory($item, $prefix, $isItemLast);
            } else {
                // It's a file
                $itemConnector = $isItemLast ? '└── ' : '├── ';
                $this->line($prefix . $itemConnector . $item->name);
            }
        }
    }
}