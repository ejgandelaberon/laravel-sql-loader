<?php

namespace Yajra\SQLLoader\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->migrateDatabase();

        $this->seedDatabase();
    }

    protected function migrateDatabase(): void
    {
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->timestamps();
        });
    }

    protected function seedDatabase(): void
    {
        collect(range(1, 20))->each(function ($i) {
            DB::table('users')->insert([
                'name' => 'Record-'.$i,
                'email' => 'Email-'.$i.'@example.com',
            ]);
        });
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.debug', true);
        $app['config']->set('database.default', 'oracle');
        $app['config']->set('database.connections.oracle', [
            'driver' => 'oracle',
            'host' => 'localhost',
            'database' => 'xe',
            'service_name' => 'xe',
            'username' => 'system',
            'password' => 'oracle',
            'port' => 1521,
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
        ];
    }
}
