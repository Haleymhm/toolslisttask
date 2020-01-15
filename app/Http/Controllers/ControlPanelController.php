<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Unidadop;
use App\Tipoact;
use App\User;
use App\Usertipoact;
use App\Usertipoactmob;
use App\Useruniop;
use App\Grupotipoact;
use App\Listado;
use App\Elemento;
use App\Actividadtipodato;
use App\Dashboard;
use App\DBItem;
use App\Programas;
use App\Roleuserh;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\File;
use Session;
use Carbon\Carbon;
use DB;
use DBItem as GlobalDBItem;
use Illuminate\Database\Eloquent\Collection;

class ControlPanelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $empresas=Empresa::All();
        foreach ($empresas as $empresa) {
            $unidadOp=Unidadop::where('empresauid','=',$empresa->id)
                              ->where('unidadopstatus','=','A')
                              ->get();
            $totalUniOP=$unidadOp->count();
            $usuarios=User::where('uidempresa','=',$empresa->id)
                          ->where('status','=','A')
                          ->get();
            $infoDom=FUNC_sizeCarpeta($empresa->id);

            $totalUser=$usuarios->count();
            $datosEmpresa[]=['uid'=>$empresa->id,
                            'nombre'=>$empresa->empresanombre,
                            'nusers'=>$totalUser,
                            'nunipos'=>$totalUniOP,
                            'tsize'=>$infoDom['tsize'],
                            'nfiles'=>$infoDom['nfiles'],
                            'vigencia'=>$empresa->empresavigente,
                            'status'=>$empresa->empresastatus
            ];
        }
        $datEmpresas=collect($datosEmpresa);
        $userid=auth()->user()->id;
        $rolesActivo=Roleuserh::where("user_id","LIKE",$userid)->get();
        foreach ($rolesActivo as $rolActivo) { $valor = $rolActivo->role_id; }

        //if($valor==1){ return redirect('cpanel/admin'); } //IR AL CPANEL
        if($valor==1){
            //dd($valor . " Panel del Control");
            return view('cpanel.index',compact('datEmpresas'));
        }else{
            return redirect('/home');
        }

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
            'nomcont'=>'required',
            'emailcont'=>'required',
            'nomemp'=>'required',
            'tlfcont'=>'required',
            'uniop'=>'required'
        ]);

        $user = new User;
        $user->name      = $request->nomcont;
        $user->email     =  $request->emailcont;
        $user->password  =  Hash::make($request->emailcont);
        $user->activation_token  =  str_random(60);
        $user->save();

        $id_user = $user->id;
        $roles=6;
        $user->roles()->sync($roles);


        $empresa = new Empresa;
        $empresa->rutrif           = $request->codemp;
        $empresa->empresanombre    = $request->nomemp;
        $empresa->empresadireccion = $request->diremp;
        $empresa->empresaemail     = $request->emailcont;
        $empresa->empresatelefono  = $request->tlfcont;
        $empresa->empresavigente   = date("Y-m-d");
        $empresa->empresastatus    = 'A';
        $empresa->uiduser          =  $id_user;
        $empresa->save();

        $id_empresa=$empresa->id;

        $directorio = public_path() . '/upload/' . $id_empresa;
        if (!File::exists($directorio)) {
            $resultado = File::makeDirectory($directorio , 0755, true);
            }

        $unidadop = new Unidadop;
        $unidadop->unidadopuid =Uuid::uuid4();
        $unidadop->empresauid = $id_empresa;
        $unidadop->unidadopnombre =$request->uniop;
        $unidadop->unidadopstatus ='A';
        $unidadop->deleted = 0;
        $unidadop->save();

        $id_uniope=$unidadop->unidadopuid;

        $userUniOp= new Useruniop;
        $userUniOp->user_id = $id_user;
        $userUniOp->unidadopuid = $id_uniope;
        $userUniOp->save();

        $user = User::find($id_user);
        $user->uidempresa =$id_empresa;
        $user->selectuniop =$id_uniope;


        if( $user->save()){
            Session::flash('save','La Registro fue CREADO Exitosamente');
            return redirect('/cpanel/empresa/'.$id_empresa.'/edit');
            //return "GUARDO";
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
            return redirect('/cpanel/admin');
        }

        //return $user;


    }


    public function edit($id)
    {
        $empresas = Empresa::find($id);
        $users = User::where('uidempresa','=',$id)->get();
        foreach ($users  as $key => $user) {
            $rolUsuario=Roleuserh::where('user_id','=',$user->id)->get();
            foreach ($rolUsuario as $rU) {
                $idrol=$rU->role_id;
            }
            $usuarios[]=[
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email,
                'selectuniop'=>$user->selectuniop,
                'status'=>$user->status,
                'idrol'=>$idrol
            ];
        }
        $uniopes = Unidadop::where('empresauid','=',$id)->get();
        $empresaAll = Empresa::all();
        $roles = Role::Orderby('name','asc')->get();
        $tipoacts = Tipoact::where('status','=','A')
                           ->where('empresauid','=',$id)
                           ->orderBy('titulo','asc')
                           ->get();
        $rolUser = Roleuserh::all();
        $useruniops = Useruniop::all();
        $usertipoacts = Usertipoact::all();
        $usertipoactsmobs = Usertipoactmob::all();

        return view('cpanel.editempresa',compact('id','empresas','usuarios','uniopes','empresaAll','roles','tipoacts','rolUser','useruniops','usertipoacts','usertipoactsmobs'));

    }

    public function stroreEdit(Request $request)
    {
        //dd($request->id);
        $datvi=new Carbon($request->empresaviegencia);
        $empresa=Empresa::find($request->id);
        $empresa->rutrif           = $request->rutrif;
        $empresa->empresanombre    = $request->empresanombre;
        $empresa->empresadireccion = $request->empresadireccion;
        $empresa->empresaemail     = $request->empresaemail;
        $empresa->empresatelefono  = $request->empresatelefono;
        $empresa->empresastatus    = $request->empresastatus;
        $empresa->empresavigente   = $datvi;

        if( $empresa->save()){
            Session::flash('update','La Empresa fue Actualizada Correctamente');
            return redirect('cpanel/admin');
        }else{
            Session::flash('error','Error el Actualizar la Empresa');
            return redirect('cpanel/empresa/'.$request->id.'/edit');
        }

    }

    public function crearUser(Request $request)
    {
        $request->validate([
            'nomcont'=>'required',
            'emailcont'=>'required',
            'empresauid'=>'required',
            'roles'=>'required',
            'uniops'=>'required',
            'tipacts'=>'required'
        ]);
        $uniact=$request->get('uniops');

        $user = new User;
        $user->name         =  $request->nomcont;
        $user->email        =  $request->emailcont;
        $user->uidempresa   =  $request->empresauid;
        $user->selectuniop  =  $uniact[0];
        $user->password     =  Hash::make($request->emailcont);
        $user->active       =  1;
        $user->activation_token  =  str_random(60);

        if($saved=$user->save()){
            $id_user=$user->id;
            $user->roles()->sync($request->get('roles'));
            $datos=$request->get('uniops');
            $i=0;$j=0;
            if(!empty($datos)){ $j=count($datos); }

            for($i=0; $i < $j; $i++) {
                $useruniop=new Useruniop;
                $useruniop->user_id     = $id_user;
                $useruniop->unidadopuid = $datos[$i];
                $useruniop->save();
            }

            $dtiposact=$request->get('tipacts');
            $i=0;$j=0;
            if(!empty($dtiposact)){ $j=count($dtiposact); }

            for($i=0; $i < $j; $i++) {
                $usertiposact=new Usertipoact;
                $usertiposact->user_id     = $id_user;
                $usertiposact->tipoacts_id = $dtiposact[$i];
                $usertiposact->save();
                //$i++;
            }

            $dtiposactm=$request->get('tipactsmob');
            $i=0;$j=0;
            if(!empty($dtiposactm)){ $j=count($dtiposactm);}
            for($i=0; $i < $j; $i++) {
                $usertiposactmob=new Usertipoactmob;
                $usertiposactmob->user_id     = $id_user;
                $usertiposactmob->tipoacts_id = $dtiposactm[$i];
                $usertiposactmob->save();
                //$i++;
            }


            Session::flash('update','El Usuario a sido CREADO Correctamente');
            return redirect('cpanel/empresa/'.$request->empresauid.'/edit');
        }else{
            Session::flash('error','No se logro Crear el Nuevo Usuario');
            return redirect('cpanel/empresa/'.$request->empresauid.'/edit');
        }
    }

    public function editarUser(Request $request)
    {
        $request->validate([
            'nomcont'=>'required',
            'emailcont'=>'required',
            'empresauid'=>'required',
            'roles'=>'required',
            'uniops'=>'required',
            'tipacts'=>'required'
        ]);

        $datos=$request->get('uniops');
        $user = User::find($request->usuariouid);
        $user->name         =  $request->nomcont;
        $user->uidempresa   =  $request->empresauid;
        $user->email        =  $request->emailcont;
        $user->active       =  1;
        $user->selectuniop  =  $datos[0];
        $user->status       =  $request->status;
        if(!empty($request->passw)){
            $user->password     =  Hash::make($request->passw);
        }
        if ($request->status=="I"){
            $user->status = "I";
            $user->active = 0;
            $user->save();
            $rol=4;
            $user->roles()->sync($rol);
            Session::flash('update','El Usuario a sido ACTUALIZADO Exitosamente');
            return redirect('cpanel/empresa/'.$request->empresauid.'/edit');
        }else{
            if($saved=$user->save()){
                $user->roles()->sync($request->get('roles'));
                $datos=$request->get('uniops');
                $i=0;$j=0;
                if(!empty($datos)){ $j=count($datos); }
                $affectedRows = Useruniop::where('user_id', '=', $request->usuariouid)->delete();
                for($i=0; $i < $j; $i++) {
                    $useruniop=new Useruniop;
                    $useruniop->user_id     = $request->usuariouid;
                    $useruniop->unidadopuid = $datos[$i];
                    $useruniop->save();
                }

                $dtiposact=$request->get('tipacts');
                $i=0;$j=0;
                if(!empty($dtiposact)){ $j=count($dtiposact); }
                $affectedRows2 = Usertipoact::where('user_id', '=', $request->usuariouid)->delete();
                for($i=0; $i < $j; $i++) {
                    $usertiposact=new Usertipoact;
                    $usertiposact->user_id     = $request->usuariouid;
                    $usertiposact->tipoacts_id = $dtiposact[$i];
                    $usertiposact->save();
                    //$i++;
                }

                $dtiposactm=$request->get('tipactsmob');
                $i=0;$j=0;
                if(!empty($dtiposactm)){ $j=count($dtiposactm);}
                $affectedRows3 = Usertipoactmob::where('user_id', '=', $request->usuariouid)->delete();
                for($i=0; $i < $j; $i++) {
                    $usertiposactmob=new Usertipoactmob;
                    $usertiposactmob->user_id     = $request->usuariouid;
                    $usertiposactmob->tipoacts_id = $dtiposactm[$i];
                    $usertiposactmob->save();
                    //$i++;
                }

                $empresa=$request->empresauid;
                Session::flash('update','El Usuario a sido ACTUALIZADO Exitosamente');
                return redirect('cpanel/empresa/'.$empresa.'/edit');
            }else{
                Session::flash('error','No se logro actualizar al usuario');
                return redirect('cpanel/empresa/'.$empresa.'/edit');
            }
        }


    }

    public function crearUniOp(Request $request)
    {

        $unidadop = new Unidadop;
        $unidadop->unidadopuid =Uuid::uuid4();
        $unidadop->empresauid = $request->empresauid;
        $unidadop->unidadopnombre =$request->uniop;
        $unidadop->unidadopstatus ='A';
        $unidadop->deleted = 0;
        $empresa=$request->empresauid;
        if( $unidadop->save()){
            Session::flash('update','La Unidad fue ELIMINADO Exitosamente');
            return redirect('cpanel/empresa/'.$empresa.'/edit');
        }else{
            Session::flash('error','La Unidad fue ELIMINADO Exitosamente');
            return redirect('cpanel/empresa/'.$empresa.'/edit');
        }
    }

    public function editUniOp(Request $request)
    {

        $unidadop = Unidadop::find($request->uniopuid);
        $unidadop->unidadopnombre =$request->uniop;
        $unidadop->unidadopstatus =$request->status;
        $empresa=$request->empresauid;
        if( $unidadop->save()){
            Session::flash('update','La Unidad fue ELIMINADO Exitosamente');
            return redirect('cpanel/empresa/'.$empresa.'/edit');
        }else{
            Session::flash('error','La Unidad fue ELIMINADO Exitosamente');
            return redirect('cpanel/empresa/'.$empresa.'/edit');
        }
    }

    public function aplicarPlantilla(Request $request)
    {
        $plantillauid=$request->plantillauid;
        $empresauid = $request->empresauid;

       // dd('Id Plantilla: '. $plantillauid . '   Uid Empresa: '.$empresauid);
        /***** INICIO DE MODELO MENU ******/
        $grupTipos=Grupotipoact::where('status','=' ,'A')
                                ->where('empresauid','=',$plantillauid)
                                ->get();

        foreach ($grupTipos as $grupTipo) {
            $gt     = new Grupotipoact;
            $gt->uid = Uuid::uuid4();
            $gt->empresauid = $empresauid;
            $gt->titulo = $grupTipo->titulo;
            $gt->descripgroup = $grupTipo->descripgroup;
            $gt->parent  = $grupTipo->parent;
            $gt->orden  = $grupTipo->orden;
            $gt->icono = $grupTipo->icono;
            $gt->tmenu  = $grupTipo->tmenu ;
            $gt->status = $grupTipo->status ;
            if($gt->save()){
                $arrayParent[]=['idActual'=>$grupTipo->id,
                                'idNuevo'=>$gt->id,
                                'parentActual'=>$grupTipo->parent];

            }
        }

        $tipoActividad = Tipoact::where('status','=' ,'A')
                                ->where('empresauid','=',$plantillauid)
                                ->get();
        foreach ($tipoActividad as $tac) {
            $ta     = new Tipoact;
            $uidta=Uuid::uuid4();
            $ta->uid = $uidta;
            $ta->empresauid = $empresauid;
            $ta->titulo = $tac->titulo;
            $ta->tipoactdescrip = $tac->tipoactdescrip;
            $ta->tipoactcolor  = $tac->tipoactcolor;
            $ta->tvista  = $tac->tvista;
            $ta->mcal  = $tac->mcal;
            $ta->mind  = $tac->mind;
            $ta->parent  = $tac->parent;
            $ta->orden  = $tac->orden;
            $ta->icono = $tac->icono;
            $ta->tmenu  = $tac->tmenu;
            $ta->status = $tac->status;
            $ta->save();
            if($ta->save()){
                $arrayTA[]=['uidactual'=>$tac->uid,
                            'uidnuevo'=>$ta->uid];
            }
        }


        $listados=Listado::where('empresauid','=',$plantillauid)
                         ->where('status','=' ,'A')
                         ->get();

        foreach ($listados as $listado) {
            $lista = new Listado;
            $lista->empresauid = $empresauid;
            $lista->nombrelista = $listado->nombrelista;
            $lista->descplista = $listado->descplista;
            $lista->ver = $listado->ver;
            $lista->status = $listado->status;
            if($lista->save()){
                $arrayLis[]=['idactual'=>$listado->id,
                             'uidnuevo'=>$lista->id];
            }
        }

        $elementos=Elemento::where('empresauid','=',$plantillauid)
                            ->where('status','=' ,'A')
                            ->get();

        foreach ($elementos as $elemento) {
            $element = new Elemento;
            $element->empresauid = $empresauid;
            $element->listadouid = $elemento->listadouid;
            $element->elemnombre = $elemento->elemnombre;
            $element->elemdescip = $elemento->elemdescip;
            $element->elempos    = $elemento->elempos;
            $element->status     = $elemento->status;
            if($element->save()){
                $arrayElem[]=['idactual'=>$elemento->id,
                                'uidnuevo'=>$element->id];
            }
        }


        $tipoContenido=Actividadtipodato::where('empresauid','=',$plantillauid)
                                        ->where('status','=' ,'A')
                                        ->get();

        foreach ($tipoContenido as $tc) {
            $tipCont = new Actividadtipodato;
            $tipCont->empresauid = $empresauid;
            $tipCont->contenidotipoid = $tc->contenidotipoid;
            $tipCont->tipoactid = $tc->tipoactid;
            $tipCont->etiqueta = $tc->etiqueta;
            $tipCont->posicion = $tc->posicion;
            $tipCont->mostrar = $tc->mostrar;
            $tipCont->obligatorio = $tc->obligatorio;
            $tipCont->idlista = $tc->idlista;
            $tipCont->status = $tc->status;
            if($tipCont->save()){
                $arrayTipCont[]=['idactual'=>$tc->id,
                                 'uidnuevo'=>$tipCont->id];
            }
        }

        $dashboard=Dashboard::where('empresauid','=',$plantillauid)
                            ->where('status','=' ,'A')
                            ->get();

        foreach ($dashboard as $db) {
            $dashB = new Dashboard;
            $dashB->empresauid = $empresauid;
            $dashB->uniopuid = $db->uniopuid;
            $dashB->dbnom = $db->dbnom;
            $dashB->dbdesc = $db->dbdesc;
            $dashB->dbpos = $db->dbpos;
            $dashB->status = $db->status;
            if($dashB->save()){
                $arraydashB[]=['idactual'=>$db->id,
                               'uidnuevo'=>$dashB->id];
            }
        }

        $dbItem=DBItem::where('empresauid','=',$plantillauid)
                      ->where('status','=' ,'A')
                      ->get();

        foreach ($dbItem as $dbi) {
            $dashBIntem = new DBItem;
            $dashBIntem->empresauid = $empresauid;
            $dashBIntem->dashboarduid  = $dbi->dashboarduid;
            $dashBIntem->itemtipo = $dbi->itemtipo;
            $dashBIntem->tipoactuid = $dbi->tipoactuid;
            $dashBIntem->agrupartipocontuid = $dbi->agrupartipocontuid;
            $dashBIntem->itemoperacion  = $dbi->itemoperacion ;
            $dashBIntem->itemgrafico = $dbi->itemgrafico;
            $dashBIntem->itempos  = $dbi->itempos;
            $dashBIntem->itemdesde  = $dbi->itemdesde;
            $dashBIntem->status = $dbi->status;
            if($dashBIntem->save()){
                $arraydashBoItem[]=['idactual'=>$dbi->id,
                                    'uidnuevo'=>$dashBIntem->id];
            }
        }


        if(!empty($arrayParent)){
            foreach($arrayParent as $ap){
                $sql='UPDATE groupstipact SET parent='.$ap['idNuevo'].' WHERE empresauid="'.$empresauid.'" AND parent='.$ap['idActual'];
                $updateGrupoTip=DB::select($sql);
                $sqlTA='UPDATE tipoacts SET parent='.$ap['idNuevo'].' WHERE empresauid="'.$empresauid.'" AND parent='.$ap['idActual'];
                $updateTipoAct=DB::select($sqlTA);
            }
        }

        if(!empty($arraydashB)){
            foreach($arraydashB as $dashb){
                $sqlbdii='UPDATE dbitem SET dashboarduid ="'.$dashb['uidnuevo'].'" WHERE empresauid="'.$empresauid.'" AND dashboarduid ="'.$dashb['idactual'].'"';
                $updateDBI=DB::select($sqlbdii);
            }
        }

        if(!empty($arrayTA)){
            foreach($arrayTA as $ata){
                $sql='UPDATE actividadtipocontenido SET tipoactid ="'.$ata['uidnuevo'].'" WHERE empresauid="'.$empresauid.'" AND tipoactid ="'.$ata['uidactual'].'"';
                $updateATC=DB::select($sql);

                $sqldbi='UPDATE dbitem SET tipoactuid ="'.$ata['uidnuevo'].'" WHERE empresauid="'.$empresauid.'" AND tipoactuid ="'.$ata['uidactual'].'"';
                $updateDBI=DB::select($sqldbi);
            }
        }

        if(!empty($arrayLis)){
            foreach($arrayLis as $alis){
                $sqlElem='UPDATE elementos SET listadouid ="'.$alis['uidnuevo'].'" WHERE empresauid="'.$empresauid.'" AND listadouid ="'.$alis['idactual'].'"';
                $updateElemt=DB::select($sqlElem);

                $sqllisTA='UPDATE actividadtipocontenido SET idlista ="'.$alis['uidnuevo'].'" WHERE empresauid="'.$empresauid.'" AND idlista ="'.$alis['idactual'].'"';
                $updateATlis=DB::select($sqllisTA);
            }
        }

        //dd($arrayTA);
        Session::flash('update','La Plantilla fue IMPORTADA Exitosamente');
        return redirect('cpanel/empresa/'.$empresauid.'/edit');


    }

    public function deleteEmpresa(Request $request)
    {
        $uidEmpresa=$request->empresauid;
        $i=0;
        if(!empty($uidEmpresa)){
            $sql[0]="DELETE FROM actividadcontenido WHERE empresauid ='".$uidEmpresa."'";
            $sql[1]="DELETE FROM actividadgrupo WHERE empresauid ='".$uidEmpresa."";
            $sql[2]="DELETE FROM actividadperiodica WHERE empresauid ='".$uidEmpresa."";
            $sql[3]="DELETE FROM actividadprograma WHERE empresauid ='".$uidEmpresa."";
            $sql[4]="DELETE FROM actividads WHERE empresauid ='".$uidEmpresa."'";
            $sql[5]="DELETE FROM actividadtipocontenido WHERE empresauid ='".$uidEmpresa."'";
            $sql[6]="DELETE FROM actividaduser WHERE empresauid ='".$uidEmpresa."'";
            $sql[7]="DELETE FROM carpetas WHERE empresauid ='".$uidEmpresa."'";
            $sql[8]="DELETE FROM dashboard WHERE empresauid ='".$uidEmpresa."'";
            $sql[9]="DELETE FROM dbitem WHERE empresauid ='".$uidEmpresa."'";
            $sql[10]="DELETE FROM documentos WHERE empresauid ='".$uidEmpresa."'";
            $sql[11]="DELETE FROM groupstipact WHERE empresauid ='".$uidEmpresa."'";
            $sql[12]="DELETE FROM listados WHERE empresauid ='".$uidEmpresa."'";
            $sql[13]="DELETE FROM elementos WHERE empresauid ='".$uidEmpresa."'";
            $sql[14]="DELETE FROM tipoacts WHERE empresauid ='".$uidEmpresa."'";
            $sql[15]="DELETE FROM unidadops WHERE empresauid ='".$uidEmpresa."'";
            $sql[16]="DELETE FROM users WHERE uidempresa ='".$uidEmpresa."'";
            $sql[17]="DELETE FROM empresas WHERE id='".$uidEmpresa."'";
            for ($i=0; $i < 18 ; $i++) {
                $delEmpresa=DB::select($sql[$i]);
            }
            Session::flash('delete','La Empresa fue Actualizada Correctamente');
            return redirect('cpanel/admin');
        }
    }


}
