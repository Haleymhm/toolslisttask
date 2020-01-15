<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actividad;
use App\Unidadop;
use App\Tipoact;
use App\Roleuserh;
use App\Useruniop;
use App\Usertipoact;
use App\User;
use Session;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->user()->selectuniop==""){ return redirect()->route('logout'); }
        $inicialView=auth()->user()->vista;

        if($inicialView==0){
            return redirect()->route('calendario.index');
        }elseif($inicialView==1){
            return redirect()->route('graficos.index');
        }elseif($inicialView==2){
            dd("Redirect a Vista Docuemtos");
        }
    }
}
