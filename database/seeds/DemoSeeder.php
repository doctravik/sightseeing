<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete places of the demo account.
        User::find(1)->places()->delete();

        DB::table('places')->insert(
            json_decode(File::get("database/seeds/data/places.json"), true)
        );
    }
}
