<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;

class ListAllUsers extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all users';

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
        $models = User::all();
        if ($models->isNotEmpty()){
            $headers = ["ID","E-mail","Role","Created at","Updated at"];
            $this->table($headers,$models);
        }else{
            $this->info("Users table is empty");
        }
    }
}