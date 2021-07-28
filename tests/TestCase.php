<?php

namespace EngineDigital\Note\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelPackageTools\CreatePackageTable;
use EngineDigital\Note\NoteServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'EngineDigital\\Note\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        // $this->artisan('migrate', [
        //     '--database' => 'testbench',
        //     '--realpath' => realpath(__DIR__ . '/../database/migrations'),
        // ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            NoteServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        // $migration = require __DIR__ . '/../database/migrations/create_model_notes_table.php.stub';
        // $migration->up();

        // Setup default database to use sqlite :memory:
        // config()->set('database.default', 'testbench');
        // config()->set('database.connections.testbench', [
        //     'driver'   => 'sqlite',
        //     'database' => ':memory:',
        //     'prefix'   => '',
        // ]);
        // $app['config']->set('database.default', 'testbench');
        // $app['config']->set('database.connections.testbench', [
        //     'driver'   => 'sqlite',
        //     'database' => ':memory:',
        //     'prefix'   => '',
        // ]);
        // $this->artisan('migrate', [
        //     '--database' => 'testing',
        //     '--realpath' => realpath(__DIR__ . '/../database/migrations'),
        // ]);
    }
}
