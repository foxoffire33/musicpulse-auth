<?php

namespace App\Console\Commands\Role;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteUserToDevice extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:device {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change role to device';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ;
        if ($user = User::findOrFail($this->argument('email'))) {
            $user->role = User::USER_ROLE_DEVICE;
            if ($user->save()) {
                $this->info('User is now device user');
            } else {
                $this->error('Can\'t save user');
            }
        }else {
            $this->error("User not found.");
        }
    }
}