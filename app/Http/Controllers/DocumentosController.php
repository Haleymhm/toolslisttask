<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Collection;

use App\Actividad;
use App\Tipoact;
use App\Unidadop;
use App\Useruniop;
use App\Usertipoact;
use App\Roleuserh;
use App\Grupotipoact;
use App\Actividaduser;
use App\ActividadPeriodica;
use App\Contenidotipo;  /* Tipo de Datos */
use App\Actividadtipodato; /* tipoDato-tipoActividad */
use App\Actividadcontenido;
use App\Documentos;
use App\Listado;
use App\Elemento;
use App\Programas;

use Carbon\Carbon;


class DocumentosController extends Controller
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
    public function index(Request $request)
    {
        $id=$request->uid;
        $uidempresa=auth()->user()->uidempresa;

        $useruniopsId=auth()->user()->selectuniop;
        //uniopSelected
        /**** Todas las unidades operativas de una empresa ****/
        $uniops =Unidadop::where("unidadopstatus","LIKE","A")
                         ->where("empresauid","LIKE",$uidempresa)->get();
                         foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }
        /**** Todas los Tipo de Actividades de una empresa ****/
        $tipoacts =Tipoact::where("status","LIKE","A")
                          ->where("empresauid","LIKE",$uidempresa)
                          ->get();
        /**** Todas las unidades operativas a las que pertenece un usuario ****/
        $useruniops = Useruniop::where("user_id","LIKE",auth()->user()->id)->get();

        /**** Todas las unidades operativas a las que pertenece un usuario ****/
        $usertipoacts =Usertipoact::where("user_id","LIKE",auth()->user()->id)->get();
        //$usertipoactId=$usertipoacts->tipoacts_id;
        $grupotipos=Grupotipoact::where("empresauid","LIKE",$uidempresa)->get();

         $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                             ->where("user_id","LIKE",auth()->user()->id)
                             ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                             ->get();
        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }
        $id="";
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('documentos.index',compact('id','notificVencidas','notificDay','uniops','direccion','valor','usertipoacts','useruniops','useruniopsId','tipoacts','grupotipos','usertipoacts','programas','menuDashboard'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unicoDocumento($id)
    {
        $ta = Tipoact::where("uid","=",$id)->get();
        foreach ($ta as $tas) { 
            $descTipAct=$tas->titulo; 
            $comporta=$tas->comporta;
            $xid=$tas->uid;
            $actividades = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                    ->where("actividads.empresauid","=",auth()->user()->uidempresa)
                                    ->where("tipoacts.uid","=",$xid)
                                    ->where("tipoacts.comporta","=",3)
                                    ->select("actividads.id","actividads.actividadinicio","actividads.actividaddescip","actividads.actividadfin","actividads.actividadstatus","actividads.actividadlugar","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.comporta", "tipoacts.tipoactdescrip")
                                    ->take(1)
                                    ->get();
            foreach ($actividades as $actividad) {
                $uid=$actividad->id;
            }
        }     
        return redirect()->action('DocumentosController@viewDocumento', ['id' => $uid]);
                              
    }

    
    public function viewDocumento($id)
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
        
        $actividades = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                ->where("actividads.empresauid","=",auth()->user()->uidempresa)
                                ->where("actividads.id","=",$id)
                                ->select("actividads.id","actividads.actividadinicio","actividads.actividaddescip","actividads.actividadfin","actividads.actividadstatus","actividads.actividadlugar","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.comporta", "tipoacts.tipoactdescrip")
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
        return view('documentos.roedit',compact('id','notificVencidas','notificDay','rowCont','actividad','tipoactividads','valor','datebegin','timebegin','timebegin2','dateend','timeend','tipoacts','useruniops','usertipoacts','uniops','direccion','useruniopsId','actividadUsers','usuarios','contenidos','documentos','destinationPath','elementos','programas','dapff','periocidad','tipop','statusap','rowContAP','programasAll','actividadesItmen','actividadesParent','menuDashboard','colUsers','listados')); /*en compact va la variable de las vista*/
    }

    public function tablaDocumento($id)
    {

        $useruniopsId=auth()->user()->selectuniop;
        //uniopSelected
        /**** Todas las unidades operativas de una empresa ****/
        $uniops =Unidadop::where("unidadopstatus","LIKE","A")
                         ->where("empresauid","=",auth()->user()->uidempresa)->get();
                         foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }
        /**** Todas los Tipo de Actividades de una empresa ****/
        $tipoacts =Tipoact::where("status","LIKE","A")->where("empresauid","=",auth()->user()->uidempresa)->get();
        /**** Todas las unidades operativas a las que pertenece un usuario ****/
        $useruniops = Useruniop::where("user_id","LIKE",auth()->user()->id)->get();
        /**** Todas las unidades operativas a las que pertenece un usuario ****/
        $usertipoacts =Usertipoact::where("user_id","LIKE",auth()->user()->id)->get();
        //$usertipoactId=$usertipoacts->tipoacts_id;
        $grupotipos=Grupotipoact::where("empresauid","=",auth()->user()->uidempresa)->get();

        $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                              ->where("user_id","LIKE",auth()->user()->id)
                              ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                              ->get();
        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }

        $programas=Programas::where('empresauid','=',auth()->user()->uidempresa)
                            ->where('uniopuid','=',auth()->user()->selectuniop)
                            ->where('status','=','A')
                            ->get();

       
/**********************************************************************************************************************************************************/
        $ta = Tipoact::where("uid","=",$id)->get();
        foreach ($ta as $tas) { $modView=$tas->tvista; $descTipAct=$tas->titulo; $comporta=$tas->comporta;}
        $arrayHeadteble="algo";
        $arrContTipo=Actividadtipodato::where('tipoactid','=',$id)
                                        ->where("mostrar",'=','SI')
                                        ->where('status','=','A')
                                        ->orderBy('posicion','asc')->get();

        $headTables[0]="Fecha"; $i=1;
        foreach ($arrContTipo as $act) { $headTables[$i]=$act->etiqueta; $i=$i+1;}
        $headTables[$i]="Status";
        if(($valor=='admin') or ($valor=='root')){
            $actTable = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
            ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
            ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)//filtro de version multiempresa
            ->where("actividads.tipoactividaduid","=",$id)
            ->where("tipoacts.tvista","=","lis")
            ->orderBy("actividads.actividadinicio","asc")
            ->select("actividads.id","actividads.actividadinicio","actividads.actividaddescip","actividads.actividadfin","actividads.actividadstatus","actividads.actividadlugar","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.comporta", "tipoacts.tipoactdescrip","tipoacts.id AS tipoactsid")
            ->get();

        }else{
            $actTable = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                        ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                        ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)//filtro de version multiempresa
                                        ->where("actividads.tipoactividaduid","=",$id)
                                        ->where("actividads.actividadstatus","=","C")
                                        ->where("tipoacts.tvista","=","lis")
                                        ->orderBy("actividads.actividadinicio","asc")
                                        ->select("actividads.id","actividads.actividadinicio","actividads.actividaddescip","actividads.actividadfin","actividads.actividadstatus","actividads.actividadlugar","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.comporta", "tipoacts.tipoactdescrip","tipoacts.id AS tipoactsid")
                                        ->get();
        }
        $y=0;
        $arrayContenido[0]=['uid'=>'',
                            'fecha'=>'',
                            'resumen'=>'',
                            'status'=>''
                        ];

                        $contTables = new Collection;
                        foreach ($actTable as $actt) {
                            $resumen="";
                            $contenidos = Actividadcontenido::join("actividadtipocontenido","actividadtipocontenido.id","=","actividadcontenido.contenidotipoactuid")
                                                        ->join("contenidotipo","contenidotipo.id","=","actividadtipocontenido.contenidotipoid")
                                                        ->where('actividaduid','=',$actt->id)

                                                        ->where('actividadtipocontenido.mostrar','=','SI')
                                                        ->select('actividadcontenido.id','actividadcontenido.uniopuid','actividadcontenido.actividaduid','actividadcontenido.contenidotipoactuid','actividadcontenido.valortexto','actividadcontenido.valornumero','actividadcontenido.valorfecha','actividadcontenido.valorcarpeta','actividadcontenido.valorlista','actividadcontenido.idlista','actividadcontenido.valorgrupact','actividadtipocontenido.etiqueta','actividadtipocontenido.posicion','contenidotipo.tipodato','actividadtipocontenido.posicion','actividadtipocontenido.status')
                                                        ->orderBy("actividadtipocontenido.posicion")
                                                        ->get();
                                $contTDcont=0;
                                foreach ($contenidos as $cont) {
                                    $valorfecha="";
                                    $valorlista="";
                                    $valorAct="";
                                    $valorDoc="";
                                    $valornumero="";
                                    $valortexto=substr($cont->valortexto,0,350);
                                    if(!is_null($cont->valorfecha) ){
                                        $f=new Carbon($cont->valorfecha);
                                        $valorfecha=$f->format('d-m-Y');
                                    }

                                    if(!is_null($cont->valornumero) ){
                                        $valornumero=number_format($cont->valornumero,2);
                                    }

                                    if(!is_null($cont->valorlista) ){
                                        $elementos=Elemento::where('id','LIKE',$cont->valorlista)->get();
                                        foreach ($elementos as $elemento) {
                                            $valorlista=$elemento->elemnombre;
                                        }

                                    }

                                    if(!is_null($cont->valorgrupact) ){
                                        //dd($cont->valorgrupact);
                                        $actItems=Actividad::where("actividadgrupouid","=",$actt->id)->get();
                                        $rowActItems=$actItems->count();
                                        $valorAct=$rowActItems." actividad(es)";

                                    }

                                    if(!is_null($cont->valorcarpeta ) ){
                                        //dd($cont->valorcarpeta);
                                        $docItems=Documentos::where("actividaduid","=",$actt->id)->get();
                                        $rowDocItems=$docItems->count();
                                        $valorDoc=$rowDocItems." documento(s)";

                                    }
                                    if($contTDcont > 0){
                                        $resumen= $resumen .'<td class="nomobile">'. $valortexto .$valornumero . $valorfecha. $valorlista. $valorAct. $valorDoc.'</td>';
                                    }else{

                                        $resumen= $resumen .'<td>'. $valortexto . $valornumero . $valorfecha. $valorlista. $valorAct. $valorDoc.'</td>';
                                    }

                                    $contTDcont++;
                                }
                            $db = new Carbon($actt->actividadinicio);
                            $fecha = $db->format('d-m-Y');
                            $arrayContenido[$y]=['uid'=>$actt->id,
                                            'fecha'=>$fecha,
                                            'resumen'=>$resumen,
                                            'status'=>$actt->actividadstatus
                                            ];
                            $y++;
                        }
            $contTable=collect($arrayContenido);
            $contTables=$contTable->sortBy('fecha');
            $menuDashboard="";
            $menuDashboard=dashboardMenu();
            //return $contTables;
           // $id="";
            $notificVencidas=actividadesVencidas();
            $notificDay=actividadesDay();
            return view('documentos.tabla', compact('i','y','id','valor','notificVencidas','notificDay','descTipAct','headTables','contTables','uniops','direccion','tipoacts','useruniops','usertipoacts','valor','useruniopsId','grupotipos','programas','menuDashboard'));
    }
}
