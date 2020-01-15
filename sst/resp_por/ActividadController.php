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
use App\Carpeta;
use App\Documentos;
use App\Elemento;
use App\User;
use App\ActividadPeriodica;
use App\Programas;
use App\Actividadgrupo;
use App\Dashboard;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Image;
use Session;
use Illuminate\Support\Facades\Redirect;
/*
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\User;
use Caffeinated\Shinobi\Facades\Shinobi;
use Caffeinated\Shinobi\Models\Role;
use App\Grupotipoact;
use App\Useracividad;
use App\Listado;
use DB;
*/


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

        $useruniopsId=auth()->user()->selectuniop;

        $actividad= new Actividad;
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

        $actividad->actividadstatus   = "A";

        $actividad->save();
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
             * crea un registro en la tabla CARPETAS
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

        if( $actividad->save() ){
            Session::flash('save','Guardar');
            return redirect()->route('actividad.edit',[$idactividad]);
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }
    }/* FIN STORE */

    public function show($id)
    {

    }/* FIN SHOW */

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

        $actividadUsers=Actividaduser::where('actividaduid','LIKE',$id)
                                     ->where('empresauid','LIKE',$uidempresa)
                                     ->get();



        $usuarios=User::where('uidempresa','LIKE',$uidempresa)->get();
        $selectuniopId=auth()->user()->selectuniop;
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$selectuniopId)
                            ->where('status','=','A')
                            ->get();
        $programasAll=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$selectuniopId)
                            ->get();
        //dd($actividadUser);

        $actividades = Actividad::where('id','LIKE',$id)
                              ->where('empresauid','LIKE',$uidempresa)
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
        }
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
        $tipoactividads = Tipoact::where("status","LIKE","A")
                                 ->where("empresauid","LIKE",$uidempresa)->get();

        $contenidos = Actividadcontenido::join("actividadtipocontenido","actividadtipocontenido.id","=","actividadcontenido.contenidotipoactuid")
                                        ->join("contenidotipo","contenidotipo.id","=","actividadtipocontenido.contenidotipoid")
                                        ->where('actividaduid','LIKE',$id)
                                        ->where('actividadcontenido.empresauid','LIKE',$uidempresa)
                                        ->where('status','LIKE',"A")
                                        ->select('actividadcontenido.id','actividadcontenido.uniopuid','actividadcontenido.actividaduid','actividadcontenido.contenidotipoactuid','actividadcontenido.valortexto','actividadcontenido.valornumero','actividadcontenido.valorfecha','actividadcontenido.valorcarpeta','actividadcontenido.valorlista','actividadcontenido.idlista','actividadtipocontenido.etiqueta','actividadtipocontenido.posicion','contenidotipo.tipodato','actividadtipocontenido.posicion')
                                        ->orderBy("actividadtipocontenido.posicion")
                                        ->get();
        $rowCont = $contenidos->count();
        $documentos=Documentos::where("actividaduid","LIKE",$id)
                              ->where("empresauid","LIKE",$uidempresa)
                              ->orderBy('created_at','desc')
                              ->get();

        $elementos=Elemento::where("empresauid","LIKE",$uidempresa)
                                ->where("status","LIKE","A")
                                ->orderBy("elemnombre")
                                ->get();

        $useruniopsId=$selectuniopId;

        $actividadesItmen = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)//filtro de version multiempresa
                                ->where("actividads.actividadgrupouid","=",$id)
                                ->select("actividads.id","actividads.actividadinicio","actividads.actividadfin","actividads.actividadstatus","actividads.actividadlugar","tipoacts.tipoactcolor","tipoacts.titulo")
                                ->get();

        $actividadesParent = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)//filtro de version multiempresa
                                ->where("actividads.id","=",$idparentAct)
                                ->select("actividads.id","actividads.actividadinicio","actividads.actividadfin","actividads.actividadstatus","actividads.actividadlugar","tipoacts.tipoactcolor","tipoacts.titulo")
                                ->get();

        $menuDashboard=Dashboard::where('empresauid','=',auth()->user()->uidempresa)
                                ->where('uniopuid','=',auth()->user()->selectuniop)
                                ->get();
        return view('actividad.edit',compact('id','rowCont','actividad','tipoactividads','valor','datebegin','timebegin','timebegin2','dateend','timeend','tipoacts','useruniops','usertipoacts','uniops','direccion','useruniopsId','actividadUsers','usuarios','contenidos','documentos','destinationPath','elementos','programas','dapff','periocidad','tipop','statusap','rowContAP','programasAll','actividadesItmen','actividadesParent','menuDashboard')); /*en compact va la variable de las vista*/
    }

    public function update(Request $request)
    {

    }
    public function updateActividad(Request $request)
    {

      $id=$request->get('id');
      $valores=$request->get('valortext');
      $idContenido=$request->get('registro');
      $tipo=$request->get('tipodato');
      $i=0;$j=0;
      $j=count($idContenido);

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
      }

      Session::flash('save','El Rol fue CREADO Exitosamente');

      $data['success'] = $saved;
      return $data;
    }/* FIN UPDATE */

    public function destroy($id)
    {

    }/* FIN DESTROY */

    public function adduser(Request $request)
    {
      $resp=$request->resp;
      if($request->resp=="on"){ $resp=1; }else{ $resp=0; }
      $data = new Actividaduser();
      $data->empresauid   = $request->empresauid;
      $data->actividaduid = $request->idactividad;
      $data->email        = $request->emailinvitado;
      $data->responsable  = $resp;
      $data->save();
      return redirect('actividad/'.$request->idactividad.'/edit');

    }

    public function remuser(Request $request)
    {
      $idact=$request->idactividad;
      $id=$request->id;

      $user = Actividaduser::find($id);
      $user->delete();
      return redirect('actividad/'.$idact.'/edit');
    }

    public function editdetails(Request $request)
    {

        $id=$request->idactividad;
        $vareditacyper = $request->textoptionsEdit;
        $uideditacyper = $request->actperiocidauid;
        $fi=$request->dateinicio." ".$request->timeinicio2;
        $ff=$request->datefin." ".$request->timefin2;

        $actividadI=new Carbon($fi);
        $actividadF=new Carbon($ff);

        $actividad = Actividad::find($id);

        $actividad->actividadlugar    = $request->place;
        $actividad->actividaddescip   = $request->actividaddescip;
        $actividad->actividadinicio   = $actividadI;
        $actividad->actividadfin      = $actividadF;
        $actividad->useruid           = auth()->user()->id;
        $actividad->actividadstatus   = $request->status;
        $actividad->programauid       = $request->programa;
        $actividad->actividadlugar    = $request->place;


        $saved=$actividad->save();
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
        $finperiodo  = $request->finperiodo." ".$request->timefin2;
        $periodofin=new Carbon($finperiodo);
        //dd('ActividadUID=> '.$id);
        //dd('ActividadPeriodicaUID=> '.$uideditacyper);
        if($uideditacyper=="" AND ($request->finperiodo!="")){
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

            $ap=$actPeriodica->id;
            $act=Actividad::find($idactividad);
            $act->actperiocidauid=$ap;
            $act->save();
            $job = new CreateActividadPeriodica();
            dispatch($job)->delay(now()->addMinutes(1));
            //dd("nuevo sin terner periodo");
        }



            if ($vareditacyper=='actualP'){

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
                    $actPeriodica->ubica       = $ubica;
                    $actPeriodica->descrip     = $descrip;
                    //dd($request->actperiocidauid);
                    $actPeriodica->save();
                    //dd('ActividadPeriodicaUID=> OK');
                    $job = new EditActividadPeriodica();
                    dispatch($job)->delay(now()->addMinutes(1));
                }



        }

      Session::flash('save','Los Registro fue Editaron Exitosamente');
      //return redirect('actividad/'.$id.'/edit');
      $data['ncont']=$request->ncontenido;
      $data['statusclass']=$statusclass;
      $data['statustext']=$statustext;
      $data['descrip'] = $request->actividaddescip;
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
              $imagen->resize(100,100);
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
                      <span class="mailbox-attachment-icon"><img src="'.$path.'/'.$temp_name.'" width="75px" class="group'.$registroDoc.'"></span>
                      <div class="mailbox-attachment-info">
                        <a href="'.$path.'/'.$filename.'" class="mailbox-attachment-name" >'.$fileOriginal.' </a>
                        <span class="mailbox-attachment-size">
                            <a href="#" class="pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                      </div>
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

        $actividad= new Actividad;
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

        $actividad->actividadstatus   = "A";

        $saved=$actividad->save();
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
             * crea un registro en la tabla CARPETAS
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
                        <span class="info-box2-text">'.$request->actividadlugar.'</span>
                        <a href="/actividad/'.$idactividad.'/edit" class="small-box-footer" > ver mas <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>';

        if( $actividad->save() ){
            $data['success'] = $saved ;
            $data['path'] = $datos;
            //dd($datos);
            return $data;
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }
    }/* FIN STORE */

}/*FIN DE LA CLASE*/
