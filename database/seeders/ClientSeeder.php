<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;


class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $obj = new Client();
        $obj->name = "Dioz ";
        $obj->email = "dioz@food.com";
        $obj->password = Hash::make('12345678');
        $obj->save();
    }
}
