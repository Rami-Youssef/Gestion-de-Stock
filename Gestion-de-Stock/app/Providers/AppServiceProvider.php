<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Pagination\Paginator::useBootstrap();
        
        // Add a helper function to translate role names
        \Illuminate\Support\Facades\Blade::directive('roleDisplay', function ($expression) {
            return "<?php
                \$roleName = '';
                switch (trim($expression, \"' \")) {
                    case 'user':
                        \$roleName = 'Utilisateur';
                        break;
                    case 'admin':
                        \$roleName = 'Administrateur';
                        break;
                    case 'super_admin':
                        \$roleName = 'Super Administrateur';
                        break;
                    default:
                        \$roleName = trim($expression, \"' \");
                        break;
                }
                echo \$roleName;
            ?>";
        });
    }
}
