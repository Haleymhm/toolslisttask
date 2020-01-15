<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Models\Permission;


use App\Empresa;
use App\User;
use App\Unidadop;
use App\Useruniop;
use App\Roleuserh;
use App\Tipoact;
use App\Usertipoact;
use App\Usertipoactmob;
use App\Programas;
use App\Dashboard;

use App\Auth;
use Session;



class CusuarioController extends Controller
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
        $uidempresa=auth()->user()->uidempresa;
        $userid=auth()->user()->id;
        $useruniopsId=auth()->user()->selectuniop;

        /**** Todas las unidades operativas de una empresa ****/
        $uniops =Unidadop::where("empresauid","LIKE",$uidempresa)
                         ->where("unidadopstatus","LIKE","A")->get();
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
        $usertipoactsmob =Usertipoactmob::where("user_id","LIKE",$userid)->get();

         $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                             ->where("user_id","LIKE",$userid)
                             ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                             ->get();

        foreach ($rolesActivo as $rolActivo) {
            $valor= $rolActivo->slug;
        }
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();


        $id="";
        $users = User::where("uidempresa","=",$uidempresa)->get();
        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('cusuario.index',compact('id','notificVencidas','notificDay','users','direccion','uniops','valor','usertipoacts','useruniops','useruniopsId','tipoacts','programas','menuDashboard')); /*en compact va la variable de las vista*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userid=auth()->user()->id;
        $uidempresa=auth()->user()->uidempresa;
        $useruniopsId=auth()->user()->selectuniop;
        $roles=Role::Orderby("name","asc")->get();

        $uniops = Unidadop::where("unidadopstatus","LIKE","A")
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

        $usertipoacts = Usertipoact::where("user_id","LIKE",$userid)->get();
        $usertipoactsmobs =Usertipoactmob::where("user_id","LIKE",$userid)->get();
        $useruniops   = Useruniop::where("user_id","LIKE",$userid)->get();
        $rolesActivo  = Roleuserh::join("roles","roles.id","=","role_user.role_id")
                                ->where("user_id","LIKE",$userid)
                                ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                                ->get();

        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }
        $programas=Programas::where('empresauid','LIKE',$uidempresa)
                            ->where('uniopuid','LIKE',$useruniopsId)
                            ->get();

        $id="";

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('cusuario.create', compact('id','notificVencidas','notificDay','roles','uniops','direccion','tipoacts','valor','usertipoacts','useruniops','useruniopsId','programas','usertipoactsmobs','menuDashboard'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $uniact=$request->get('uniops');

        $user = new User;
        
        $user->uidempresa  = $request->uidempresa;
        $user->name        = $request->name;
        $user->email       = $request->email;
        $user->cargo       = $request->cargo;
        $user->selectuniop = $uniact[0];
        $user->password    =  bcrypt($request->email);
        /*$user->password     =  Hash::make($request->email);*/
        $user->activation_token  =  str_random(60);
        $user->active       =  1;
        $user->status      = "A";
        if($request->solomisact=='on'){
            $user->misact  = 'S';
            $user->solomisact = 'S';
        }else{
            $user->misact  = 'N';
            $user->solomisact = 'N';
        }
        $user->save();
        $id_user = $user->id;

        $user->roles()->sync($request->get('roles'));


        $datos=$request->get('uniops');
        $i=0;$j=0;
        if(!empty($datos)){
             $j=count($datos); }

        for($i=0; $i < $j; $i++) {
            $useruniop=new Useruniop;
            $useruniop->user_id     = $id_user;
            $useruniop->unidadopuid = $datos[$i];
            $useruniop->save();

        }

        $dtiposact=$request->get('tipacts');
        $i=0;$j=0;
        if(!empty($dtiposact)){
            $j=count($dtiposact); }

        for($i=0; $i < $j; $i++) {
            $usertiposact=new Usertipoact;
            $usertiposact->user_id     = $id_user;
            $usertiposact->tipoacts_id = $dtiposact[$i];
            $usertiposact->save();
            //$i++;
        }

        $dtiposactm=$request->get('tipactsmob');
        $i=0;$j=0;
        if(!empty($dtiposactm)){
            $j=count($dtiposactm);}
        for($i=0; $i < $j; $i++) {
            $usertiposactmob=new Usertipoactmob;
            $usertiposactmob->user_id     = $id_user;
            $usertiposactmob->tipoacts_id = $dtiposactm[$i];
            $usertiposactmob->save();
            //$i++;
        }


        Session::flash('save','El Usuario se ha CREADO Exitosamente');

        return redirect()->route('cusuario.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
        $rolesuserhs  = Roleuserh::where("user_id","LIKE",$id)->get();
        $useruniops   = Useruniop::where("user_id","LIKE",$id)->get();
        $usertipoacts = Usertipoact::where("user_id","LIKE",$id)->get();
        $usertipoactsmobs =Usertipoactmob::where("user_id","LIKE",$id)->get();


        $user   = User::find($id);
        $roles  = Role::Orderby("name","asc")->get();
        $uniops = Unidadop::where("unidadopstatus","LIKE","A")
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


        $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                              ->where("user_id","LIKE",$userid)
                              ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                              ->get();

        foreach ($rolesActivo as $rolActivo) {
        $valor= $rolActivo->slug;
        }
        $programas=Programas::where('empresauid','LIKE',$uidempresa)
                            ->where('uniopuid','LIKE',$useruniopsId)
                            ->get();

        $id="";
        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('cusuario.edit',compact('id','notificVencidas','notificDay','user','roles','uniops','direccion','tipoacts','useruniops','rolesuserhs','usertipoacts','valor','useruniopsId','programas','usertipoactsmobs','menuDashboard')); /*en compact va la variable de las vista*/
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
        $datos=$request->get('uniops');
        $user = User::find($id);
        //dd($request->solomisact);
        if($request->solomisact=='on'){
            $user->misact  = 'S';
            $user->solomisact = 'S';
        }else{
            $user->misact  = 'N';
            $user->solomisact = 'N';
        }
        $user->name  =$request->name;
        $user->email = $request->email;
        $user->cargo = $request->cargo;
        $user->status = $request->status;
        if(!empty($request->passw)){
            $user->password     =  bcrypt($request->passw);
        }
        $user->selectuniop = $datos[0];
        if ($request->status=="I"){
            $user->status = "I";
            $user->save();
            $rol=4;
            $user->roles()->sync($rol);
            $userTipoActRow = Usertipoact::where('user_id', 'LIKE', $id)->delete();
            $userUniOpeRow  = Useruniop::where('user_id', 'LIKE', $id)->delete();
            Session::flash('delete','El Usuario de DESACTIVADO Exitosamente');
            return redirect()->route('cusuario.index');
        }

        $user->save();
        $user->roles()->sync($request->get('roles'));

        $datos=$request->get('uniops');
        $i=0;$j=0;

        if(!empty($datos)){
            $j=count($datos); }
        $affectedRows = Useruniop::where('user_id', 'LIKE', $id)->delete();
        for($i=0; $i < $j; $i++) {

            $useruniop=new Useruniop;
            $useruniop->user_id     = $id;
            $useruniop->unidadopuid = $datos[$i];
            $useruniop->save();
            $usuario=User::find($id);
            $usuario->selectuniop = $datos[$i];
            $usuario->save();

        }

        $dtiposact=$request->get('tipacts');
        $i=0;$j=0;
        if(!empty($dtiposact)){
            $j=count($dtiposact); }
        $affectedRows = Usertipoact::where('user_id', 'LIKE', $id)->delete();
        for($i=0; $i < $j; $i++) {

            $usertiposact=new Usertipoact;
            $usertiposact->user_id     = $id;
            $usertiposact->tipoacts_id = $dtiposact[$i];
            $usertiposact->save();
        }

        $dtiposactm=$request->get('tipactsmob');
        $i=0;$j=0;
        if(!empty($dtiposactm)){
            $j=count($dtiposactm);}
        $affectedRows = Usertipoactmob::where('user_id', '=', $id)->delete();
        //dd($id);
        for($i=0; $i < $j; $i++) {

            $usertiposactmob=new Usertipoactmob;
            $usertiposactmob->user_id     = $id;
            $usertiposactmob->tipoacts_id = $dtiposactm[$i];
            $usertiposactmob->save();
        }

        Session::flash('update','Los Permisos fueron ASIGNADO Exitosamente');
        return redirect()->route('cusuario.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->status = "I";
        $user->save();
        $rol=4;
        $user->roles()->sync($rol);
        $userTipoActRow = Usertipoact::where('user_id', 'LIKE', $id)->delete();
        $userUniOpeRow  = Useruniop::where('user_id', 'LIKE', $id)->delete();
        Session::flash('delete','El Usuario de DESACTIVADO Exitosamente');
        return redirect()->route('cusuario.index');

    }
}
