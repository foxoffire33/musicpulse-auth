<?php

namespace App\Console;

use App\Console\Commands\Devices\DeleteAllDevices;
use App\Console\Commands\Devices\ListAllDevices;
use App\Console\Commands\Password\ChangePassword;
use App\Console\Commands\Password\GeneratePassword;
use App\Console\Commands\Role\PromoteUserToAdmin;
use App\Console\Commands\Role\PromoteUserToDevice;
use App\Console\Commands\User\CreateUser;
use App\Console\Commands\User\DeleteUser;
use App\Console\Commands\User\ListAllUsers;
use App\Console\Commands\GenerateAppKey;
use App\Console\Commands\JWTTokenGenerateKeyPair;

use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        JWTTokenGenerateKeyPair::class,
        GenerateAppKey::class,
        //user management
        CreateUser::class,
        DeleteUser::class,
        ListAllUsers::class,
        //role management
        PromoteUserToAdmin::class,
        PromoteUserToDevice::class,
        //password management
        ChangePassword::class,
        GeneratePassword::class,
        //devices
        ListAllDevices::class,
        DeleteAllDevices::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
