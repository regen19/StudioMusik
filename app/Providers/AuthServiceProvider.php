<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Model::class => Policy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('isAdmin', fn(User $u) => $u->user_role === 'admin');
        Gate::define('isK3l',   fn(User $u) => $u->user_role === 'k3l');
        Gate::define('isUkmbs', fn(User $u) => $u->user_role === 'ukmbs');
        Gate::define('isUser',  fn(User $u) => $u->user_role === 'user');

        Gate::define('approve-peminjaman', fn(User $u) => $u->user_role === 'k3l');
        Gate::define('pengembalian-alat', fn(User $u) => $u->user_role === 'ukmbs');
    }
}