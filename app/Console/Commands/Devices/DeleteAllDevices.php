<?php
namespace App\Console\Commands\Devices;

use App\Models\Device;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DeleteAllDevices extends Command
{




    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devices:delete-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all devices';

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
        if ($this->confirm('All devices will be deleted, do you wish to continue?')) {
            foreach (Device::all() as $device) {
                if ($device->delete())
                    $this->info("Device with ID:" . $device->id . "deleted");
            }
        }
    }
}