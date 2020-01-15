<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tipoact;
use App\Unidadop;
use App\Empresa;
use App\User;
use App\Useruniop;
use App\Roleuserh;
use App\Usertipoact;
use App\Grupotipoact;
use App\Actividadtipodato;
use App\Contenidotipo;
use App\Actividadcontenido;
use App\Listado;
use App\Programas;
use App\Colores;
use App\Carpeta;
use App\Actividadgrupo;

use App\Dashboard;
use Ramsey\Uuid\Uuid;
use Session;
use DB;
use App\Actividad;

class TipoactController extends Controller
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


        $uniops= Unidadop::where("deleted","=",0)
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

        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $tipoactividads = Tipoact::where("empresauid","=",$uidempresa)
                                 ->orderBy("titulo","asc")
                                 ->get();
        $colores=Colores::all();
        $id="";

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('tipoact.index',compact('id','notificVencidas','notificDay','tipoactividads','direccion','uniops','valor','usertipoacts','useruniops','tipoacts','useruniopsId','grupotipoactividads','programas','colores','menuDashboard'));
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
        $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
        ->where("user_id","LIKE",$userid)
        ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
        ->get();

        foreach ($rolesActivo as $rolActivo) {
        $valor= $rolActivo->slug;
        }

        $usertipoacts =Usertipoact::where("user_id","LIKE",$userid)->get();
        $useruniops = Useruniop::where("user_id","LIKE",$userid)->get();


        $grupotipoactividads = Grupotipoact::where("empresauid","LIKE",$uidempresa)
                                           ->where("status","LIKE","A")
                                           ->orderBy("parent")
                                           ->orderBy("orden")
                                           ->get();

        $uniops =Unidadop::where("deleted","=",0)
                         ->where("empresauid","LIKE",$uidempresa)
                         ->where("unidadopstatus","LIKE","A")->get();
                         foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();
        $id="";
        $colores=Colores::all();
        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('tipoact.create',compact('id','notificVencidas','notificDay','uniops','direccion','valor','usertipoacts','useruniops','tipoacts','useruniopsId','grupotipoactividads','programas','colores','menuDashboard'));
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
            'tipoactnombre'=>'required',
            'tipoactcolor'  =>'required'
        ]);
        $tipoactUid= Uuid::uuid4();
        $tipoact= new Tipoact;
        $tipoact->uid             = $tipoactUid;
        $tipoact->empresauid      = $request->empresauid;
        $tipoact->titulo          = $request->tipoactnombre;
        $tipoact->tipoactdescrip  = $request->tipoactdescrip;
        $tipoact->tipoactcolor    = $request->tipoactcolor;
        $tipoact->icono           = $request->icono;
        $tipoact->parent          = $request->parent;
        $tipoact->tvista          = "cal";
        $tipoact->mcal            = "SI";
        $tipoact->mind            = "SI";
        $tipoact->orden           = 1;
        $tipoact->comporta        = 1;
        $tipoact->status          = "A";

        $tipoact->save();
        $idact=$tipoact->id;
        if( $tipoact->save() ){
            Session::flash('save','La Registro fue CREADO Exitosamente');
            return redirect('tipoact/'.$idact.'/edit');
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tipoact  $tipoact
     * @return \Illuminate\Http\Response
     */
    public function show(Tipoact $tipoact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tipoact  $tipoact
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
        $contipos=Contenidotipo::orderBy("id","asc")->get();
        $tipoactividad2 = Tipoact::where("id","=",$id)
                                 ->where("empresauid","LIKE",$uidempresa)
                                 ->get();

        foreach($tipoactividad2 as $tp){
            $uid=$tp->uid;
        }
        $contenidos=Actividadtipodato::where("empresauid","LIKE",$uidempresa)
                                     ->where("tipoactid","=",$uid)
                                     /*->orderBy("actividadtipocontenido.status")*/
                                     ->orderBy("actividadtipocontenido.posicion")
                                     ->get();
        $nColumnas = count($contenidos);
        $nColumnas = $nColumnas + 1;

        $listas=Listado::where("empresauid","LIKE",$uidempresa)
                                ->where("status","LIKE","A")
                                ->orderBy("nombrelista","asc")
                                ->get();

        $tipoactividad = Tipoact::find($id);

        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $colores=Colores::all();

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('tipoact.edit',compact('id','notificVencidas','notificDay','tipoactividad','uniops','direccion','valor','usertipoacts','useruniops','tipoacts','useruniopsId','grupotipoactividads','contipos','contenidos','listas','nColumnas','programas','colores','menuDashboard'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tipoact  $tipoact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'prefijo'  =>'required|min:1|max:3'
        ]);

        $tipoact = Tipoact::find($id);
        $tipoact->empresauid      = $request->empresauid;
        $tipoact->titulo          = $request->tipoactnombre;
        $tipoact->tipoactdescrip  = $request->tipoactdescrip;
        $tipoact->tipoactcolor    = $request->tipoactcolor;
        $tipoact->status          = $request->tipoactstatus;
        $tipoact->tvista          = $request->inictialView;
        $tipoact->mcal            = $request->mcal;
        $tipoact->mind            = $request->mind;
        $tipoact->parent          = $request->parent;
        $tipoact->orden           = $request->orden;
        $tipoact->icono           = $request->icono;
        $tipoact->comporta        = $request->comporta;
        $tipoact->prefijo         = strtoupper($request->prefijo);

       // $tipoact->save();

        if( $tipoact->save() ){
            Session::flash('update','La Registro fue MODIFICADO Exitosamente');
        return redirect()->route('tipoact.index');
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tipoact  $tipoact
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipoact = Tipoact::find($id);
        $tipoact->deleted = 1;
        $tipoact->save();

        if( $tipoact->save() ){
            Session::flash('update','La Unidad fue ELIMINADO Exitosamente');
        return redirect()->route('tipoact.index');
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }
    }

    public function addcontenido(Request $request)
    {
        $request->validate([
            'titulo'   => 'required',
            'tipo'     => 'required',
            'posicion' => 'required',
            'status'   => 'required'
        ]);

        if ($request->mostar=="on"){ $mostrar="SI";
        }else{ $mostrar="NO"; }
        if ($request->obligatorio=="on"){ $obligatorio="SI";
        }else{ $obligatorio="NO"; }

        $contipo= new Actividadtipodato;
        $contipo->empresauid       = $request->empresauid;
        $contipo->tipoactid        = $request->tipoactuid;
        $contipo->etiqueta         = $request->titulo;
        $contipo->contenidotipoid  = $request->tipo;
        $contipo->posicion         = $request->posicion;
        $contipo->mostrar          = $mostrar;
        $contipo->obligatorio      = $obligatorio;
        $contipo->idlista          = $request->idlista;
        $contipo->status           = $request->status;
        $idlistado=$request->idlista;
        $contipo->save();
        $atp=$contipo->id;

        $idPosActTipoDatoNew=$request->posicion;
        $contenidotipos = Actividadtipodato::where('tipoactid','=',$request->tipoactuid)
                                           ->where('id','<>',$atp)
                                           ->orderBy('posicion','asc')
                                           ->get();

        foreach ($contenidotipos as $contenidotipo) {
            $idacttpdat=$contenidotipo->id;
            $PosActTipoDato=$contenidotipo->posicion;
            if($PosActTipoDato ==  $idPosActTipoDatoNew){
                $idPosActTipoDatoNew = $idPosActTipoDatoNew + 1;
                $contipo = Actividadtipodato::find($idacttpdat);
                $contipo->posicion=$idPosActTipoDatoNew;
                $contipo->save();
            }
        }

        $addContenidoTipos=Actividad::where('empresauid','=',auth()->user()->uidempresa)
                                    ->where('unidadopuid','=',auth()->user()->selectuniop)
                                    ->where('tipoactividaduid','=',$request->tipoactuid)
                                    ->get();
        $filas=count($addContenidoTipos);

        //dd($filas);
        foreach ($addContenidoTipos as $addContenidoTipo) {
            //dd($addContenidoTipo->id);
            $actividadContenido = Actividadcontenido::create([
                'empresauid'=>auth()->user()->uidempresa,
                'uniopuid'=>auth()->user()->selectuniop,
                'tipoactuid'=>$request->tipoactuid,
                'actividaduid'=>$addContenidoTipo->id,
                'idlista'=>$idlistado,
                'contenidotipoactuid'=>$atp


            ]);

            $tcs=Contenidotipo::where('id','=',$contipo->contenidotipoid)->get();

            foreach ($tcs as $tc){
              if ($tc->tipodato == "documento"){
                //dd("ES UN CAMPO DE TIPO DOCUEMTO");
                $carpeta=Carpeta::create([
                    'empresauid'=>auth()->user()->uidempresa,
                    'actividaduid'=>$addContenidoTipo->id,
                    'carpetanombre'=>''
                ]);
                $ac=$actividadContenido->id;
                $nc=$carpeta->id;
                $actividadContenido2=Actividadcontenido::find($ac);
                $actividadContenido2->valorcarpeta=$nc;
                $actividadContenido2->save();
              }

              if ($tc->tipodato == "actividad"){
                //dd("ES UN CAMPO DE TIPO GRIPO DE ACTIVIDADES");
                $actgrup=Actividadgrupo::create([
                    'empresauid'=>auth()->user()->uidempresa,
                    'unidadopuid'=>auth()->user()->selectuniop,
                    'actividaduid'=>$addContenidoTipo->id,
                    'descrip'=>'',
                    'status'=>'A'

                ]);
                $ac=$actividadContenido->id;
                $nc=$actgrup->id;
                $actividadContenido2=Actividadcontenido::find($ac);
                $actividadContenido2->valorgrupact=$nc;
                $actividadContenido2->save();
              }
            }
        }


        //dd($addContenidoTipo);
        return redirect('tipoact/'.$request->tipoactid.'/edit');

    }

    public function editcontenido(Request $request, $id)
    {
        $request->validate([
            'titulo'   => 'required',
            'posicion' => 'required',
            'status'   => 'required'
        ]);

        if ($request->mostar=="on"){ $mostrar="SI";
        }else{ $mostrar="NO"; }

        if ($request->obligatorio=="on"){ $obligatorio="SI";
        }else{ $obligatorio="NO"; }

        if ($request->status=="I"){ $mostrar="NO"; $obligatorio="NO";}


        $contipo = Actividadtipodato::find($id);
        $posActual=$contipo->posicion;
        $posNueva=$request->posicion;

        $contipo->empresauid       = $request->empresauid;
        $contipo->tipoactid        = $request->tipoactuid;
        $contipo->etiqueta         = $request->titulo;
        $contipo->idlista          = $request->idlista;
        $contipo->posicion         = $request->posicion;
        $contipo->mostrar          = $mostrar;
        $contipo->obligatorio      = $obligatorio;
        $contipo->status           = $request->status;
        $contipo->save();

        $atp=$contipo->id;
        $idPosActTipoDatoNew=$request->posicion;
        $contenidotipos = Actividadtipodato::where('tipoactid','=',$request->tipoactuid)
                                           ->where('id','<>',$atp)
                                           ->orderBy('posicion','asc')
                                           ->get();



        $npos = 0;
        foreach ($contenidotipos as $contenidotipo) {
            $npos = $npos + 1;
            if ($npos == $idPosActTipoDatoNew)
            {
                $npos = $npos + 1;
            }
            $idacttpdat=$contenidotipo->id;
            $contipo = Actividadtipodato::find($idacttpdat);
            $contipo->posicion=$npos;
            $contipo->save();
        }


        $findContenidoTipos=Actividadcontenido::where('empresauid','=',auth()->user()->uidempresa)
                                              ->where('tipoactuid','=',$request->tipoactid)
                                              ->where('contenidotipoactuid','=',$id)
                                              ->get();

        $rowcont=0;
        $rowcont=$findContenidoTipos->count();
        foreach ($findContenidoTipos as $findContenidoTipos) {$rowcont=$rowcont+1;}

        //dd($rowcont);
        if($request->idlista==""){
            $idlista=0;
        }else{
            $idlista=$request->idlista;
            if($rowcont>0){
                $update="UPDATE actividadcontenido SET idlista='".$idlista."' WHERE empresauid LIKE '".$request->empresauid."' AND tipoactuid LIKE '".$request->tipoactid."' AND contenidotipoactuid LIKE '".$id."'";
                $updateContenidoTipos=DB::select($update);
            }
        }


        return redirect('tipoact/'.$request->tipoactid.'/edit');

    }

    public function anularcontenido(Request $request)
    {
        $request->validate([
            'idcont'   => 'required'
        ]);
        $id=$request->idcont;
        $idPosActTipoDatoNew=$request->posicion;
        $contipo = Actividadtipodato::find($request->idcont);
        $contipo->posicion         = 99;
        $contipo->status           = 'I';
        $contipo->save();

        $atp=$contipo->id;
        $idPosActTipoDatoNew = $request->posicion;
        $contenidotipos = Actividadtipodato::where('tipoactid','=',$request->tipoactuid)
                                           ->where('id','<>',$atp)
                                           ->orderBy('posicion','asc')
                                           ->get();

            foreach ($contenidotipos as $contenidotipo) {
                $idacttpdat=$contenidotipo->id;
                $PosActTipoDato=$contenidotipo->posicion;
                if($PosActTipoDato ==  $idPosActTipoDatoNew){
                    $contipo = Actividadtipodato::find($idacttpdat);
                    $contipo->posicion=$idPosActTipoDatoNew;
                    $contipo->save();
                    $idPosActTipoDatoNew = $idPosActTipoDatoNew + 1;
                }
            }
        return redirect('tipoact/'.$request->tipoactid.'/edit');
    }

}
