<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    /*
    ////////////////////////////////////////
    Retorno de vista a panel administrativo 
    ///////////////////////////////////////
    */
    public function mostrar()
    {
       return view('admin.admin');
    }
}
