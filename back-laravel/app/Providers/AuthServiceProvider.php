<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use app\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', function (User $user): bool {
            Log::info('User role: ', ['role' => $user->role]);

            $isAdmin = $user->role instanceof Role
                ? $user->role === Role::ADMIN
                : $user->role === Role::ADMIN->value;

            Log::info('Is admin: ', ['is_admin' => $isAdmin]);

            return $isAdmin;
        });
    }
}
