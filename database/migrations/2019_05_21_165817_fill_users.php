<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FillUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = [
            'name' => 'biba',
            'first_name' => '',
            'last_name' => '',
            'email' => 'biba@learn.world',
            'password' => '$2a$10$quJmTLrvOW9RnEPKsABXMO0ATVHxgoxUsnnJv/tuf3Y97k6oi7Yne'
        ];

        DB::table('users')->insert($users);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('users')->delete();
    }
}
