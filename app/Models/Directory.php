<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    protected $fillable = ['name', 'parent_id'];

    public function children()
    {
        return $this->hasMany(Directory::class, 'parent_id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}