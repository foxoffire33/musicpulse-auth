<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteUser extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an user';

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
        if ($user = User::findOrFail($this->argument('id'))) {
            if ($user->delete()) {
                $this->info('User is deleted');
            } else {
                $this->error('Can\'t delete user');
            }
        } else {
            $this->error("User with ID: " . $this->argument('id') . "  not found.");
        }
    }
}