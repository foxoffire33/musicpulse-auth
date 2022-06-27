<?php

namespace App\Console\Commands\Password;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class ChangePassword extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:change {id} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change password for user';

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

        if (!$this->hasArgument('password')){
            $password = $this->ask("Type your new password:");
        }



        if ($user = User::findOrFail( $this->argument('id'))) {
            $password = $this->argument('password') ?? $this->ask("Type your new password:");
            if($password < 8)
                abort(422,"Password not secure");

            $user->password = $password;
            if ($user->save()) {
                $this->info('Changed password');
            } else {
                $this->error('Can\'t save user');
            }
        } else {
            $this->error("User not found.");
        }
    }
}