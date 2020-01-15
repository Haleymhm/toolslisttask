<?php

namespace App\Http\Controllers;

use App\Actividad;
use App\Tipoact;
use App\Unidadop;
use App\Useruniop;
use App\Usertipoact;
use App\Roleuserh;
use App\Grupotipoact;
use App\Useracividad;
use App\Programas;
use App\Dashboard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class GraficosController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
      if (isset($request->dateinicioI)) {
        //dd("REQUEST CON DATOS");
        $dateinicio    = new Carbon($request->dateinicioI);
        $df=$request->datefinI." 23:59";
        $datefin       = new Carbon($df);
        $tipoActividad = $request->tipoactividaduid;
        $desde         = $dateinicio->format('Y-m-d')." 00:00";
        $hasta         = $datefin->format('Y-m-d')." 23:59";

        $datebegin     = $dateinicio->format('d-m-Y');
        $dateend       = $datefin->format('d-m-Y');
      }else{

        $dates= Carbon::now();
        $datebegin = "01-01-".$dates->format('Y');
        $dateend   = $dates->format('d-m-Y');

        $desde     = $dates->format('Y')."-01-01 00:00";
        $hasta     = $dates->format('Y-m-d')." 23:59";
      }

        $uidempresa=auth()->user()->uidempresa;
        $userid=auth()->user()->id;
        $useruniopsId=auth()->user()->selectuniop;
        //uniopSelected
        /**** Todas las unidades operativas de una empresa ****/

        $msg='<b>Intup Desde: </b>'.$datebegin.' <b>Input Hasta: </b>'.$dateend;
        $msg=$msg .'<br /><b>SQL Desde: </b>'.$desde.' <b>SQL Hasta: </b>'.$hasta;
        //return $msg;
        $uniops =Unidadop::where("unidadopstatus","LIKE","A")
                         ->where("empresauid","LIKE",$uidempresa)->get();
                         foreach ($uniops as $uniop) {
                          if($uniop->unidadopuid==$useruniopsId){
                              $direccion= $uniop->unidadopnombre;
                          }
                      }
        /**** Todas los Tipo de Actividades de una empresa ****/
        $tipoacts =Tipoact::where("status","LIKE","A")
                          ->where("empresauid","LIKE",$uidempresa)
                          ->orderBy("titulo","asc")
                          ->get();
        /**** Todas las unidades operativas a las que pertenece un usuario ****/
        $useruniops = Useruniop::where("user_id","LIKE",$userid)->get();

        /**** Todas las unidades operativas a las que pertenece un usuario ****/
        $usertipoacts =Usertipoact::where("user_id","LIKE",$userid)->get();
        //$usertipoactId=$usertipoacts->tipoacts_id;
        $grupotipos=Grupotipoact::where("empresauid","LIKE",$uidempresa)->get();

         $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                             ->where("user_id","LIKE",$userid)
                             ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                             ->get();

        if (isset($tipoActividad)) {
            $tiposActs = Tipoact::where("status","LIKE","A")
                          ->where("empresauid","LIKE",$uidempresa)
                          ->where("uid","LIKE",$tipoActividad)
                          ->where("mind","LIKE","SI")
                          ->orderBy("titulo","asc")
                          ->get();
        } else {
           $tiposActs = Tipoact::where("status","LIKE","A")
                               ->where("empresauid","LIKE",$uidempresa)
                               ->where("mind","LIKE","SI")
                               ->orderBy("titulo","asc")
                               ->get();
        }

        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }
        $i=0;
        foreach ($tiposActs as $tas) {
        	$nombreTA[$i]=$tas->titulo;
        	$totalActivo=DB::table('actividads')
                           ->where('tipoactividaduid','=',$tas->uid)
                           ->where('unidadopuid','=',auth()->user()->selectuniop)
                           ->whereBetween('actividadinicio',[$desde,$hasta])
          						   ->where('actividadstatus','LIKE',"A")
                                     ->get();

        	$valorActivo[$i] = count($totalActivo);
        	$totalCerrado=DB::table('actividads')
                          ->where('tipoactividaduid','=',$tas->uid)
                          ->where('unidadopuid','=',auth()->user()->selectuniop)
                          ->whereBetween('actividadinicio',[$desde,$hasta])
          						    ->where('actividadstatus','LIKE',"C")
          						    ->get();
        	$valorCerrado[$i] = count($totalCerrado);
        	$i=$i+1;
        }


      //dd($valorCerrado);
      $id="";
      /*if (Auth::user()->can('grafico.index')){
        return view('graficos.index', compact('id','uniops','direccion','tipoacts','useruniops','usertipoacts','valor','useruniopsId','grupotipos','nombreTA','valorActivo','valorCerrado','i','datebegin','dateend'));
      }else{
        return view('errors.401', compact('id','uniops','direccion','tipoacts','useruniops','usertipoacts','valor','useruniopsId','grupotipos','nombreTA','valorActivo','valorCerrado','i','datebegin','dateend'));
      }*/
    $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

    $menuDashboard=dashboardMenu();
    $notificVencidas=actividadesVencidas();
    $notificDay=actividadesDay();
    //dd($notificVencidas);
      return view('graficos.index', compact('id','notificVencidas','notificDay','uniops','datebegin','dateend','direccion','tipoacts','useruniops','usertipoacts','valor','useruniopsId','grupotipos','nombreTA','valorActivo','valorCerrado','i','datebegin','dateend','programas','menuDashboard'));
    }
}


