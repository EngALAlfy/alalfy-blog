<?php

namespace App\Models;

use App\Enums\PostStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia
{
    use HasTags;
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description',
        'description',
        'status',
        'status_at',
        'author_id',
        'category_id',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected function casts(): array
    {
        return [
            'status_at' => 'datetime',
            'status' => PostStatusEnum::class,
        ];
    }

    public function registerMediaCollections(): void
    {
        $fallbackText = 'P';
        if (!empty($this->title)) {
            $fallbackText = preg_replace('/\b(\w)/', '$1', ucwords($this->title));
            $fallbackText = preg_replace('/[^A-Z]/', '', $fallbackText);
        }

        $this->addMediaCollection('banner')->useFallbackUrl("https://placehold.co/700x400?text=" . $fallbackText);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
