<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ExampleController extends Controller
{

    public function testMethod($id){
        return DB::table('users')->where('id','=',$id)->get();
    }
}
