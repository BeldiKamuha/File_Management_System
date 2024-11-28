<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['name', 'directory_id', 'path'];

    public function directory()
    {
        return $this->belongsTo(Directory::class, 'directory_id');
    }
}