<?php

namespace Database\Seeders;

use App\Models\Catagory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatagorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Catagory::create([
            'name' => "Developer"
        ]);

        Catagory::create([
            'name' => "Tester"
        ]);

        Catagory::create([
            'name' => "Manager"
        ]);
    }
}
