<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Grupotipoact;
use App\Tipoact;
use App\Unidadop;
use App\Empresa;
use App\User;
use App\Useruniop;
use App\Roleuserh;
use App\Usertipoact;
use App\Programas;
use App\Dashboard;
use Ramsey\Uuid\Uuid;
use DB;

use Session;

class GrupotipoactController extends Controller
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

        $uniops =Unidadop::where("empresauid","LIKE",$uidempresa)
                         ->where("unidadopstatus","LIKE","A")->get();
                         foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }


        $grupotipoactividads = Grupotipoact::where("empresauid","LIKE",$uidempresa)
                                           ->orderBy("parent")
                                           ->orderBy("orden")
                                           ->get();
                                   // ->where("status","LIKE","A")->paginate(9);

        /**** Todas los Tipo de Actividades de una empresa ****/
        $tipoacts =Tipoact::where("status","LIKE","A")
                          ->where("empresauid","LIKE",$uidempresa)
                          ->orderBy("titulo","asc")
                          ->get();
        $id="";
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('grupotipoact.index',compact('id','notificVencidas','notificDay','grupotipoactividads','uniops','direccion','valor','usertipoacts','useruniops','useruniopsId','grupotipoactividads','tipoacts','programas','menuDashboard')); /*en compact va la variable de las vista*/
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

        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }

        $usertipoacts = Usertipoact::where("user_id","LIKE",$userid)->get();
        $useruniops   = Useruniop::where("user_id","LIKE",$userid)->get();

        $uniops =Unidadop::where("empresauid","LIKE",$uidempresa)
                         ->where("unidadopstatus","LIKE","A")->get();
                         foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }

        $grupotipoactividads = Grupotipoact::where("empresauid","LIKE",$uidempresa)
                                    ->where("status","LIKE","A")->get();

        /**** Todas los Tipo de Actividades de una empresa ****/
        $tipoacts =Tipoact::where("status","LIKE","A")
                          ->where("empresauid","LIKE",$uidempresa)
                          ->orderBy("titulo","asc")
                          ->get();
        $id="";
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $menuDashboard=Dashboard::where('empresauid','=',auth()->user()->uidempresa)
                                ->where('uniopuid','=',auth()->user()->selectuniop)
                                ->get();

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('grupotipoact.create',compact('id','notificVencidas','notificDay','grupotipoactividads','uniops','direccion','valor','usertipoacts','useruniops','useruniopsId','tipoacts','programas','menuDashboard')); /*en compact va la variable de las vista*/
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
            'nombre'=>'required',
        ]);
        $grupoUid = Uuid::uuid4();
        $grupotipoact = new Grupotipoact;
        $grupotipoact->uid       = $grupoUid;
        $grupotipoact->empresauid     = $request->empresauid;
        $grupotipoact->titulo         = $request->nombre;
        $grupotipoact->descripgroup   = $request->descripcion;
        $grupotipoact->parent         = $request->parent;
        $grupotipoact->orden          = $request->orden;
        $grupotipoact->icono          = 'fa fa-plus';
        $grupotipoact->status         = "A";


        $grupotipoact->save();

        if( $grupotipoact->save() ){
            Session::flash('save','La Registro fue CREADO Exitosamente');
        return redirect()->route('grupotipoact.index');
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
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

        $grupotipoactividads =  Grupotipoact::where("empresauid","LIKE",$uidempresa)
                                            ->where("status","LIKE","A")->get();

        $uniops = Unidadop::where("empresauid","LIKE",$uidempresa)
                          ->where("unidadopstatus","LIKE","A")->get();
                          foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }

        /**** Todas los Tipo de Actividades de una empresa ****/
        $tipoacts =Tipoact::where("status","LIKE","A")
                          ->where("empresauid","LIKE",$uidempresa)->get();

        $grupotipoactividad = Grupotipoact::find($id);
        $id="";
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();


        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('grupotipoact.edit',compact('id','notificVencidas','notificDay','grupotipoactividad','uniops','direccion','valor','usertipoacts','useruniops','useruniopsId','grupotipoactividads','tipoacts','programas','menuDashboard')); /*en compact va la variable de las vista*/
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
        $grupotipoact= Grupotipoact::find($id);
        $grupotipoact->empresauid     = $request->empresauid;
        $grupotipoact->titulo         = $request->nombre;
        $grupotipoact->descripgroup   = $request->descripcion;
        $grupotipoact->parent         = $request->parent;
        $grupotipoact->orden          = $request->orden;
        $grupotipoact->status         = $request->status;
        if(!empty($request->icono)){
            $grupotipoact->icono  = $request->icono;
        }

        //$grupotipoact->save();

        if( $grupotipoact->save() ){
            Session::flash('update','La Registro fue MODIFICADO Exitosamente');
        return redirect()->route('grupotipoact.index');
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }
    }


}
