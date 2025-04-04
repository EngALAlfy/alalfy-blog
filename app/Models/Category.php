<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function registerMediaCollections(): void
    {
        $fallbackText = 'C';
        if (!empty($this->name)) {
            $fallbackText = preg_replace('/\b(\w)/', '$1', ucwords($this->name));
            $fallbackText = preg_replace('/[^A-Z]/', '', $fallbackText);
        }

        $this->addMediaCollection('banner')->useFallbackUrl("https://placehold.co/700x400?text=" . $fallbackText);
    }
}
