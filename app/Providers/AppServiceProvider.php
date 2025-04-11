<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton('firebase.storage', function ($app) {
            $factory = (new \Kreait\Firebase\Factory)
                ->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')))
                ->withDefaultStorageBucket(str_replace('gs://', '', env('FIREBASE_STORAGE_BUCKET')));

            return $factory->createStorage();
        });

        $this->app->singleton('firebase.firestore', function ($app) {
            $factory = (new \Kreait\Firebase\Factory)
                ->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')));

            return $factory->createFirestore();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
