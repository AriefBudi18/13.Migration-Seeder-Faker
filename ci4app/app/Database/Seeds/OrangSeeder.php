<?php

namespace App\Database\Seeds;

use CodeIgniter\I18n\Time;

class OrangSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        // $data = [
        //     [
        //         'name'          => 'Arief Kusumah',
        //         'address'       => 'ariefKusumah.com',
        //         'created_at'    => Time::now(),
        //         'updated_at'    => Time::now()
        //     ],
        //     [
        //         'name'          => 'Aimas Boediono',
        //         'address'       => 'aimasBoediono.com',
        //         'created_at'    => Time::now(),
        //         'updated_at'    => Time::now()
        //     ],
        //     [
        //         'name'          => 'Ajael Pelimbang',
        //         'address'       => 'ajaelPelimbang.com',
        //         'created_at'    => Time::now(),
        //         'updated_at'    => Time::now()
        //     ],
        //     [
        //         'name'          => 'Edan Hajar',
        //         'address'       => 'edanHajar.com',
        //         'created_at'    => Time::now(),
        //         'updated_at'    => Time::now()
        //     ],
        //     [
        //         'name'          => 'Halam Subardjo',
        //         'address'       => 'halamSubardjo.com',
        //         'created_at'    => Time::now(),
        //         'updated_at'    => Time::now()
        //     ]

        // ];



        $faker = \Faker\Factory::create('id_ID');
        // jika mau looping
        for ($i = 0; $i < 100; $i++) {

            $data = [
                'nama'          => $faker->name,
                'address'       => $faker->address,
                'created_at'    => Time::createFromTimestamp($faker->unixTime()),
                'updated_at'    => Time::now()
            ];
            $this->db->table('orang')->insert($data);
        }








        // Simple Queries
        // $this->db->query(
        //     // insert mencocokan field sesuai yg ada d tabel orang
        //     // values mencocokan nilai/field dengan yg ada pda variabel $data
        //     "INSERT INTO orang (name, address, created_at, updated_at) 
        //     VALUES(:name:, :address:, :created_at:, :updated_at:)",
        //     $data
        // );

        // Using Query Builder

        // $this->db->table('orang')->insertBatch($data);
    }
}
