<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    protected $fillable = ['name', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Directory::class, 'parent_id');
    }

    public function subDirectories()
    {
        return $this->hasMany(Directory::class, 'parent_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'directory_id');
    }
}