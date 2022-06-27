<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            ['id' => Str::uuid(), 'email' => 'admin@dxmusic.nl', 'password' => Hash::make('BRkuT2awqENoNMpo'), 'role' => \App\Models\User::USER_ROLE_ADMIN],
            ['id' => Str::uuid(), 'email' => 'partner@dxmusic.nl', 'password' => Hash::make('OnGuEegw36dmUfp1'), 'role' => \App\Models\User::USER_ROLE_PARTNER],
            ['id' => Str::uuid(), 'email' => 'device@dxmusic.nl', 'password' => Hash::make('i5ETv8ifePTq4mr2'), 'role' => \App\Models\User::USER_ROLE_DEVICE]
        ]);

       //User::factory()->hasDevices(rand(1,5))->count(10)->create();


    }

}
