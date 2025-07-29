<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;





class PackageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('packages')->insert([
            [
                'name' => 'Basic Package',
                'description' => 'Basic features for starters.',
                'price' => 10.99,
                'days_count' => 10, // Assuming this is a monthly package
                'hours_count' => 20, // Assuming this is a package with 5 hours of service
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Standard Package',
                'description' => 'Standard features for growing needs.',
                'price' => 19.99,
                'days_count' => 10, // Assuming this is a monthly package
                'hours_count' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Package',
                'description' => 'All features included.',
                'price' => 29.99,
                'days_count' => 10, // Assuming this is a monthly package
                'hours_count' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}