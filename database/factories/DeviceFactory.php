<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Device::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique->uuid,
            'device_name' => $this->faker->name,
            'device_token' => $this->faker->text(64),
            'device_os' => 'IOS',
            'os_version' => 15.1,
            'app_version' => 1.1
        ];
    }
}