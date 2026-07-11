<?php

namespace Database\Seeders;

use App\Models\{Grado, Seccion, User};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Grado::firstOrCreate(['nombre'=>'1er año']); Seccion::firstOrCreate(['nombre'=>'A']);
        User::firstOrCreate(['email'=>'admin@sae.test'],['name'=>'Administrador SAE','password'=>'Cambiar123!','role'=>'admin','estado'=>true]);
    }
}
