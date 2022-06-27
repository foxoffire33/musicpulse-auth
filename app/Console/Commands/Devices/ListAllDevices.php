<?php

namespace App\Console\Commands\Devices;

use App\Models\Device;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ListAllDevices extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devices:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all devices';

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
        $models = Device::all();
        if ($models->isNotEmpty()) {
            $headers = ['id', 'device_name', 'device_token', 'device_os', 'os_version', 'app_version'];
            $this->table($headers, $models);
        } else {
            $this->info("Devices table is empty");
        }
    }
}