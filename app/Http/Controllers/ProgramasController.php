<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Roleuserh;
use App\Usertipoact;
use App\Useruniop;
use App\Unidadop;
use App\Tipoact;
use App\Programas;
use Session;
use App\Colores;
use App\Dashboard;

class ProgramasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
     public function index()
    {
        $userid       = auth()->user()->id;
        $uidempresa   = auth()->user()->uidempresa;
        $useruniopsId = auth()->user()->selectuniop;
        $rolesActivo  = Roleuserh::join("roles","roles.id","=","role_user.role_id")
                                 ->where("user_id","LIKE",$userid)
                                 ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                                 ->get();

        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }

        $usertipoacts = Usertipoact::where("user_id","LIKE",$userid)->get();
        $useruniops   = Useruniop::where("user_id","LIKE",$userid)->get();

        $uniops = Unidadop::where("empresauid","LIKE",$uidempresa)
                          ->where("unidadopstatus","LIKE","A")->get();
                          foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }

        $tipoacts = Tipoact::where("status","LIKE","A")
                           ->where("empresauid","LIKE",$uidempresa)
                           ->orderBy("titulo","asc")
                           ->get();

        $programas=Programas::where('empresauid','=',$uidempresa)
                           ->where('uniopuid','=',$useruniopsId)
                           ->where('status','=','A')
                           ->get();

        $programastbl = Programas::where("empresauid","LIKE",$uidempresa)
                              ->where("uniopuid","LIKE",$useruniopsId)
                              ->paginate(9);

        $id="";
        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('programas.index',compact('programastbl','id','notificVencidas','notificDay','uniops','direccion','valor','usertipoacts','useruniops','useruniopsId','tipoacts','programas','menuDashboard'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userid       = auth()->user()->id;
        $uidempresa   = auth()->user()->uidempresa;
        $useruniopsId = auth()->user()->selectuniop;

        $rolesActivo = Roleuserh::join("roles","roles.id","=","role_user.role_id")
                                ->where("user_id","LIKE",$userid)
                                ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                                ->get();

        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug;  }

        $usertipoacts = Usertipoact::where("user_id","LIKE",$userid)->get();
        $useruniops   = Useruniop::where("user_id","LIKE",$userid)->get();

        $uniops =Unidadop::where("deleted","=",0)
                         ->where("empresauid","LIKE",$uidempresa)->get();
                         foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }

        $tipoacts = Tipoact::where("status","LIKE","A")
                           ->where("empresauid","LIKE",$uidempresa)
                           ->orderBy("titulo","asc")
                           ->get();
        $id="";

        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $colores=Colores::all();
        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('programas.create',compact('id','notificVencidas','notificDay','uniops','direccion','valor','usertipoacts','useruniops','useruniopsId','tipoacts','programas','colores','menuDashboard'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nomb'=>'required|max:250'
        ]);
        $programa= new Programas();
        $programa->empresauid   = auth()->user()->uidempresa;
        $programa->uniopuid     = auth()->user()->selectuniop;
        $programa->prognombre   = $request->nomb;
        $programa->progdescrip  = $request->descip;
        $programa->progcolor    = $request->color;
        $programa->progicon    = $request->icono;
        $programa->status       = 'A';
        if( $programa->save() ){
            Session::flash('save','La Unidad fue CREADO Exitosamente');
            return redirect()->route('programas.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userid       = auth()->user()->id;
        $uidempresa   = auth()->user()->uidempresa;
        $useruniopsId = auth()->user()->selectuniop;

        $rolesActivo = Roleuserh::join("roles","roles.id","=","role_user.role_id")
                                ->where("user_id","LIKE",$userid)
                                ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                                ->get();

        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }

        $usertipoacts = Usertipoact::where("user_id","LIKE",$userid)->get();
        $useruniops   = Useruniop::where("user_id","LIKE",$userid)->get();

        $uniops = Unidadop::where("empresauid","LIKE",$uidempresa)
                          ->where("unidadopstatus","LIKE","A")->get();
                          foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){ $direccion= $uniop->unidadopnombre; } }

        $tipoacts = Tipoact::where("status","LIKE","A")
                           ->where("empresauid","LIKE",$uidempresa)
                           ->orderBy("titulo","asc")
                           ->get();
        $programastbl = Programas::find($id);
        $programas=Programas::where('empresauid','=',$uidempresa)
                           ->where('uniopuid','=',$useruniopsId)
                           ->where('status','=','A')
                           ->get();


        $colores=Colores::all();

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('programas.edit',compact('id','notificVencidas','notificDay','programastbl','uniops','direccion','valor','usertipoacts','useruniops','useruniopsId','tipoacts','programas','colores','menuDashboard'));
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
            'nomb'=>'required|max:250'
        ]);
        $programa=  Programas::find($id);
        $programa->empresauid   = auth()->user()->uidempresa;
        $programa->uniopuid     = auth()->user()->selectuniop;
        $programa->prognombre   = $request->nomb;
        $programa->progdescrip  = $request->descip;
        $programa->progcolor    = $request->color;
        $programa->progicon    = $request->icono;
        $programa->status       = $request->status;
        if( $programa->save() ){
            Session::flash('update','La Unidad fue CREADO Exitosamente');
        return redirect()->route('programas.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function vprogramas()
    {
        $userid       = auth()->user()->id;
        $uidempresa   = auth()->user()->uidempresa;
        $useruniopsId = auth()->user()->selectuniop;
        $rolesActivo  = Roleuserh::join("roles","roles.id","=","role_user.role_id")
                                 ->where("user_id","LIKE",$userid)
                                 ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                                 ->get();
                                 dd('esto es una mierda');
        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }

        $usertipoacts = Usertipoact::where("user_id","LIKE",$userid)->get();
        $useruniops   = Useruniop::where("user_id","LIKE",$userid)->get();

        $uniops = Unidadop::where("empresauid","LIKE",$uidempresa)
                          ->where("unidadopstatus","LIKE","A")->get();
                          foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }

        $tipoacts = Tipoact::where("status","LIKE","A")
                           ->where("empresauid","LIKE",$uidempresa)
                           ->orderBy("titulo","asc")
                           ->get();

        $programas=Programas::where('empresauid','=',$uidempresa)
                           ->where('uniopuid','=',$useruniopsId)
                           ->where('status','=','A')
                           ->get();

        $programastbl = Programas::where("empresauid","LIKE",$uidempresa)
                              ->where("uniopuid","LIKE",$useruniopsId)
                              ->paginate(9);

        $id="";
        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('programas.vprograma',compact('programastbl','id','notificVencidas','notificDay','uniops','direccion','valor','usertipoacts','useruniops','useruniopsId','tipoacts','programas','menuDashboard'));
    }
}
