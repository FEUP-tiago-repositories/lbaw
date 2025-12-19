<?php

namespace App\Providers;

use App\Models\Response;
use App\Models\Space;
use App\Policies\ResponsePolicy;
use App\Policies\ReviewPolicy;
use App\Policies\SpacePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Space::class => SpacePolicy::class, // Register the SpacePolicy
        Review::class => ReviewPolicy::class,
        Response::class => ResponsePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
