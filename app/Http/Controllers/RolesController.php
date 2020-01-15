<?php

namespace App\Http\Controllers;

use App\Unidadop;
use App\Empresa;
use App\User;
use App\Useruniop;
use App\Roleuserh;
use App\Tipoact;
use App\Usertipoact;
use App\Permisorolh;
use App\Grupotipoact;
use App\Programas;
use App\Dashboard;
use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Models\Permission;
use Session;


class RolesController extends Controller
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
        $userid=auth()->user()->id;
        $uidempresa=auth()->user()->uidempresa;
        $useruniopsId=auth()->user()->selectuniop;
        $roles=Role::where("deleted","=",0)
                    ->Orderby("name","asc")->get();

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
        $useruniops   = Useruniop::where("user_id","LIKE",$userid)->get();
        $rolesActivo  = Roleuserh::join("roles","roles.id","=","role_user.role_id")
                                 ->where("user_id","LIKE",$userid)
                                 ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                                 ->get();

        foreach ($rolesActivo as $rolActivo) { $valor = $rolActivo->slug; }

        $roles = Role::where("deleted","=",0)
                     ->orderBy("name","ASC")->get();
        $id="";
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('roles.index', compact('id','notificVencidas','notificDay','roles','direccion','uniops','tipoacts','usertipoacts','useruniops','valor','useruniopsId','programas','menuDashboard'));
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
        $roles=Role::where("deleted","=",0)
                    ->Orderby("name","asc")->get();

        $uniops = Unidadop::where("deleted","=",0)
                          ->where("unidadopstatus","LIKE","A")
                          ->where("empresauid","LIKE",$uidempresa)->get();
                          foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }

        $tipoacts =Tipoact::where("status","LIKE","A")
                          ->where("empresauid","LIKE",$uidempresa)
                          ->orderBy("titulo","asc")
                          ->get();

        $usertipoacts =Usertipoact::where("user_id","LIKE",$userid)->get();
        $useruniops   = Useruniop::where("user_id","LIKE",$userid)->get();
        $rolesActivo  = Roleuserh::join("roles","roles.id","=","role_user.role_id")
                                ->where("user_id","LIKE",$userid)
                                ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                                ->get();

        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }
        $permissions = Permission::orderBy("agrupar","asc")->get();
        $id="";
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('roles.create', compact('id','notificVencidas','notificDay','permissions','roles','uniops','direccion','tipoacts','usertipoacts','useruniops','valor','useruniopsId','programas','menuDashboard'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = Role::create($request->all());
        $role->permissions()->sync($request->get('permissions'));

        Session::flash('save','El Rol fue CREADO Exitosamente');
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function show(Roles $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userid=auth()->user()->id;
        $uidempresa=auth()->user()->uidempresa;
        $useruniopsId=auth()->user()->selectuniop;
        $roles=Role::where("deleted","=",0)
                    ->Orderby("name","asc")->get();

        $uniops = Unidadop::where("deleted","=",0)
                          ->where("unidadopstatus","LIKE","A")
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

        $usertipoacts =Usertipoact::where("user_id","LIKE",$userid)->get();
        $useruniops   = Useruniop::where("user_id","LIKE",$userid)->get();
        $rolesActivo  = Roleuserh::join("roles","roles.id","=","role_user.role_id")
                                ->where("user_id","LIKE",$userid)
                                ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                                ->get();

        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }
        $role = Role::find($id);
        $permissions = Permission::orderBy("agrupar","asc")->get();
        $permisosroles=Permisorolh::where("role_id","=",$id)->get();
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('roles.edit', compact('id','notificVencidas','notificDay','role', 'permissions','roles','uniops','direccion','tipoacts','usertipoacts','useruniops','valor','permisosroles','useruniopsId','programas','menuDashboard'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $role->name=$request->name;
        $role->slug=$request->slug;
        $role->description=$request->description;
        $role->save();


        $role->permissions()->sync($request->get('permissions'));

        Session::flash('save','El Rol fue EDITADO Exitosamente');
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *        return redirect()->route('roles.index');
     * @param  \App\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $role->deleted = 1;
        $role->save();

        Session::flash('save','El Rol fue EDITADO Exitosamente');
        return redirect()->route('roles.index');
    }
}
