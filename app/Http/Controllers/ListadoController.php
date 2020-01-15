<?php

namespace App\Http\Controllers;

use App\Tipoact;
use App\Unidadop;
use App\Empresa;
use App\User;
use App\Useruniop;
use App\Roleuserh;
use App\Usertipoact;
use App\Grupotipoact;
use App\Listado;
use App\Elemento;
use App\Programas;
use App\Dashboard;


use Session;
use DB;

use Illuminate\Http\Request;

class ListadoController extends Controller
{

     public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userid=auth()->user()->id;
        $uidempresa=auth()->user()->uidempresa;
        $useruniopsId=auth()->user()->selectuniop;
        $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
        ->where("user_id","LIKE",$userid)
        ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
        ->get();

        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug;   }

        $usertipoacts =Usertipoact::where("user_id","LIKE",$userid)->get();
        $useruniops = Useruniop::where("user_id","LIKE",$userid)->get();
        $tipoacts = Tipoact::where("status","LIKE","A")
                                  ->where("empresauid","LIKE",$uidempresa)
                                  ->orderBy('titulo','asc')
                                  ->get();


        $uniops =Unidadop::where("deleted","=",0)
                         ->where("empresauid","LIKE",$uidempresa)
                         ->where("unidadopstatus","LIKE","A")->get();
                         foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }

        $grupotipoactividads = Grupotipoact::where("empresauid","LIKE",$uidempresa)
                                           ->where("status","LIKE","A")
                                           ->orderBy("parent")
                                           ->orderBy("orden")
                                           ->get();

        $listados = Listado::where("empresauid","=",$uidempresa)
                           ->where("status","=","A")
                           ->orderBy("nombrelista")
                           ->get();
        $id="";
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();



        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('listado.index',compact('id','notificVencidas','notificDay','listados','uniops','direccion','valor','usertipoacts','useruniops','tipoacts','useruniopsId','grupotipoactividads','programas','menuDashboard'));

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'listnombre'    =>'required|min:3|max:100',

        ]);
        //$tipoactUid= Uuid::uuid4();
        $listado = new Listado;
        $listado->empresauid   = $request->empresauid;
        $listado->nombrelista  = $request->listnombre;
        $listado->descplista   = $request->listdescrip;
        $listado->ver          = 'lis';
        $listado->status          = 'A';

        $listado->save();
        $idlist=$listado->id;
        if( $listado->save() ){
            Session::flash('save','La Registro fue CREADO Exitosamente');
        return redirect('listado/'.$idlist.'/edit');
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userid=auth()->user()->id;
        $uidempresa=auth()->user()->uidempresa;
        $useruniopsId=auth()->user()->selectuniop;
        $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                              ->where("user_id","LIKE",$userid)
                              ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                              ->get();

        foreach ($rolesActivo as $rolActivo) {
        $valor= $rolActivo->slug;
        }

        $usertipoacts =Usertipoact::where("user_id","LIKE",$userid)->get();
        $useruniops = Useruniop::where("user_id","LIKE",$userid)->get();
        $tipoacts = Tipoact::where("status","LIKE","A")
                                  ->where("empresauid","LIKE",$uidempresa)
                                  ->orderBy('titulo','asc')
                                  ->get();

        $uniops =Unidadop::where("deleted","=",0)
                         ->where("empresauid","LIKE",$uidempresa)
                         ->where("unidadopstatus","LIKE","A")->get();
                         foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }

        $grupotipoactividads = Grupotipoact::where("empresauid","LIKE",$uidempresa)
                                    ->where("status","LIKE","A")
                                    ->orderBy("parent")
                                    ->orderBy("orden")
                                    ->get();

        $elementos=Elemento::where("empresauid","LIKE",$uidempresa)
                           ->where("listadouid","LIKE",$id)
                           ->orderBy("elempos","asc")
                           ->get();
        $pos=$elementos->count()+1;
        $programas=Programas::where('empresauid','=',$uidempresa)
                           ->where('uniopuid','=',$useruniopsId)
                           ->where('status','=','A')
                           ->get();

        $listados = Listado::find($id);
        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('listado.edit',compact('id','notificVencidas','notificDay','uniops','direccion','valor','usertipoacts','useruniops','tipoacts','useruniopsId','grupotipoactividads','listados','elementos','pos','programas','menuDashboard')); /*en compact va la variable de las vista*/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'listnombre'  =>'required|min:3|max:100',
            'inictialView'  =>'required',

        ]);
        //$tipoactUid= Uuid::uuid4();
        //dd($request->inictialView);
        $listado = Listado::find($id);
        $listado->empresauid   = $request->empresauid;
        $listado->nombrelista  = $request->listnombre;
        $listado->descplista   = $request->listdescrip;
        $listado->ver          = $request->inictialView;
        $listado->status          = "A";

        $listado->save();

        if( $listado->save() ){
            Session::flash('save','La Registro fue CREADO Exitosamente');
        return redirect('listado/');
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }
    }


    public function addcontenido(Request $request)
    {
        //dd("que esta pasando");
        $request->validate([
            'elemtnombre'   => 'required'
        ]);


        $elemento= new Elemento;
        $elemento->empresauid       = $request->empresauid;
        $elemento->listadouid       = $request->listadouid;
        $elemento->elemnombre       = $request->elemtnombre;
        $elemento->elemdescip       = $request->elemtdescrip;
        $elemento->elempos          = $request->elempos;
        $elemento->status           = "A";
        $elemento->save();

        $newElemPos=$request->elempos;
        $elementos=Elemento::where('listadouid','=',$request->listadouid)
                           ->where('id','<>',$elemento->id)
                           ->orderBy('elempos','asc')
                           ->get();

        foreach ($elementos as $element) {
            $idElemtActual=$element->id;
            $posElemtActual=$element->elempos;
            if($posElemtActual == $newElemPos){
                $newElemPos = $newElemPos + 1;
                $elemen = Elemento::find($idElemtActual);
                $elemen->elempos=$newElemPos;
                $elemen->save();
            }
        }

        return redirect('listado/'.$request->listadouid.'/edit');

    }

    public function editcontenido(Request $request, $id)
    {
        $request->validate([
            'elemtnombre'   => 'required',
            'status'        => 'required'
        ]);

        $uidempresa=auth()->user()->uidempresa;
        $elemento = Elemento::find($id);
        $elemento->empresauid       = $uidempresa;
        $elemento->listadouid       = $request->listadouid;
        $elemento->elemnombre       = $request->elemtnombre;
        $elemento->elemdescip       = $request->elemtdescrip;
        $elemento->elempos          = $request->elempos;
        $elemento->status           = $request->status;
        $elemento->save();

        $newElemPos=$request->elempos;
        $elementos=Elemento::where('listadouid','=',$request->listadouid)
                           ->where('id','<>',$elemento->id)
                           ->orderBy('elempos','asc')
                           ->get();
        $npos = 0;
        foreach ($elementos as $element) {
            $npos = $npos + 1;
            if ($npos == $newElemPos) { $npos = $npos + 1; }
            $idElemtActual=$element->id;
            $elemen = Elemento::find($idElemtActual);
            $elemen->elempos=$npos;
            $elemen->save();
        }


        return redirect('listado/'.$request->listadouid.'/edit');


    }
}
