<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'extension',
        'description',
        'mime_type',
        'size'
    ];
    public function imageable()
    {
        return $this->morphTo();
    }

    public function getSizeMegas(){
        return $this->size / (1024 * 1024);
    }
}
