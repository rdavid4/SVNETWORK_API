<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'extension',
        'description',
        'mime_type',
        'width',
        'height',
        'size'
    ];
    public function imageable()
    {
        return $this->morphTo();
    }

    public function getSizeMegas(){
        return $this->size / (1024 * 1024);
    }

    public function getUrlAttribute(){
        $url = Storage::disk('projects')->url($this->filename);
        return $url;
    }
    public function getUrlCompanyAttribute(){
        $url = Storage::disk('companies')->url($this->filename);
        return $url;
    }
}
