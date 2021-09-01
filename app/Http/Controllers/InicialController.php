<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Library\Breadcrumb\Breadcrumb;

class InicialController extends Controller
{

    public function __construct() {
        $this->bc = new Breadcrumb('Bem Vindo');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inicial()
    {
        return view('inicial.inicial', ['bc'=>$this->bc]);
    }

}
