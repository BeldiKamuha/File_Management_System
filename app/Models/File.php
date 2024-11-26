<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'path', 'directory_id'];

    public function directory()
    {
        return $this->belongsTo(Directory::class);
    }
}