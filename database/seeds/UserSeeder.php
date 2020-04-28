<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private $rows = [
        [
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => 'adminadmin',
            'role' => 100
        ],
        [
            'id' => 2,
            'name' => 'user',
            'password' => 'useruser',
            'email' => 'user@mail.com',
            'role' => 1
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->rows as &$row){
            $row['password'] = Hash::make($row['password']);
        }
        DB::table('users')->delete();
        foreach ($this->rows as $value){
            \App\Models\User::create($value);
        }
    }
}
