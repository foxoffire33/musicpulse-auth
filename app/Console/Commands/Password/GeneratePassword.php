<?php

namespace App\Console\Commands\Password;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class GeneratePassword extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:generate {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate a password for user';

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
        if ($user = User::findOrFail( $this->argument('id'))) {
            $newPassword = Str::random();
            $user->password = $newPassword;
            if ($user->save()) {
                $this->info('Changed password to: ' . $newPassword);
            } else {
                $this->error('Can\'t save user');
            }
        } else {
            $this->error("User not found.");
        }
    }
}