<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Empresa;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $com = Empresa::all();
        DB::table('users')->insert([
            'name' => 'Fernando Romero Rodríguez',
            'email' => 'fernando.romero20@gmail.com',
            'email_verified_at' => now(),
            'empresa_id' => $com->last()->id,
            'password' => Hash::make('romero2010'),
            'state' => true,
            'rol' => 'Administrador',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
