<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('vehicles')->insert([
            [
                'no_polisi' => 'D9091ABC',
                'driver' => 'Driver 1'
            ],
            [
                'no_polisi' => 'D9092BCD',
                'driver' => 'Driver 2'
            ],
            [
                'no_polisi' => 'D9093CDE',
                'driver' => 'Driver 3'
            ],
            [
                'no_polisi' => 'D9094DEF',
                'driver' => 'Driver 4'
            ],
            [
                'no_polisi' => 'D9095EFG',
                'driver' => 'Driver 5'
            ],
            [
                'no_polisi' => 'D9096FGH',
                'driver' => 'Driver 6'
            ]
        ]);

        DB::table('stores')->insert([
            [
                'name' => 'Alfamart',
                'location' => 'Bandung',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Indomaret',
                'location' => 'Jakarta',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Yomart',
                'location' => 'Bandung',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);

        DB::table('products')->insert([
            [
                'code' => '5300267',
                'name' => 'Ultra Milk Fat Choco 1000 Ml/12',
                'price' => '6700'
            ],
            [
                'code' => '5300453',
                'name' => 'Ultra Milk Plain 1000 Ml/12 <ND>',
                'price' => '5300'
            ],
            [
                'code' => '5300454',
                'name' => 'Ultra Milk Chocolate 1000 Ml/12 <ND>',
                'price' => '5400'
            ],
            [
                'code' => '5300455',
                'name' => 'Ultra Milk Low Fat HiCal 1000 Ml/12 <ND>',
                'price' => '5500'
            ],
            [
                'code' => '5300457',
                'name' => 'Ultra Milk Chocolate 250 Ml/24 <ND>',
                'price' => '500'
            ],
            [
                'code' => '5300458',
                'name' => 'Ultra Milk Strawberry 250 Ml/24 <ND>',
                'price' => '500'
            ],
            [
                'code' => '5300459',
                'name' => 'Ultra Milk Mocca 250 Ml/24 <ND>',
                'price' => '500'
            ],
            [
                'code' => '5300460',
                'name' => 'Ultra Milk Low Fat HiCal 250 Ml/24 <ND>',
                'price' => '500'
            ],
            [
                'code' => '5300462',
                'name' => 'Ultra Milk Chocolate 200 Ml/24 <ND>',
                'price' => '500'
            ],
            [
                'code' => '5300463',
                'name' => 'Ultra Milk Strawberry 200 Ml/24 <ND>',
                'price' => '500'
            ],
            [
                'code' => '5300464',
                'name' => 'Ultra Milk Mocca 200 Ml/24 <ND>',
                'price' => '500'
            ],
            [
                'code' => '5300465',
                'name' => 'Ultra Milk Chocolate 125 Ml/40 <ND>',
                'price' => '500'
            ],
            [
                'code' => '5300466',
                'name' => 'Ultra Milk Strawberry 125 Ml/40 <ND>',
                'price' => '500'
            ],
            [
                'code' => '5300572',
                'name' => 'Teh Kotak Jasmine ND2 300 Ml/24',
                'price' => '720'
            ],
            [
                'code' => '5300602',
                'name' => 'Mimi Choco X-tra Cal 125 Ml/40 <ND10>',
                'price' => '600'
            ],
            [
                'code' => '5300603',
                'name' => 'Mimi Strawberry X-tra Cal 125 Ml/40 <ND10>',
                'price' => '600'
            ],
            [
                'code' => '5300821',
                'name' => 'Sari Kacang Ijo 250 Ml/24 CB1 S.Pos',
                'price' => '820'
            ],
            [
                'code' => '5300884',
                'name' => 'Mimi Full Cream 125 Ml/40',
                'price' => '880'
            ],
            [
                'code' => '5300886',
                'name' => 'Teh Kotak Jasmine Less Sugar 300 Ml/24',
                'price' => '880'
            ],
            [
                'code' => '5300905',
                'name' => 'Teh Kotak Rasa Apple New Design 300 Ml/24',
                'price' => '900'
            ],
            [
                'code' => '5300906',
                'name' => 'Teh Kotak Rasa Lemon New Design 300 Ml/24',
                'price' => '900'
            ],
        ]);
    }
}
