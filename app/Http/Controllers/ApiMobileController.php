<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use App\User;
use App\Roleuserh;
use App\Unidadop;
use App\Useruniop;
use App\Grupotipoact;
use App\Tipoact;
use App\Usertipoact;
use App\Usertipoactmob;
use App\Actividad;
use App\Actividaduser;
use App\Actividadcontenido;
use App\Contenidotipo;  /* Tipo de Datos */
use App\Carpeta;
use App\ActividadPeriodica;
use App\Actividadgrupo;
use App\Actividadtipodato; /* tipoDato-tipoActividad */
use App\Programas;
use App\Documentos;
use App\Elemento;
use App\Dashboard;
use App\Listado;
use Image;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\New_;

//use Maatwebsite\Excel\Concerns\ToArray;

class ApiMobileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendTA(Request $request)
    {
        $id=$request->uid;

        $Tipoacts=Tipoact::where('uid','=',$id)->get();
        $tipActContenido=Actividadtipodato::where('tipoactid','=',$id)->get();
        $contenidos=Actividadtipodato::join('contenidotipo','contenidotipo.id','=','actividadtipocontenido.contenidotipoid')
                                                ->where('actividadtipocontenido.tipoactid','=',$id)
                                                ->where('actividadtipocontenido.status','=','A')
                                                ->orderBy('actividadtipocontenido.posicion')
                                                ->select('actividadtipocontenido.id','actividadtipocontenido.etiqueta','actividadtipocontenido.posicion','actividadtipocontenido.idlista','contenidotipo.tipodato','contenidotipo.id AS tipouid')
                                                ->get();

        foreach ($Tipoacts as $Tipoact) {
            $tvista='';
            foreach ($contenidos as $conte){

                //unset($element);
                $listados=Listado::where("id","=",$conte->idlista)->get();
                foreach ($listados as $listado) {
                    $tvista=$listado->ver;
                }

                $elementos=Elemento::where("listadouid","=",$conte->idlista)
                                   ->where("status","=","A")
                                   ->orderBy("elemnombre")
                                   ->get();
                unset($element);
                $element[]=['uidelemento'=>'',
                            'titelemento'=>''
                            ];
                foreach ($elementos as $elemento) {
                    $element[]=[
                        'uidelemento'=>$elemento->id,
                        'titelemento'=>$elemento->elemnombre
                    ];
                }
                $datos[]=['id'=>$conte->id ,
                        'etiqueta'=>$conte->etiqueta,
                        'posicion'=>$conte->posicion,
                        'idtipodato'>=$conte->tipouid,
                        'tipodato'=>$conte->tipodato,
                        'idlista'=>$conte->idlista,
                        'tlista'=>$tvista,
                        'elementos'=>$element

                ];

            }
            $tipoActividad=['uid'=>$Tipoact->id,
                          'nombre'=>$Tipoact->titulo,
                          'contenido'=>$datos];
        }

        return response()->json($tipoActividad);
    }

    public function sendData(Request $request)
    {
        $em="$request->email";

        $usuario=User::where('email','=', $em)->get();
        foreach ($usuario as $user) {
            $useruid=$user->id;
            $empresauid=$user->uidempresa;
            $username=$user->name;
            $usermail=$user->email;
            $empresa=Empresa::where('id','=', $empresauid)->get();

            foreach ($empresa as $emp) {$nombreempresa=$emp->empresanombre; }
        }/***** FIN  DE CONSULATA DE USUARIO  ******/
        //dd($empresa);
        /***** INICIO DE UNIDADES OPERATIVAS POR USUARIO ******/
        $userUnipos=Useruniop::where('user_id','LIKE',$useruid)->get();
        foreach ($userUnipos as $userUnipo) {
            $uo=$userUnipo->unidadopuid;
            $Unipos=Unidadop::where('unidadopuid','LIKE',$uo)->get();
            foreach ($Unipos as $Unipo) {
                $arrayUnipos[]=['uop_id'=>$Unipo->unidadopuid,
                                'uop_nom'=>$Unipo->unidadopnombre];
            }
        }/***** FIN UNIDADES OPERATIVAS POR USUARIO ******/

        /***** INICIO DE TIPO DE ACTIVDADES POR USUSARIO ******/
        $userTipActs=Usertipoactmob::where('user_id','LIKE',$useruid)->get();
        $rows=$userTipActs->count();
        if($rows==0){
            $arrayTipoacts=['tp_id'=>'0',
                              'tp_nom'=>'Sin Activividades Asociadas al Usuario',
                              'tp_uid'=>'000'];
        }
        foreach ($userTipActs as $userTipAct) {
            $tp=$userTipAct->tipoacts_id;
            $Tipoacts=Tipoact::where('id','=',$tp)->get();
            foreach ($Tipoacts as $Tipoact) {
                $arrayTipoacts[]=['tp_id'=>$Tipoact->id,
                                'tp_nom'=>$Tipoact->titulo,
                                'tp_uid'=>$Tipoact->uid];
            }

        }/***** DE TIPO DE ACTIVDADES POR USUSARIO ******/

        /***** INICIO DE MODELO MENU ******/
        $grupTipos=Grupotipoact::where('status','LIKE' ,'A')
                                ->where('empresauid','LIKE',$empresauid)
                                ->orderby('parent')
                                ->orderby('orden')
                                ->orderby('titulo')
                                ->select('id','titulo','parent','orden','uid',"tmenu")
                                ->get()
                                ->toArray();

        $dataTipo = Tipoact::join("tipoact_user","tipoacts_id","=","tipoacts.id")
                           ->where("user_id","LIKE",$useruid)
                           ->select("tipoacts.id","tipoacts.titulo","tipoacts.parent","tipoacts.orden","tipoacts.uid","tipoacts.tmenu")
                           ->where('status','LIKE' ,'A')
                           ->where('empresauid','LIKE',$empresauid)
                           ->orderby('tipoacts.parent')
                           ->orderby('tipoacts.orden')
                           ->orderby('tipoacts.titulo')
                           ->get()
                           ->toArray();

        $data1 = array_merge($grupTipos, $dataTipo);
        $data = array_sort($data1, 'parent', SORT_ASC); // Sort by surname
        $menuAll = [];
        foreach ($data as $line) {
            $item = [ array_merge($line, ['submenu' => $this->getChildren($data, $line) ]) ];
            $menuAll = array_merge($menuAll, $item);
        }

        /***** INICIO DE MODELO MENU ******/

        /***** DE TIPO DE ACTIVDADES POR USUSARIO ******/
        $arrayUsuario=['user_id'=>$useruid,
                         'user_nom'=>$username,
                         'user_mail'=>$usermail,
                         'emp_id'=>$empresauid,
                         'emp_nom'=>$nombreempresa,
                         'uops'=>$arrayUnipos,
                         'tipact'=>$arrayTipoacts,
                         'menu'=>$arrayTipoacts,
                         'msg'=>'', /*  Usuario ha Ingresado Correctamente  */
                         'status'=>'OK'
                        ];

        return response()->json($arrayUsuario);

    }


    public function getChildren($data, $line)
    {
        $children = [];
        foreach ($data as $line1) {
            if ($line['id'] == $line1['parent']) {
                $children = array_merge($children, [ array_merge($line1, ['submenu' => $this->getChildren($data, $line1) ]) ]);
            }
        }
        return $children;
    }


    public function getTask(Request $request)
    {
        $actFind=Actividad::where('id','=', $request->act_id)->get();
        $rowContAct=$actFind->count();

        if($rowContAct == 0){
            $task=$this->createTask($request);
        }else{
            $task=$this->editTask($request);
        }

        return response()->json($task);
    }

    public function createTask($request)
    {
        $empresauid="";
        $usuario=User::where('id','=', $request->user_id)->get();
        foreach ($usuario as $user) { $empresauid=$user->uidempresa; }
        //dd($empresauid);
        $tipact=Tipoact::where('uid','=', $request->acttipo_id)->get();
        foreach ($tipact as $ta) { $titulo=$ta->titulo; $color=$ta->tipoactcolor; }

        $actividad = New  Actividad;
        $actividad->id               = $request->act_id;
        $actividad->empresauid       = $empresauid;
        $actividad->useruid          = $request->user_id;
        $actividad->unidadopuid      = $request->uop_id;
        $actividad->tipoactividaduid = $request->acttipo_id;
        $actividad->actividadtitulo  = $titulo;
        $actividad->actividaddescip  = $request->act_desc;
        $actividad->actividadinicio  = $request->act_fh_ini;
        $actividad->actividadfin     = $request->act_fh_fin;
        $actividad->actividadlugar   = $request->act_lugar;
        $actividad->actividadcolor   = $color;
        $actividad->actividadorigen  = 'API';
        $actividad->actividadstatus  = 'A';

        if( $actividad->save() ){

            $parts=$request->act_particip;
            foreach ($parts as $part) {
                $actUser=New Actividaduser;
                $actUser->useruid = $request->user_id;
                $actUser->empresauid = $empresauid;
                $actUser->actividaduid = $request->act_id;
                $actUser->nombre = $part['nom'];
                $actUser->email = $part['mail'];
                $actUser->responsable = '1';
                $actUser->save();
            }

            $contenido=$request->act_valores;
            foreach ($contenido as $cont) {

                $actCont = New Actividadcontenido;
                $actCont->empresauid   =  $empresauid;
                $actCont->uniopuid  =  $request->uop_id;
                $actCont->tipoactuid  =  $request->acttipo_id;
                $actCont->actividaduid  =  $request->act_id;
                $actCont->contenidotipoactuid  =  $cont['id'];

                if(($cont['tipodato']=="text") or ($cont['tipodato']=="textarea")){
                    $actCont->valortexto   =  $cont['valor'];
                    $actCont->save();
                }

                if(($cont['tipodato']=="numeric") or ($cont['tipodato']=="monto")){
                    $actCont->valornumero  =  $cont['valor'];
                    $actCont->save();
                }

                if($cont['tipodato']=="date") {
                    $actCont->valorfecha  =  $cont['valor'];
                    $actCont->save();
                }

                if($cont['tipodato']=="list") {
                    $actCont->valorlista   =  $cont['valor'];
                    $actCont->idlista   =  $cont['idlista'];
                    $actCont->save();
                }

                if($cont['tipodato']=="actividad") {
                    $actgrup=Actividadgrupo::create([
                        'empresauid'=>$empresauid,
                        'unidadopuid'=>$request->uop_id,
                        'actividaduid'=>$request->act_id,
                        'status'=>'A'
                    ]);
                    $actCont->valorgrupact  = $actgrup->id;
                    $actCont->save();
                }

                if($cont['tipodato']=="documento") {

                    $carpeta=Carpeta::create([
                        'empresauid'=>$empresauid,
                        'actividaduid'=>$request->act_id,
                        'carpetanombre'=>$request->actividadtitulo
                    ]);
                    $actCont->save();
                    $registroDoc=$actCont->id;
                    $actCont->valorcarpeta  =  $carpeta->id;
                    $actCont->save();

                    if (isset($cont['valor'])) { $arrayValor=$cont['valor']; }
                    else{$arrayValor=[];}

                    $arrayValor=$cont['valor'];
                    if (empty($arrayValor)){
                        $msg="Algo que informar";
                    }else{
                        foreach ($arrayValor as $aValor) {

                            $destinationPath = public_path().'/upload/'.$empresauid.'/';

                            $base64_str = substr($aValor['base64'], strpos($aValor['base64'], ",")+1);
                            $imagen64 = base64_decode($base64_str);
                            $imagen = Image::make($imagen64);
                            $file_name=$aValor['uid'].'.png';
                            $imagen->save($destinationPath . $file_name);

                            $tmp="thumb_". $file_name;
                            $imagen = Image::make($imagen64);
                            $hfile= $imagen->height();
                            $wfile= $imagen->width();
                            $wx=intval((33000/$wfile)); /** Ancho costante de 330px **/
                            $hx=intval((($hfile*$wx)/100));
                            $imagen->resize(330,$hx);
                            $imagen->save($destinationPath . $tmp);

                            Documentos::create([
                                'empresauid'=>$empresauid,
                                'actividaduid'=>$request->act_id,
                                'carpetauid'=>$carpeta->id,
                                'contenidouid'=>$registroDoc,
                                'nombre'=>$file_name,
                                'nombrefisico'=>$file_name,
                                'thumbnails'=>$tmp,
                                'extension'=>'png',
                                'publico'=>"S",
                                'status'=>"A"
                            ]);
                        }
                    }
                }

            }

            $msg=['act_id'=>$request->act_id,'msg'=>'','status'=>'OK'];
        }else{
            $msg=['act_id'=>$request->act_id,
                  'msg'=>'No se logro CREAR la actividad: '.$request->act_id,
                  'status'=>'FAIL'];
        }
        return $msg;
    }

    public function editTask($request)
    {
        $usuario=User::where('id','=', $request->user_id)->get();
        foreach ($usuario as $user) { $empresauid=$user->uidempresa; }

        $tipact=Tipoact::where('uid','=', $request->acttipo_id)->get();
        foreach ($tipact as $ta) { $titulo=$ta->titulo; $color=$ta->tipoactcolor; }

        $actividad = Actividad::find($request->act_id);
        $actividad->empresauid       = $empresauid;
        $actividad->useruid          = $request->user_id;
        $actividad->unidadopuid      = $request->uop_id;
        $actividad->tipoactividaduid = $request->acttipo_id;
        $actividad->actividadtitulo  = $request->$titulo;
        $actividad->actividaddescip  = $request->act_desc;
        $actividad->actividadinicio  = $request->act_fh_ini;
        $actividad->actividadfin     = $request->act_fh_fin;
        $actividad->actividadlugar   = $request->act_lugar;
        $actividad->actividadcolor   = $request->$color;
        $actividad->actividadorigen  = 'API';
        $actividad->actividadstatus  = 'A';

        if( $actividad->save() ){
            $contenido=$request->act_valores;
            foreach ($contenido as $cont) {
                $actContenido=Actividadcontenido::where('empresauid','=',$empresauid)
                                                ->where('uniopuid','=',$request->uop_id)
                                                ->where('tipoactuid','=',$request->acttipo_id)
                                                ->where('actividaduid','=',$request->act_id)
                                                ->where('contenidotipoactuid','=',$cont['id'])
                                                ->get();
                foreach ($actContenido as $acttt) { $uuiidd  = $acttt->id; }

                $actCont=Actividadcontenido::find($uuiidd);

                if(($cont['tipodato']=="text") or ($cont['tipodato']=="textarea")){
                    $actCont->valortexto   =  $cont['valor'];
                    $actCont->save();
                }

                if(($cont['tipodato']=="numeric") or ($cont['tipodato']=="monto")){
                    $actCont->valornumero  =  $cont['valor'];
                    $actCont->save();
                }

                if($cont['tipodato']=="date") {
                    $actCont->valorfecha  =  $cont['valor'];
                    $actCont->save();
                }

                if($cont['tipodato']=="list") {
                    $actCont->valorlista   =  $cont['valor'];
                    $actCont->idlista   =  $cont['idlista'];
                    $actCont->save();
                }

                if($cont['tipodato']=="documento") {
                    if (isset($cont['valor'])) {
                         $arrayValor=$cont['valor'];
                        }else{$arrayValor=[];}
                    $carpetauid=$actCont->valorcarpeta;
                    $contUID=$actCont->id;

                    if (empty($arrayValor)){
                        $msg="Algo que informar";
                    }else{
                        foreach ($arrayValor as $aValor) {

                            $file_name=$aValor['uid'].'.png';
                            $destinationPath = public_path().'/upload/'.$empresauid.'/';

                            $findDocs=Documentos::where('empresauid','=',$empresauid)
                                                ->where('actividaduid','=',$request->act_id)
                                                ->where('nombre','=',$file_name)
                                                ->where('carpetauid','=',$carpetauid)
                                                ->get();

                            $rowContFile=$findDocs->count();

                            if($rowContFile==0){

                                $base64_str = substr($aValor['base64'], strpos($aValor['base64'], ",")+1);
                                $imagen64 = base64_decode($base64_str);
                                $imagen = Image::make($imagen64);

                                $imagen->save($destinationPath . $file_name);

                                $tmp="thumb_". $file_name;
                                $imagen = Image::make($imagen64);
                                $hfile= $imagen->height();
                                $wfile= $imagen->width();
                                $wx=intval((33000/$wfile)); /** Ancho costante de 330px **/
                                $hx=intval((($hfile*$wx)/100));
                                $imagen->resize(330,$hx);
                                $imagen->save($destinationPath . $tmp);

                                Documentos::create([
                                    'empresauid'=>$empresauid,
                                    'actividaduid'=>$request->act_id,
                                    'carpetauid'=>$carpetauid,
                                    'contenidouid'=>$contUID,
                                    'nombre'=>$file_name,
                                    'nombrefisico'=>$file_name,
                                    'thumbnails'=>$tmp,
                                    'extension'=>'png',
                                    'publico'=>"S",
                                    'status'=>"A"
                                ]);
                            }
                        }
                    }
                }
            }

            $msg=['act_id'=>$request->act_id,
                  'msg'=>'',
                  'status'=>'OK'];
        }else{
            $msg=['act_id'=>$request->act_id,
                  'msg'=>'No se logro EDITAR la actividad: '.$request->act_id,
                  'status'=>'FAIL'];
        }

        return $msg;
    }

}
