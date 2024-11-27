<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['name', 'path', 'directory_id'];

    public function directory()
    {
        return $this->belongsTo(Directory::class);
    }
}