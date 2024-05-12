<?php

namespace Database\Seeders;

use App\Models\Grupo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            ['nombre' => 'Grupo 1'],
            ['nombre' => 'Grupo 2'],
            ['nombre' => 'Grupo 3'],
            ['nombre' => 'Grupo 4'],
            ['nombre' => 'Grupo 5'],
            // Agrega más grupos según sea necesario
        ];

        // Iterar sobre los datos y crear los grupos en la base de datos
        foreach ($groups as $groupData) {
            Grupo::create($groupData);
        }
    }
}
