<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class CreateUser extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {email} {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

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
        $attributes = [
            'email' => $this->argument('email'),
            'password' => Str::random()
        ];
        $user = new User($attributes);
        try {
            if ($user->save()) {
                $this->info('user created, password: ' . $attributes['password']);
            }else{
                $this->error("Creating user failed");
            }
        } catch (QueryException $exception) {
            $this->error($exception->getMessage());
        }
    }
}