<?php

namespace App\Models;

use App\Traits\HasContactPerson;
use App\Traits\HasMultimodalTranslations;
use App\Traits\HasMultipageEditingAndPublishing;
use Carbon\Carbon;
use Hearth\Traits\HasInvitations;
use Hearth\Traits\HasMembers;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
use Makeable\EloquentStatus\HasStatus;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class RegulatedOrganization extends Model
{
    use CascadesDeletes;
    use HasContactPerson;
    use HasFactory;
    use HasMultipageEditingAndPublishing;
    use HasStatus;
    use HasTranslations;
    use HasTranslatableSlug;
    use HasInvitations;
    use HasMembers;
    use HasMultimodalTranslations;
    use Notifiable;

    protected $attributes = [
        'preferred_contact_method' => 'email',
        'preferred_notification_method' => 'email',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'published_at',
        'name',
        'type',
        'languages',
        'region',
        'locality',
        'about',
        'service_areas',
        'accessibility_and_inclusion_links',
        'social_links',
        'website_link',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
        'contact_person_vrs',
        'preferred_contact_method',
        'preferred_notification_method',
        'notification_settings',
    ];

    /**
     * The attributes that which should be cast to other types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime:Y-m-d',
        'name' => 'array',
        'languages' => 'array',
        'about' => 'array',
        'service_areas' => 'array',
        'accessibility_and_inclusion_links' => 'array',
        'social_links' => 'array',
        'contact_person_phone' => E164PhoneNumberCast::class.':CA',
        'contact_person_vrs' => 'boolean',
        'notification_settings' => SchemalessAttributes::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string|array<string>
     */
    protected mixed $cascadeDeletes = [
        'users',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public array $translatable = [
        'name',
        'slug',
        'about',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::createWithLocales(['en', 'fr'])
            ->generateSlugsFrom(function (RegulatedOrganization $model, $locale): string {
                return $model->getTranslation('name', $locale);
            })
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getRoutePrefix(): string
    {
        return 'regulated-organizations';
    }

    public function getRoutePlaceholder(): string
    {
        return 'regulatedOrganization';
    }

    public function invitations(): MorphMany
    {
        return $this->morphMany(Invitation::class, 'invitationable');
    }

    protected function serviceRegions(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => get_regions_from_provinces_and_territories(json_decode($attributes['service_areas']) ?? []),
        );
    }

    /**
     * Get the individual's social links.
     *
     * @return array
     */
    public function getSocialLinksAttribute(): array
    {
        if (isset($this->attributes['social_links']) && ! is_null($this->attributes['social_links'])) {
            return array_filter(json_decode($this->attributes['social_links'], true));
        }

        return [];
    }

    /**
     * Get the individual's accessibility and inclusion links.
     *
     * @return array
     */
    public function getAccessibilityAndInclusionLinksAttribute(): array
    {
        if (isset($this->attributes['accessibility_and_inclusion_links']) && ! is_null($this->attributes['accessibility_and_inclusion_links'])) {
            return array_filter(json_decode($this->attributes['accessibility_and_inclusion_links'], true));
        }

        return [];
    }

    /**
     * The sectors that belong to the federally regulated organization.
     *
     * @return BelongsToMany
     */
    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class);
    }

    /**
     * Get the projects that belong to this federally regulated organization.
     *
     * @return MorphMany
     */
    public function projects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->orderBy('start_date');
    }

    public function draftProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereNull('published_at')
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this regulated organization that are in progress.
     *
     * @return MorphMany
     */
    public function inProgressProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('start_date', '<=', Carbon::now())
            ->where(function ($query) {
                $query->whereDate('end_date', '>=', Carbon::now())
                    ->orWhereNull('end_date');
            })
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this regulated organization that have been completed.
     *
     * @return MorphMany
     */
    public function completedProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('end_date', '<', Carbon::now())
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this regulated organization that haven't started yet.
     *
     * @return MorphMany
     */
    public function upcomingProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('start_date', '>', Carbon::now())
            ->orderBy('start_date');
    }

    /**
     * Has the user added any details to the regulated organization?
     *
     * @return bool
     */
    public function hasAddedDetails(): bool
    {
        return ! is_null($this->region);
    }

    public function blocks(): MorphToMany
    {
        return $this->morphToMany(User::class, 'blockable');
    }

    public function blockedBy(?User $user): bool
    {
        if (is_null($user)) {
            return false;
        }

        return $this->blocks()->where('user_id', $user->id)->exists();
    }

    public function notificationRecipients(): MorphToMany
    {
        return $this->morphToMany(User::class, 'notificationable');
    }

    public function isNotifying(?User $user): bool
    {
        if (is_null($user)) {
            return false;
        }

        return $this->notificationRecipients()->where('user_id', $user->id)->exists();
    }
}
