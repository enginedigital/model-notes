<?php

namespace EngineDigital\Note;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
// use EngineDigital\Note\Commands\NoteCommand;

class NoteServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('model-notes')
            ->hasConfigFile()
            ->hasMigration('create_model_notes_table');
            // ->hasCommand(NoteCommand::class);
    }

    // public function packageRegistered()
    // {
    //     $resolver = config('model-notes.tenant_resolver');

    //     $this->app->bind('EngineDigital\Note\NoteTenantResolver', function () use ($resolver) {
    //         return app($resolver);
    //     });
    // }
}
