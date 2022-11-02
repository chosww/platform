<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Translatable\HasTranslations;

class Course extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'title',
        'introduction',
        'video',
    ];

    protected $casts = [
        'title' => 'array',
        'introduction' => 'array',
        'video' => 'array',
    ];

    public array $translatable = [
        'title',
        'introduction',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }
}
