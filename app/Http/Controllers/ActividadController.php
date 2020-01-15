<?php

namespace App\Http\Controllers;

use App\Jobs\CreateActividadPeriodica;
use App\Jobs\EditActividadPeriodica;

use App\Actividad;
use App\Tipoact;
use App\Unidadop;
use App\Useruniop;
use App\Usertipoact;
use App\Roleuserh;
use App\Actividaduser;
use App\Contenidotipo;  /* Tipo de Datos */
use App\Actividadtipodato; /* tipoDato-tipoActividad */
use App\Actividadcontenido;
use App\ActividadPeriodica;
use App\Actividadgrupo;
use App\Carpeta;
use App\Documentos;
use App\Elemento;
use App\Empresa;
use App\Listado;
use App\User;
use App\Programas;


use Illuminate\Http\Request;
use Carbon\Carbon;
use Image;
use Session;
use Ramsey\Uuid\Uuid;


class ActividadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
      return redirect()->route('home.index');
    } /* FIN INDEX */

    public function create()
    {


    }/* FIN CREATE */

    public function store(Request $request)
    {

        $request->validate([
            'empresauid'=>'required',
            'useruid'=>'required',
            'dateinicio'=>'required',
            'datefin'=>'required'
        ]);

        $fi=$request->dateinicio." ".$request->timeinicio;
        $ff=$request->datefin." ".$request->timefin;

        $actividadI=new Carbon($fi);
        $actividadF=new Carbon($ff);

        $tauid=$request->tipoactividaduid;
        $tipoacts = Tipoact::where("uid","LIKE",$tauid)->get();
        foreach($tipoacts as $tipoact){
          $colorAct=$tipoact->tipoactcolor;
        }
        $codTask=codigoActividad($tauid);
        
        $useruniopsId=auth()->user()->selectuniop;
        $actividadUid= Uuid::uuid4();
        $actividad= new Actividad;
        $actividad->id                = $actividadUid;
        $actividad->empresauid        = $request->empresauid;
        $actividad->useruid           = $request->useruid;
        $actividad->actividadcodigo   = $codTask;
        $actividad->tipoactividaduid  = $request->tipoactividaduid;
        $actividad->actividadtitulo   = $request->actividadtitulo;
        $actividad->actividaddescip   = $request->actividaddescip;
        $actividad->unidadopuid       = $useruniopsId;
        $actividad->actividadinicio   = $actividadI;
        $actividad->actividadfin      = $actividadF;
        $actividad->actividadcolor    = $colorAct;
        $actividad->actividadorigen   = 'WEB';
        $actividad->actividadlugar    = $request->actividadlugar;
        $actividad->programauid       = $request->programa;
        $actividad->actidadbusq       = $actividadUid;
        $actividad->actividadstatus   = "A";


        $actividad->save();
        $fiIB=str_replace('-','',$actividadI);
        $actBusq=$fiIB.';'.$request->dateinicio.';'.$request->timeinicio.';'.$request->actividadlugar.';'.$request->actividaddescip.';'.$request->status;
        $indxBusq=actividadBuesquedaIndex($actividadUid, $actBusq,$actividad->tipoactividaduid);
        $act=Actividad::find($actividadUid);
        $act->actidadbusq = $indxBusq;
        $act->save();
        ///////
        $empresa=numRegEmp();
        //////
        $idactividad=$actividadUid;
        $au=auth()->user()->id;
        $au="alguntexto";

        $actividaduser = Actividaduser::create([
            'empresauid'=>auth()->user()->uidempresa,
            'actividaduid'=>$idactividad,
            'useruid'=>$au,
            'email'=>auth()->user()->email,
            'nombre'=>auth()->user()->name,
            'responsable'=>1
        ]);

        $TipActTipDats=Actividadtipodato::where('tipoactid','=',$tauid)
                                        /*->where('status','LIKE','A')*/
                                        ->get();

        /* crea el contenido de la actividad */
        foreach ($TipActTipDats as $TipActTipDat){
            $atp=$TipActTipDat->id;
            $ct=$TipActTipDat->contenidotipoid;
            $idlista=$TipActTipDat->idlista;

            $actividadContenido = Actividadcontenido::create([
                'empresauid'=>auth()->user()->uidempresa,
                'uniopuid'=>$useruniopsId,
                'tipoactuid'=>$tauid,
                'actividaduid'=>$idactividad,
                'contenidotipoactuid'=>$atp,
                'idlista'=>$idlista
            ]);

            /* Si algun contenido ws de tipo ARCHIVO
               crea un registro en la tabla CARPETAS
             */
            $tcs=Contenidotipo::where('id','=',$ct)->get();
            foreach ($tcs as $tc){
              if ($tc->tipodato == "documento"){
                //dd("ES UN CAMPO DE TIPO DOCUEMTO");
                $carpeta=Carpeta::create([
                    'empresauid'=>auth()->user()->uidempresa,
                    'actividaduid'=>$idactividad,
                    'carpetanombre'=>$request->actividadtitulo
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
                    'actividaduid'=>$idactividad,
                    'descrip'=>$request->actividadtitulo,
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
        if($request->periocidad==0){
            $periocidad=1;
        }else{
        $periocidad  = $request->periocidad;}
        $descrip     = $request->actividaddescip;
        $ubica       = $request->actividadlugar;
        $tipoperiodo = $request->tipoperiodo;
        $program     = $request->programa;
        $finperiodo  = $request->finperiodo." ".$request->timefin;
        $periodofin  = new Carbon($finperiodo);
        //dd($periodofin);
        if ($tipoperiodo!=''){

            $actPeriodica=ActividadPeriodica::create([
                'empresauid'=>auth()->user()->uidempresa,
                'useruid'=>auth()->user()->id,
                'uniopuid'=>$useruniopsId,
                'tipoactuid'=>$tauid,
                'fechai'=>$actividadI,
                'fechaf'=>$periodofin,
                'periocidad'=>$periocidad,
                'tipoperiodo'=>$tipoperiodo,
                'descrip'=>$descrip,
                'ubica'=>$ubica,
                'programauid'=>$program,
                'status'=>'A'
            ]);

            $ap=$actPeriodica->id;
            $act=Actividad::find($idactividad);
            $act->actperiocidauid=$ap;
            $act->save();
            $job = new CreateActividadPeriodica();
            dispatch($job)->delay(now()->addMinutes(1));
        }

        Session::flash('save','Guardar');
        $data['success'] = $actividad;
        $data['idact'] = $idactividad;
        return $data;

    }/* FIN STORE */


    public function edit($id)
    {

        $uidempresa=auth()->user()->uidempresa;
        $userid=auth()->user()->id;
        $useremail=auth()->user()->email;
        $selectuniopId=auth()->user()->selectuniop;
        $destinationPath ='/upload/'.auth()->user()->uidempresa;
        /**** Todas las unidades operativas de una empresa ****/
        $uniops = Unidadop::where("empresauid","LIKE",$uidempresa)
                         ->where("unidadopstatus","LIKE","A")->get();
                         foreach ($uniops as $uniop) {
                          if($uniop->unidadopuid==$selectuniopId){
                              $direccion= $uniop->unidadopnombre;
                          }

                      }
        /**** Todas los Tipo de Actividades de una empresa ****/
        $tipoacts = Tipoact::where("status","LIKE","A")
                          ->where("empresauid","LIKE",$uidempresa)
                          ->orderBy("titulo","asc")
                          ->get();

        /**** Todas las unidades operativas a las que pertenece un usuario ****/
        $useruniops = Useruniop::where("user_id","LIKE",$userid)->get();

        foreach ($useruniops as $useruniop) {
            $useruniopsId=$useruniop->unidadopuid;
        }

        /**** Todas las unidades operativas a las que pertenece un usuario ****/
        $usertipoacts =Usertipoact::where("user_id","LIKE",$userid)->get();


         $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                             ->where("user_id","LIKE",$userid)
                             ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                             ->get();
        foreach ($rolesActivo as $rolActivo) {
          $valor= $rolActivo->slug;
        }

        $actividadUsers=Actividaduser::where('actividaduid','=',$id)
                                     ->where('empresauid','=',$uidempresa)
                                     ->get();



        $colUsers=User::where('uidempresa','LIKE',$uidempresa)
                      ->orderby('name')->get();


        $selectuniopId=auth()->user()->selectuniop;
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$selectuniopId)
                            ->where('status','=','A')
                            ->get();
        $programasAll=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$selectuniopId)
                            ->get();
        //dd($actividadUser);

        /*$actividades = Actividad::where('id','LIKE',$id)
                              ->where('empresauid','LIKE',$uidempresa)
                              ->get();*/
        $actividades = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                ->where("actividads.empresauid","=",auth()->user()->uidempresa)
                                ->where("actividads.id","=",$id)
                                ->select("actividads.id","actividads.actividadcodigo","actividads.actividadinicio","actividads.actividaddescip","actividads.actividadfin","actividads.actividadstatus","actividads.actividadlugar","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.comporta", "tipoacts.tipoactdescrip")
                                ->get();

        foreach ($actividades as $actividad) {
          $db= new Carbon($actividad->actividadinicio);
          $de= new Carbon($actividad->actividadfin);

          $datebegin = $db->toDateString();
          $datebegin = $db->format('d-m-Y');
          $timebegin2 = $db->toTimeString();
          $timebegin2 = $db->format('H:i');
         // $timebegin = $db->toTimeString();
          $timebegin = $actividad->actividadinicio; //$db->format('H:i');
          $dateend = $de->toDateString();
          $dateend = $de->format('d-m-Y');
          $timeend = $de->toTimeString();
          $timeend = $de->format('H:i');
          //dd($timebegin);
          $idtpact=$actividad->tipoactividaduid;
          $status=$actividad->actividadstatus;
          $idactPer=$actividad->actperiocidauid;
          $idparentAct=$actividad->actividadgrupouid;
        }/** FIN DE ACTIVIDAD */

        $dataActPerio = ActividadPeriodica::where('id','=',$idactPer)->get();
        $rowContAP=$dataActPerio->count();

        foreach ($dataActPerio as $dap) {
            $date183 = new Carbon($dap->fechaf);
            $dapff = $date183->format('d-m-Y');
            $periocidad=$dap->periocidad;
            $tipop=$dap->tipoperiodo;
            $statusap=$dap->status;
        }
        if($rowContAP==0){
            $dapff="";
            //$dapff=$dapff->format('d-m-Y');
            $periocidad=0;
            $tipop='x';
            $statusap=0;
        }
        $tipoactividads = Tipoact::where("status","=","A")
                                 ->where("empresauid","=",$uidempresa)->get();

        $contenidos = Actividadcontenido::join("actividadtipocontenido","actividadtipocontenido.id","=","actividadcontenido.contenidotipoactuid")
                                        ->join("contenidotipo","contenidotipo.id","=","actividadtipocontenido.contenidotipoid")
                                        ->where('actividaduid','=',$id)
                                        ->where('actividadcontenido.empresauid','LIKE',$uidempresa)
                                        ->where('status','=',"A")
                                        ->select('actividadcontenido.id','actividadcontenido.uniopuid','actividadcontenido.actividaduid','actividadcontenido.contenidotipoactuid','actividadcontenido.valortexto','actividadcontenido.valornumero','actividadcontenido.valorfecha','actividadcontenido.valorcarpeta','actividadcontenido.valorlista','actividadcontenido.idlista','actividadtipocontenido.etiqueta','actividadtipocontenido.posicion','contenidotipo.tipodato','actividadtipocontenido.posicion','actividadtipocontenido.updated_at')
                                        ->orderBy("actividadtipocontenido.posicion","asc")
                                        ->orderBy("actividadtipocontenido.updated_at","asc")
                                        ->get();
        $rowCont = $contenidos->count();
        $documentos=Documentos::where("actividaduid","=",$id)
                              ->where("empresauid","=",$uidempresa)
                              ->orderBy('extension','desc')
                              ->get();

        $listados=Listado::where("empresauid","=",$uidempresa)
                              ->where("status","=","A")
                              ->get();
        $elementos=Elemento::where("empresauid","=",$uidempresa)
                                ->where("status","=","A")
                                ->orderBy("elempos")
                                ->get();

        $useruniopsId=$selectuniopId;

        $actividadesItmen = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)//filtro de version multiempresa
                                ->where("actividads.actividadgrupouid","=",$id)
                                ->select("actividads.id","actividads.actividadinicio","actividads.actividaddescip","actividads.actividadfin","actividads.actividadstatus","actividads.actividadlugar","tipoacts.tipoactcolor","tipoacts.titulo")
                                ->get();

        $actividadesParent = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)//filtro de version multiempresa
                                ->where("actividads.id","=",$idparentAct)
                                ->select("actividads.id","actividads.actividadinicio","actividads.actividaddescip","actividads.actividadfin","actividads.actividadstatus","actividads.actividadlugar","tipoacts.tipoactcolor","tipoacts.titulo")
                                ->get();


        $rolActiviadUser=Actividaduser::where('empresauid','=',auth()->user()->uidempresa)
                                      ->where('actividaduid','=',$id)
                                      ->where('email','=',auth()->user()->email)
                                      ->get();
        $rolUsuario=0;
        foreach ($rolActiviadUser as $rolActiviadUse) {
            $rolUsuario=$rolActiviadUse->responsable;
        }

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        //dd($rolUsuario);
        if($status=="A") {
            if($rolUsuario==1){
                return view('actividad.edit',compact('id','notificVencidas','notificDay','rowCont','actividad','tipoactividads','valor','datebegin','timebegin','timebegin2','dateend','timeend','tipoacts','useruniops','usertipoacts','uniops','direccion','useruniopsId','actividadUsers','usuarios','contenidos','documentos','destinationPath','elementos','programas','dapff','periocidad','tipop','statusap','rowContAP','programasAll','actividadesItmen','actividadesParent','menuDashboard','colUsers','listados')); /*en compact va la variable de las vista*/
            }if($rolUsuario==2){
                return view('actividad.sredit',compact('id','notificVencidas','notificDay','rowCont','actividad','tipoactividads','valor','datebegin','timebegin','timebegin2','dateend','timeend','tipoacts','useruniops','usertipoacts','uniops','direccion','useruniopsId','actividadUsers','usuarios','contenidos','documentos','destinationPath','elementos','programas','dapff','periocidad','tipop','statusap','rowContAP','programasAll','actividadesItmen','actividadesParent','menuDashboard','colUsers')); /*en compact va la variable de las vista*/
            }if(($rolUsuario==3) or ($rolUsuario==0)){
                return view('actividad.roedit',compact('id','notificVencidas','notificDay','rowCont','actividad','tipoactividads','valor','datebegin','timebegin','timebegin2','dateend','timeend','tipoacts','useruniops','usertipoacts','uniops','direccion','useruniopsId','actividadUsers','usuarios','contenidos','documentos','destinationPath','elementos','programas','dapff','periocidad','tipop','statusap','rowContAP','programasAll','actividadesItmen','actividadesParent','menuDashboard','colUsers')); /*en compact va la variable de las vista*/
            }
        }else{
            if($rolUsuario==1){
                return view('actividad.edit',compact('id','notificVencidas','notificDay','rowCont','actividad','tipoactividads','valor','datebegin','timebegin','timebegin2','dateend','timeend','tipoacts','useruniops','usertipoacts','uniops','direccion','useruniopsId','actividadUsers','usuarios','contenidos','documentos','destinationPath','elementos','programas','dapff','periocidad','tipop','statusap','rowContAP','programasAll','actividadesItmen','actividadesParent','menuDashboard','colUsers'));
            }else{
                return view('actividad.roedit',compact('id','notificVencidas','notificDay','rowCont','actividad','tipoactividads','valor','datebegin','timebegin','timebegin2','dateend','timeend','tipoacts','useruniops','usertipoacts','uniops','direccion','useruniopsId','actividadUsers','usuarios','contenidos','documentos','destinationPath','elementos','programas','dapff','periocidad','tipop','statusap','rowContAP','programasAll','actividadesItmen','actividadesParent','menuDashboard','colUsers'));
            }
            //return view('actividad.roedit',compact('id','notificVencidas','notificDay','rowCont','actividad','tipoactividads','valor','datebegin','timebegin','timebegin2','dateend','timeend','tipoacts','useruniops','usertipoacts','uniops','direccion','useruniopsId','actividadUsers','usuarios','contenidos','documentos','destinationPath','elementos','programas','dapff','periocidad','tipop','statusap','rowContAP','programasAll','actividadesItmen','actividadesParent','menuDashboard','colUsers'));
        }

    }


    public function updateActividad(Request $request)
    {

      $id=$request->get('id');
      $valores=$request->get('valortext');
      $idContenido=$request->get('registro');
      $tipo=$request->get('tipodato');
      $i=0;$j=0;
      $j=count($idContenido);
      $msg[]=['tp'=>'','id'=>'','valor'=>'','stado'=>''];
      for($i=0; $i < $j; $i++) {
        $id=$idContenido[$i];
        $contenido=Actividadcontenido::find($id);

        if ($tipo[$i]=="text") { $contenido->valortexto=$valores[$i]; }

        if ($tipo[$i]=="numeric"){ $contenido->valornumero=$valores[$i]; }

        if ($tipo[$i]=="textarea"){ $contenido->valortexto=$valores[$i]; }

        if ($tipo[$i]=="date"){
          $dtx= new Carbon($valores[$i]);
          $contenido->valorfecha=$dtx;
        }

        if ($tipo[$i]=="monto"){ $contenido->valornumero=$valores[$i]; }

        if ($tipo[$i]=="lista"){ $contenido->valorlista=$valores[$i]; }
        $saved=$contenido->save();
        $msg[]=['tp'=>$tipo[$i],
                'id'=>$id,
                'valor'=>$valores[$i],
                'stado'=>$saved
            ];

      }
      Session::flash('save','el Contenido de la Actividad fue EDITADA Exitosamente');

      $data['success'] = true;
      $data['msg'] = $msg;
      return $data;
    }/* FIN UPDATE */


    public function editdetails(Request $request)
    {
        $cierre=0;
        $id=$request->idactividaddt;

        $fi=$request->dateinicio." ".$request->timeinicio2;
        $ff=$request->datefin." ".$request->timefin2;

        $actividadI=new Carbon($fi);
        $actividadF=new Carbon($ff);

        $actividad = Actividad::find($id);
        $fiIB=str_replace('-','',$actividadI);
        $actBusq=$fiIB.';'.$request->dateinicio.';'.$request->timeinicio2.';'.$request->place.';'.$request->actividaddescipdetale.';'.$request->status;
        $actividad->actividadlugar    = $request->place;
        $actividad->actividaddescip   = $request->actividaddescipdetale;
        $actividad->actividadinicio   = $actividadI;
        $actividad->actividadfin      = $actividadF;
        $actividad->useruid           = auth()->user()->id;
        $actividad->actividadstatus   = $request->status;


        if($request->status=='C'){
            $cierre=validarCierre($id);
        }

        if($cierre > 0){
            $data['headtask'] = "FALLO";
            $data['cerrar'] = false;
            return $data;
        }else{
            if($saved=$actividad->save()){
                /************************************/
                $indxBusq=actividadBuesquedaIndex($id, $actBusq,$actividad->tipoactividaduid);
                /***************************************/
                if($request->status=='A'){ $statusclass='badge label-primary'; $statustext='Abierta'; }
                elseif($request->status=='X'){ $statusclass='badge label-default'; $statustext='Cancelada'; }
                elseif($request->status=='C'){ $statusclass='badge label-success'; $statustext='Cerrada'; }
                $data['statusclass']=$statusclass;
                $data['statustext']=$statustext;
                $data['descrip'] = $request->actividaddescipdetale;
                $data['headtask'] = "GUARDO";
                $data['uidta']=$actividad->tipoactividaduid;
                $data['cerrar'] = true;
                $data['indexbusq'] = $indxBusq;
                return $data;
            }else{
                $data['headtask'] = "FALLO";
                return $data;
            }
        }

    }/* FIN DESTROY */

    public function adduser(Request $request)
    {
       // dd($request->useruid);
      $usuario=User::where('uidempresa','=',auth()->user()->uidempresa)
                   ->where('id','=',$request->useruid)
                   ->get();

      $rowcont=$usuario->count();
      if($rowcont==0){
        if($request->resp==4){
            if(empty($request->nombreinvitado) AND empty($request->emailinvitado)){
                $useruid='';
                $nombreinvitado='';
                $emailinvitado='';
            }else{
            $useruid=$request->useruid;
            $nombreinvitado=$request->nombreinvitado;
            $emailinvitado=$request->emailinvitado;

            //dd("nombreinvitado: ".$nombreinvitado ."  ####  emailinvitado: " .$emailinvitado);
            }
        }
      }else{
          foreach ($usuario as $u) {
            $useruid=$u->id;
            $nombreinvitado=$u->name;
            $emailinvitado=$u->email;
          }
      }

      $addUser = new Actividaduser();
      $addUser->empresauid   = auth()->user()->uidempresa;
      $addUser->actividaduid = $request->idactividad;
      $addUser->useruid      = $useruid;
      $addUser->nombre       = $nombreinvitado;
      $addUser->email        = $emailinvitado;
      $addUser->responsable  = $request->resp;
      $saved=$addUser->save();
      $tk=csrf_token();
      if($request->resp==1){
        $r='<span class="label label-success pull-right""> Responsable </span>';
      }elseif($request->resp==2){
        $r='<span class="label label-info pull-right"> Editor </span>';
      }elseif($request->resp==3){
        $r='<span class="label label-default pull-right"> Participante</span>';
      }elseif($request->resp==4){
        $r='<span class="label label-warning pull-right"> Participante Externo</span>';
      }
      $freninv='<form action="actividad/remuser" autocomplete="off" method="POST" id="formRemoveParticipante'.$addUser->id.'">
      <input type="hidden" name="_token" value="'.$tk.'">
      <input type="hidden" name="idactividad" value="'.$request->idactividad.'">
      <input type="hidden" name="iduser" value="'.$addUser->id.'">
        <button type="button" id_cont="'.$addUser->id.'" class="btn-xs btn-danger btn-delete"><i class="fa fa-close"></i></button>
        </form>';
      $datos='<tr id="part_'.$addUser->id.'"><td>'.$nombreinvitado.' - '.$emailinvitado.$r.'</td><td>'.$freninv.'</td></tr>';
      $data['success'] = $saved;
      $data['path'] = $datos;
      return $data;


    }

    public function remuser(Request $request)
    {
      $idact=$request->idactividad;
      $email=$request->iduser;
      $datos='USER:'.$email.'   uidActividad: '.$idact;

      $user = Actividaduser::where('id','=',$email)
                           ->where('actividaduid','=',$idact)
                           ->where('empresauid','=',auth()->user()->uidempresa)->delete();

      $data['success'] = $user;
      $data['path'] = $datos;
      return $data;

     /* $user = Actividaduser::find($id);
      $user->delete();
      return redirect('actividad/'.$idact.'/edit');*/
    }

    public function editactividadper(Request $request)
    {

        $id=$request->idactividaddt;
        $vareditacyper = $request->textoptionsEdit;
        $uideditacyper = $request->actperiocidauid;
        $fi=$request->dateinicio." ".$request->timeinicio2;
        $ff=$request->datefin." ".$request->timefin2;
        $actividadI=new Carbon($fi);
        $actividadF=new Carbon($ff);

        $actividad = Actividad::find($id);
        $actividad->actividadlugar    = $request->place;
        $actividad->actividaddescip   = $request->actividaddescipdetale;
        $actividad->actividadinicio   = $actividadI;
        $actividad->actividadfin      = $actividadF;
        $actividad->useruid           = auth()->user()->id;
        $actividad->actividadstatus   = $request->status;
        $actividad->programauid       = $request->programa;
        $actividad->actividadlugar    = $request->place;
        $saved=$actividad->save();

        $uidActPer=$actividad->actperiocidauid;
        if($uidActPer==""){$actper="NO"; }else{$actper="SI";}

        if($request->status=='A'){
            $statusclass='badge label-primary';
            $statustext='Abierta';
        }elseif($request->status=='X'){
            $statusclass='badge label-default';
            $statustext='Cancelada';
        }elseif($request->status=='C'){
            $statusclass='badge label-success';
            $statustext='Cerrada';
        }


        $idactividad=$actividad->id;
        $tauid=$actividad->tipoactividaduid;
        if($request->periocidad==0){
            $periocidad=1;
        }else{
        $periocidad  = $request->periocidad;}
        $descrip     = $request->actividaddescip;
        $ubica       = $request->place;
        $tipoperiodo = $request->tipoperiodo;
        $program     = $request->programa;
        if($request->finperiodo!=""){
            $finperiodo  = $request->finperiodo." ".$request->timefin2;
            $periodofin=new Carbon($finperiodo);
        }else{$periodofin = "";}

        if($uideditacyper==""){

            $actPeriodica=ActividadPeriodica::create([
                'empresauid'=>auth()->user()->uidempresa,
                'useruid'=>auth()->user()->id,
                'uniopuid'=>auth()->user()->selectuniop,
                'tipoactuid'=>$tauid,
                'fechai'=>$actividadI,
                'fechaf'=>$periodofin,
                'periocidad'=>$periocidad,
                'tipoperiodo'=>$tipoperiodo,
                'descrip'=>$descrip,
                'ubica'=>$ubica,
                'programauid'=>$program,
                'status'=>'A'
            ]);
            //dd('ActividadPeriodicaUID=> '.$uideditacyper);
            $msg="<b>ID PROGRAMA: </b>".$program."<br /><b>ACTUAL_P:</b><br /> <b>ActividadPeriodicaUID=></b>".$uideditacyper;//.$vareditacyper."<br /><b>UID Periodo: </b>".$uideditacyper;
            //return $msg;
            $ap=$actPeriodica->id;
            $act=Actividad::find($idactividad);
            $act->actperiocidauid=$ap;
            $act->save();
            $job = new CreateActividadPeriodica();
            dispatch($job)->delay(now()->addMinutes(1));
        }else{

            $actPeriodica=ActividadPeriodica::find($uideditacyper);
            $actPeriodica->fechai      = $actividadI;
            $actPeriodica->fechaf      = $periodofin;
            $actPeriodica->periocidad  = $periocidad;
            $actPeriodica->tipoperiodo = $tipoperiodo;
            $actPeriodica->programauid = $program;
            $actPeriodica->ubica       = $ubica;
            $actPeriodica->descrip     = $descrip;
            $actPeriodica->status     = $request->status;

            $actPeriodica->save();

            $job = new EditActividadPeriodica();
            dispatch($job)->delay(now()->addMinutes(1));
        }

        Session::flash('save','Los Registro fue Editaron Exitosamente');
        //return redirect('actividad/'.$id.'/edit');
        $data['ncont']=$request->ncontenido;
        $data['isAP']=$actper;
        $data['actual']=$vareditacyper;
        $data['statusclass']=$statusclass;
        $data['statustext']=$statustext;
        $data['descrip'] = $request->actividaddescipdetale;
        $data['success'] = $saved;
        return $data;


    }

    public function addfiles(Request $request)
    {
      $empresauid=auth()->user()->uidempresa;
      $idActividad=$request->idActidadDoc;
      $idcarpetaDoc=$request->idcarpetaDoc;
      $registroDoc=$request->registroDoc;
      $tipodatoDoc=$request->tipodatoDoc;
      $file = $request->file('valorfile');
      $imagenOriginal = $request->file('valorfile');

      $destinationPath = public_path().'/upload/'.auth()->user()->uidempresa.'/';
      $dateFile = date('YmdHis');

      $fileOriginal = $file->getClientOriginalName();
      $fileExtension = $file->getClientOriginalExtension();

      if(isset($file)) {
          $filename = $dateFile."&".$idActividad."&". $fileOriginal;



          if (($fileExtension=="png") or ($fileExtension=="jpg") or ($fileExtension=="gif")){
            if (isset($imagenOriginal)){

              $tmp="thumb_". $filename;
              $imagen = Image::make($imagenOriginal);
              $hfile= $imagen->height();
              $wfile= $imagen->width();

              $wx=intval((33000/$wfile)); /** Ancho costante de 330px **/
              $hx=intval((($hfile*$wx)/100));

              //dd("Ancho =>".$wfile." - Ancho % =>".$wx ." - Alto =>".$hfile." - Alto % =>".$hx);
              $imagen->resize(330,$hx);
              $temp_name = $tmp . '.' . $imagenOriginal->getClientOriginalExtension();
              $imagen->save($destinationPath . $temp_name);

              }

          }else{
            $temp_name="null";
          }

          $upload_success = $file->move($destinationPath, $filename);
          $saved = Documentos::create([
                              'empresauid'=>$empresauid,
                              'actividaduid'=>$idActividad,
                              'carpetauid'=>$idcarpetaDoc,
                              'contenidouid'=>$registroDoc,
                              'nombre'=>$fileOriginal,
                              'nombrefisico'=>$filename,
                              'thumbnails'=>$temp_name,
                              'extension'=>$fileExtension,
                              'publico'=>"S",
                              'status'=>"A"
                          ]);

      }

      $datos="";
      $get=url("upload");
      $path=$get."/".$empresauid;

        if (($fileExtension=="png") or ($fileExtension=="jpg") or ($fileExtension=="gif")){

          $datos = $datos .'<li>
                      <span class="mailbox-attachment-icon"><img src="'.$path.'/'.$temp_name.'" width="75px" height="75px" class="group'.$registroDoc.'"></span>
                      <div class="mailbox-attachment-info">
                        <a href="'.$path.'/'.$filename.'" class="mailbox-attachment-name" >'.$fileOriginal.' </a>
                        <span class="mailbox-attachment-size">
                            <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                      </div>
                    </li>
                    <li class="visible-print-block">
                        <img src="'.$path.'/'.$temp_name.'" >
                    </li>';
        }elseif (($fileExtension=="doc") or ($fileExtension=="docx")){

          $datos = $datos .'<li>
                      <span class="mailbox-attachment-icon"><a href="'.$path.'/'.$filename.'" target="_blank"><i class="fa fa-file-word-o"></i></a></span>
                      <div class="mailbox-attachment-info">
                        <a href="'.$path.'/'.$filename.'" class="mailbox-attachment-name" target="_blank">'.$fileOriginal.' </a>
                        <span class="mailbox-attachment-size">
                            <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                      </div>
                    </li>';
        }elseif (($fileExtension=="xls") or ($fileExtension=="xlsx") or ($fileExtension=="cvs")){

          $datos=$datos .'<li>
                      <span class="mailbox-attachment-icon"><a href="'.$path.'/'.$filename.'" target="_blank"><i class="fa fa-file-excel-o"></i></a></span>
                      <div class="mailbox-attachment-info">
                        <a href="'.$path.'/'.$filename.'" class="mailbox-attachment-name" target="_blank">'.$fileOriginal.' </a>
                        <span class="mailbox-attachment-size">
                            <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                      </div>
                    </li>';
        }elseif (($fileExtension=="mp3") or ($fileExtension=="ogg")){

          $datos=$datos .'<li>
                      <span class="mailbox-attachment-icon"><a href="'.$path.'/'.$filename.'" target="_blank"><i class="fa fa-file-audio-o"></i></a></span>
                      <div class="mailbox-attachment-info">
                        <a href="'.$path.'/'.$filename.'" class="mailbox-attachment-name" target="_blank">'.$fileOriginal.' </a>
                        <span class="mailbox-attachment-size">
                            <a href="#" target="_blank" class="pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                      </div>
                    </li>';
        }elseif (($fileExtension=="avi") or ($fileExtension=="mpg") or ($fileExtension=="mpeg") or ($fileExtension=="mp4")){

          $datos=$datos .'<li>
                      <span class="mailbox-attachment-icon"><a href="'.$path.'/'.$filename.'" target="_blank"><i class="fa fa-file-movie-o"></i></a></span>
                      <div class="mailbox-attachment-info">
                        <a href="'.$path.'/'.$filename.'" class="mailbox-attachment-name" target="_blank">'.$fileOriginal.'</a>
                        <span class="mailbox-attachment-size">
                            <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                      </div>
                    </li>';
        }elseif ($fileExtension=="pdf"){

          $datos=$datos .'<li>
                      <span class="mailbox-attachment-icon"><a href="'.$path.'/'.$filename.'" target="_blank"><i class="fa fa-file-pdf-o"></i></a></span>
                      <div class="mailbox-attachment-info">
                        <a href="'.$path.'/'.$filename.'" class="mailbox-attachment-name" target="_blank"> '.$fileOriginal.'</a>
                        <span class="mailbox-attachment-size">
                            <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                      </div>
                    </li>';
        }elseif ($fileExtension=="txt"){
          $datos=$datos .'<li>
                      <span class="mailbox-attachment-icon"><a href="'.$path.'/'.$filename.'" target="_blank"><i class="fa fa-file-text-o"></i></a></span>
                      <div class="mailbox-attachment-info">
                        <a href="'.$path.'/'.$filename.'" class="mailbox-attachment-name" target="_blank">'.$fileOriginal.'</a>
                        <span class="mailbox-attachment-size">
                            <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                      </div>
                    </li>';
      }else{

        $datos=$datos .'<li><span class="mailbox-attachment-icon"><a href="'.$path.'/'.$filename.'" target="_blank"><i class="fa fa-file-archive-o"></i></a></span>
                            <div class="mailbox-attachment-info">
                              <a href="'.$path.'/'.$filename.'" class="mailbox-attachment-name" target="_blank">'.$fileOriginal.'</a>
                              <span class="mailbox-attachment-size">
                                <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
                              </span>
                            </div>
                        </li>';
        }
     /** } **/
      //$ruta=asset('/upload/avatar'.$temp_name);
            $data['success'] = $saved;
            $data['path'] = $datos;
            //dd($datos);
            return $data;


    }

    public function storeactgrupo(Request $request)
    {

        $request->validate([
            'empresauid'=>'required',
            'useruid'=>'required',
            'dateinicio'=>'required',
            'datefin'=>'required'
        ]);



        $fi=$request->dateinicio." ".$request->timeinicio;
        $ff=$request->datefin." ".$request->timefin;

        $actividadI=new Carbon($fi);
        $actividadF=new Carbon($ff);

        $tauid=$request->tipoactividaduid;
        $tipoacts = Tipoact::where("uid","LIKE",$tauid)->get();
        foreach($tipoacts as $tipoact){
          $colorAct=$tipoact->tipoactcolor;
          $titulo=$tipoact->titulo;
        }

        $useruniopsId=auth()->user()->selectuniop;

        $fiIB=str_replace('-','',$actividadI);
        $actBusq=$fiIB.';'.$request->dateinicio.';'.$request->timeinicio.';'.$request->actividadlugar.';'.$request->actividaddescip.';A';
        $codTask=codigoActividad($tauid);
        $actividadUid= Uuid::uuid4();
        $actividad= new Actividad;

        $actividad->id                = $actividadUid;
        $actividad->empresauid        = $request->empresauid;
        $actividad->useruid           = $request->useruid;
        $actividad->tipoactividaduid  = $request->tipoactividaduid;
        $actividad->actividadtitulo   = $request->actividadtitulo;
        $actividad->actividaddescip   = $request->actividaddescip;
        $actividad->unidadopuid       = $useruniopsId;
        $actividad->actividadinicio   = $actividadI;
        $actividad->actividadfin      = $actividadF;
        $actividad->actividadcolor    = $colorAct;
        $actividad->actividadlugar    = $request->actividadlugar;
        $actividad->programauid       = $request->programa;
        $actividad->actividadgrupouid = $request->actgrupuid;
        $actividad->actidadbusq       = $actBusq;
        $actividad->actividadcodigo   = $codTask;
        $actividad->actividadstatus   = "A";

        $saved=$actividad->save();
        $empresa=numRegEmp();
        $idactividad=$actividad->id;

        $actiidaduser = Actividaduser::create([
            'empresauid'=>auth()->user()->uidempresa,
            'actividaduid'=>$idactividad,
            'email'=>auth()->user()->email,
            'responsable'=>1,
        ]);

        $TipActTipDats=Actividadtipodato::where('tipoactid','LIKE',$tauid)
                                       ->where('status','LIKE','A')
                                       ->get();

        /* crea el contenido de la actividad */
        foreach ($TipActTipDats as $TipActTipDat){
            $atp=$TipActTipDat->id;
            $ct=$TipActTipDat->contenidotipoid;
            $idlista=$TipActTipDat->idlista;

            $actividadContenido = Actividadcontenido::create([
                'empresauid'=>auth()->user()->uidempresa,
                'uniopuid'=>$useruniopsId,
                'tipoactuid'=>$tauid,
                'actividaduid'=>$idactividad,
                'contenidotipoactuid'=>$atp,
                'idlista'=>$idlista
            ]);

            /* Si algun contenido ws de tipo ARCHIVO
               crea un registro en la tabla CARPETAS
             */
            $tcs=Contenidotipo::where('id','=',$ct)->get();
            foreach ($tcs as $tc){
              if ($tc->tipodato == "documento"){
                //dd("ES UN CAMPO DE TIPO DOCUEMTO");
                $carpeta=Carpeta::create([
                    'empresauid'=>auth()->user()->uidempresa,
                    'actividaduid'=>$idactividad,
                    'carpetanombre'=>$request->actividadtitulo
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
                    'actividaduid'=>$idactividad,
                    'descrip'=>$request->actividadtitulo,
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


        $datos='';
        $datos = '<div class="col-md-4 col-sm-6 col-xs-12"
                    <div class="info-box2">
                        <span class="info-box2-icon" style="background-color:'.$colorAct.';">&nbsp;</span>
                        <div class="info-box2-content"><span class="badge label2-primary pull-right">&nbsp;Abierta&nbsp;</span>
                        <span class="info-box2-title">'.$titulo.'</span>
                        <span class="info-box2-text">'.$request->dateinicio." ".$request->timeinicio.'</span>
                        <span class="info-box2-text">'.$request->actividaddescip.'&nbsp;</span>
                        <a href="/actividad/'.$idactividad.'/edit" class="small-box-footer" > ver mas <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>';

        if( $actividad->save() ){
            $data['success'] = $saved ;
            $data['path'] = $datos;
            return $data;
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }
    }/* FIN STORE */

    /**
     * Search user from database according to search term
     * @param  Request $request [description]
     * @return json return json formated result
     */
    public function searchuser(Request $request)
    {
        $query =$request->get('query','');
        $usuarios="";

        if ( ! empty($query)) {
            // search user by name or email
            $usersAll = User::orwhere('name','LIKE','%'.$query.'%')
                            ->orwhere('email','LIKE','%'.$query.'%')
                            ->select('id','name','email','uidempresa')
                            /*->orwhere('email','LIKE','%'.$query.'%')*/
                            ->get();
            $users=$usersAll->where('uidempresa', '=', auth()->user()->uidempresa);
            $users->all();
        }

        return response()->json($users);
    }

    public function searchTask(Request $request)
    {
        $query = $request->input('search.value');
        $totalData=0;$totalFiltered=0;
        $tasks[]=['actividadinicio' => '','titulo'=>'','actividaddescip'=>'','actividadlugar'=>'','actividadstatus'=>'','add'=>''];
        $tasksAll=['draw' =>0,'recordsTotal'=>0,'recordsFiltered' =>0,'data'=>$tasks];
        $length = strlen(trim(utf8_decode($query)));
        if($length >= 3){


            if (!empty($query)) {
                $taskAll=Actividad::where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)->get();

                $taskFiltered = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                    ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                    ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)//filtro de version multiempresa
                                    ->where('actividads.actidadbusq','LIKE','%'.$query.'%')
                                    ->orderBy("actividads.actividadinicio","asc")
                                    ->select("actividads.id","actividads.actividaddescip","actividads.actividadlugar","actividads.actividadinicio","actividads.actividadfin","actividads.actividadstatus","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.id AS tipoactsid","actividads.actidadbusq")
                                    ->get();

                $totalData = $taskAll->count();
                $totalFiltered = $taskFiltered->count();
                foreach($taskFiltered as $tall){
                    if ($tall->actividadstatus == "A"){ $status='<span class="label label-primary">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>';}
                    elseif ($tall->actividadstatus == "I"){$status='<span class="label label-primary">&nbsp;&nbsp;Inactivo&nbsp;&nbsp;</span>';}

                    $add='<a  onclick="AsociaAct('."'".$tall->id."'".')" class="badge bg-aqua idasoc"><i class="fa fa-edit"></i></a>';
                    //AsociaAct(xAsoc_uid)
                    $tasks[]=['actividadinicio' => $tall->actividadinicio,
                            'titulo'=>$tall->titulo,
                            'actividaddescip'=>$tall->actividaddescip,
                            'actividadlugar'=>$tall->actividadlugar,
                            'actividadstatus'=>$status,
                            'add'=>$add];
                }
            }/* fin del if !empty($query) */
        }/* fin del if del $length*/
        $tasksAll =['draw' => intval($request->input('draw')),
                    'recordsTotal'    => intval($totalData),
                    'recordsFiltered' => intval($totalFiltered),
                    'data'=>$tasks];

        return response()->json($tasksAll);
    }

    public function buscarTask(){

        return view('actividad.buscaractividad');
    }

    public function asociartask(Request $request){

        $taskFiltered = Actividad::find($request->act_uid);
        $taskFiltered->actividadgrupouid=($request->padre_uid);
        if( $saved=$taskFiltered->save() ){
            $db= new Carbon($taskFiltered->actividadinicio);
            $datebegin = $db->toDateString();
            $datebegin = $db->format('d-m-Y H:i');

            $tipoacts = Tipoact::where("uid","LIKE",$taskFiltered->tipoactividaduid)->get();
            foreach($tipoacts as $tipoact){
            $colorAct=$tipoact->tipoactcolor;
            $titulo=$tipoact->titulo;
            }
            $datos = '<div class="col-md-4 col-sm-6 col-xs-12"
                    <div class="info-box2">
                        <span class="info-box2-icon" style="background-color:'.$colorAct.';">&nbsp;</span>
                        <div class="info-box2-content"><span class="badge label2-primary pull-right">&nbsp;Abierta&nbsp;</span>
                        <span class="info-box2-title">'.$titulo.'</span>
                        <span class="info-box2-text">'.$datebegin.'</span>
                        <span class="info-box2-text">'.$taskFiltered->actividaddescip.'&nbsp;</span>
                        <a href="/actividad/'.$request->act_uid.'/edit" class="small-box-footer" > ver mas <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>';
            $data['path'] = $datos;
            $data['success'] = $saved ;
            return $data;
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }


    }

}/*FIN DE LA CLASE*/
