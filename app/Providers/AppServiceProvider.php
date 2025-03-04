<?php

namespace App\Providers;

use App\Models\Individual;
use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Observers\UserObserver;
use App\Settings;
use App\Statuses\IndividualStatus;
use App\Statuses\OrganizationStatus;
use App\Statuses\ProjectStatus;
use App\Statuses\RegulatedOrganizationStatus;
use Composer\InstalledVersions;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Makeable\EloquentStatus\StatusManager;
use Spatie\LaravelIgnition\Facades\Flare;
use Spatie\Translatable\Facades\Translatable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Settings::class, function () {
            return Settings::make(storage_path('app/settings.json'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (config('app.env') !== 'local') {
            $url->forceScheme('https');
        }

        if (config('app.env') === 'local') {
            DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        }

        Flare::determineVersionUsing(function () {
            return InstalledVersions::getRootPackage()['pretty_version'];
        });

        StatusManager::bind(Individual::class, IndividualStatus::class);
        StatusManager::bind(Organization::class, OrganizationStatus::class);
        StatusManager::bind(RegulatedOrganization::class, RegulatedOrganizationStatus::class);
        StatusManager::bind(Project::class, ProjectStatus::class);
        Translatable::fallback(fallbackLocale: 'en', fallbackAny: true);
        User::observe(UserObserver::class);
    }
}
