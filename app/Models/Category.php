<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Category extends Model implements HasMedia
{
    use HasTags;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'short_description',
        'description',
    ];
}
