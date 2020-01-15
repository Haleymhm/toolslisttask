<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Unidadop;
use App\Empresa;
use App\User;
use App\Useruniop;
use App\Roleuserh;
use App\Tipoact;
use App\Usertipoact;
use App\Programas;
use App\Dashboard;


use Ramsey\Uuid\Uuid;
use Session;
use DB;


class UnidadopController extends Controller
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

        $unidadesops = Unidadop::where("empresauid","LIKE",$uidempresa)
                               ->get();

        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();
        $id="";

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('uniop.index',compact('id','notificVencidas','notificDay','uniops','direccion','valor','usertipoacts','useruniops','unidadesops','useruniopsId','tipoacts','programas','menuDashboard'));
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

        $programas=Programas::where('empresauid','=',$uidempresa)
                           ->where('uniopuid','=',$useruniopsId)
                           ->where('status','=','A')
                           ->get();
        $id="";

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('uniop.create',compact('id','notificVencidas','notificDay','uniops','direccion','valor','usertipoacts','useruniops','useruniopsId','tipoacts','programas','menuDashboard'));
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
            'unidadopnombre'=>'required|max:250'
        ]);
        $uniUid= Uuid::uuid4();
        $unidadop= new Unidadop;
        $unidadop->unidadopuid = $uniUid;
        $unidadop->unidadopnombre = $request->unidadopnombre;
        $unidadop->unidadopstatus = "A";
        $unidadop->deleted        = 0;
        $unidadop->empresauid     = $request->empresauid;
        $unidadop->save();

        if( $unidadop->save() ){
            Session::flash('save','La Unidad fue CREADO Exitosamente');
        return redirect()->route('uniop.index');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Unidadop  $unidadop
     * @return \Illuminate\Http\Response
     */
    public function show(Unidadop $unidadop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Unidadop  $unidadop
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

        $unidadops = Unidadop::find($id);
        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('uniop.edit',compact('id','notificVencidas','notificDay','unidadops','uniops','direccion','valor','usertipoacts','useruniops','useruniopsId','tipoacts','programas','menuDashboard'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Unidadop  $unidadop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $unidadop = Unidadop::find($id);
        $unidadop->unidadopnombre = $request->unidadopnombre;
        $unidadop->unidadopstatus = $request->unidadopstatus;
        $unidadop->deleted        = 0;
        $unidadop->unidadopuid    = $request->empresauid;

        $unidadop->save();

        if( $unidadop->save() ){
            Session::flash('update','La Unidad fue EDITADO Exitosamente');
        return redirect()->route('uniop.index');
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Unidadop  $unidadop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unidadop $unidadop, $id)
    {

        $unidadop = Unidadop::find($id);
        $unidadop->deleted = 1;
        $unidadop->save();

        if( $unidadop->save() ){
            Session::flash('update','La Unidad fue ELIMINADO Exitosamente');
        return redirect()->route('uniop.index');
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }
    }
}
