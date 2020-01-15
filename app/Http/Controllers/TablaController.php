<?php

/**
* Controlador que muestra las actividades en el calendario
*
* @author Haleym Hidalgo - haleymhm@gmail.com
* @since 1.0
* @copyright 2019
*/
namespace App\Http\Controllers;
use App\Actividad;
use App\Tipoact;
use App\Unidadop;
use App\Useruniop;
use App\Usertipoact;
use App\Roleuserh;
use App\Grupotipoact;
use App\Useractividad;
use App\Dashboard;
use App\Contenidotipo;  /* Tipo de Datos */
use App\Actividadtipodato; /* tipoDato-tipoActividad */
use App\Actividadcontenido;
use App\Carpeta;
use App\Documentos;
use App\Listado;
use App\Elemento;
use App\Programas;
use App\Colores;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Foundation\Auth\User;
use Caffeinated\Shinobi\Facades\Shinobi;
use Caffeinated\Shinobi\Models\Role;
use Carbon\Carbon;
use Calendar;
use Session;
use DB;

use Illuminate\Database\Eloquent\Collection;

class TablaController extends Controller
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
        $userid=auth()->user()->id;
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
        $useruniops = Useruniop::where("user_id","LIKE",$userid)->get();

        /**** Todas las unidades operativas a las que pertenece un usuario ****/
        $usertipoacts =Usertipoact::where("user_id","LIKE",$userid)->get();
        //$usertipoactId=$usertipoacts->tipoacts_id;
        $grupotipos=Grupotipoact::where("empresauid","LIKE",$uidempresa)->get();

         $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                             ->where("user_id","LIKE",$userid)
                             ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                             ->get();
        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }



        $arrayContenido[]=['uid'=>"",
                                'tipo'=>"",
                                'fecha'=>"",
                                'resumen'=>"",
                                'status'=>""
                                ];

        $ta = Tipoact::where("uid","LIKE",$id)
                     ->where("tvista","LIKE","lis")
                     ->where("empresauid","LIKE",$uidempresa)->get();
        foreach ($ta as $tas) {
            $modView=$tas->tvista;
            $descTipAct=$tas->titulo;
            $tipo = '<span class="badge" style="background-color:'.$tas->tipoactcolor.'">&nbsp;&nbsp;&nbsp;</span> &nbsp;'.$tas->titulo;


        $actTable=Actividad::where("tipoactividaduid","LIKE",$tas->uid)
                            ->orderBy('actividadinicio')->get();
        //$i=0;
        $contTables = new Collection;
            foreach ($actTable as $actt) {
                $resumen="";
                $contenidos = Actividadcontenido::join("actividadtipocontenido","actividadtipocontenido.id","=","actividadcontenido.contenidotipoactuid")
                                            ->join("contenidotipo","contenidotipo.id","=","actividadtipocontenido.contenidotipoid")
                                            ->where('actividaduid','LIKE',$actt->id)
                                            ->where('status','LIKE',"A")
                                            ->where('actividadtipocontenido.mostrar','LIKE',"SI")
                                            ->select('actividadcontenido.id','actividadcontenido.uniopuid','actividadcontenido.actividaduid','actividadcontenido.contenidotipoactuid','actividadcontenido.valortexto','actividadcontenido.valornumero','actividadcontenido.valorfecha','actividadcontenido.valorcarpeta','actividadcontenido.valorlista','actividadcontenido.valorgrupact','actividadcontenido.idlista','actividadtipocontenido.etiqueta','actividadtipocontenido.posicion','contenidotipo.tipodato','actividadtipocontenido.posicion')
                                            ->orderBy("actividadtipocontenido.posicion")
                                            ->get();
                    foreach ($contenidos as $cont) {
                        $valorfecha="";
                        $valorlista="";
                        $valorAct="";
                        $valorDoc="";
                        $valortexto=substr($cont->valortexto,0,350);
                        if(!is_null($cont->valorfecha) ){
                            //dd($cont->valorfecha);
                            $f=new Carbon($cont->valorfecha);
                            $valorfecha=$f->format('d-m-Y');
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

                        $resumen = $resumen . "<strong>".$cont->etiqueta.": </strong>";
                        $resumen = $resumen. $valortexto . $cont->valornumero . $valorfecha. $valorlista. $valorAct. $valorDoc."</br>";

                    }

                $db = new Carbon($actt->actividadinicio);
                //$tipo="TIPOOO";
                $fecha = $db->format('d-m-Y');
                $arrayContenido[]=['uid'=>$actt->id,
                                'tipo'=>$tipo,
                                'fecha'=>$fecha,
                                'resumen'=>$resumen,
                                'status'=>$actt->actividadstatus
                                ];
                //$i++;


            }


            $contTable=collect($arrayContenido);
            $contTables=$contTable->sortBy('fecha');
        }
        $id="";
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('tabla.index', compact('i','id','notificVencidas','notificDay','descTipAct','headTables','contTables','uniops','direccion','tipoacts','useruniops','usertipoacts','valor','useruniopsId','grupotipos','programas','menuDashboard'));
    }


    public function create()
    {
        //dd("FUNCION TABLACAL DEL CONTROLLER TABLA");
        $id="";
        $uidempresa=auth()->user()->uidempresa;
        $userid=auth()->user()->id;
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
        $useruniops = Useruniop::where("user_id","LIKE",$userid)->get();

        /**** Todas las unidades operativas a las que pertenece un usuario ****/
        $usertipoacts =Usertipoact::where("user_id","LIKE",$userid)->get();
        //$usertipoactId=$usertipoacts->tipoacts_id;
        $grupotipos=Grupotipoact::where("empresauid","LIKE",$uidempresa)->get();

         $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                             ->where("user_id","LIKE",$userid)
                             ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                             ->get();
        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }



        $arrayContenido[]=['uid'=>"",
                                'tipo'=>"",
                                'fecha'=>"",
                                'resumen'=>"",
                                'status'=>""
                                ];

        $ta = Tipoact::where("empresauid","LIKE",$uidempresa)->get();
        foreach ($ta as $tas) {
            $modView=$tas->tvista;
            $descTipAct=$tas->titulo;
            $tipo = '<span class="badge" style="background-color:'.$tas->tipoactcolor.'">&nbsp;&nbsp;&nbsp;</span> &nbsp;'.$tas->titulo;

            /** FALTA FILTRO POR UNIDAD OPERATIVA **/
        $actTable=Actividad::where('tipoactividaduid','=',$tas->uid)
                           ->where('unidadopuid','=',auth()->user()->selectuniop)
                           ->orderBy('actividadinicio')->get();
        //$i=0;
        //$contTables = new Collection;

            foreach ($actTable as $actt) {
                $resumen="";
                $contenidos = Actividadcontenido::join("actividadtipocontenido","actividadtipocontenido.id","=","actividadcontenido.contenidotipoactuid")
                                            ->join("contenidotipo","contenidotipo.id","=","actividadtipocontenido.contenidotipoid")
                                            ->where('actividaduid','=',$actt->id)
                                            ->where('actividadtipocontenido.status','=','A')
                                            ->where('actividadtipocontenido.mostrar','=','SI')
                                            ->select('actividadcontenido.id','actividadcontenido.uniopuid','actividadcontenido.actividaduid','actividadcontenido.contenidotipoactuid','actividadcontenido.valortexto','actividadcontenido.valornumero','actividadcontenido.valorfecha','actividadcontenido.valorcarpeta','actividadcontenido.valorlista','actividadcontenido.valorgrupact','actividadcontenido.idlista','actividadtipocontenido.etiqueta','actividadtipocontenido.posicion','contenidotipo.tipodato','actividadtipocontenido.posicion','actividadtipocontenido.status')
                                            ->orderBy("actividadtipocontenido.posicion")
                                            ->get();
                    foreach ($contenidos as $cont) {
                        $valorfecha="";
                        $valorlista="";
                        $valorAct="";
                        $valorDoc="";
                        $valortexto=substr($cont->valortexto,0,350);
                        if(!is_null($cont->valorfecha) ){
                            //dd($cont->valorfecha);
                            $f=new Carbon($cont->valorfecha);
                            $valorfecha=$f->format('d-m-Y');
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

                        $resumen = $resumen . "<strong>".$cont->etiqueta.": </strong>";
                        $resumen = $resumen. $valortexto . $cont->valornumero . $valorfecha. $valorlista. $valorAct. $valorDoc."</br>";

                    }

                $db = new Carbon($actt->actividadinicio);
                //$tipo="TIPOOO";
                $fecha = $db->format('d-m-Y');
                $arrayContenido[]=['uid'=>$actt->id,
                                'tipo'=>$tipo,
                                'fecha'=>$fecha,
                                'resumen'=>$resumen,
                                'status'=>$actt->actividadstatus
                                ];
                //$i++;


            }

            $contTable=collect($arrayContenido);
            $contTables=$contTable->sortBy('fecha');

        }
        $id="";
        //dd($arrayContenido);
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('tabla.index', compact('i','id','notificVencidas','notificDay','descTipAct','headTables','contTables','uniops','direccion','tipoacts','useruniops','usertipoacts','valor','useruniopsId','grupotipos','programas','menuDashboard'));
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

        $uidempresa=auth()->user()->uidempresa;
        $userid=auth()->user()->id;
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
        $useruniops = Useruniop::where("user_id","LIKE",$userid)->get();

        /**** Todas las unidades operativas a las que pertenece un usuario ****/
        $usertipoacts =Usertipoact::where("user_id","LIKE",$userid)->get();
        //$usertipoactId=$usertipoacts->tipoacts_id;
        $grupotipos=Grupotipoact::where("empresauid","LIKE",$uidempresa)->get();

        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

         $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                             ->where("user_id","LIKE",$userid)
                             ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                             ->get();
        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }

        $ta = Tipoact::where("uid","=",$id)->get();
        foreach ($ta as $tas) { $modView=$tas->tvista; $descTipAct=$tas->titulo;}


        $arrayHeadteble="algo";
        $arrContTipo=Actividadtipodato::where('tipoactid','=',$id)
                                      ->where('mostrar','=','SI')
                                      ->where('status','=','A')
                                      ->orderBy('posicion','asc')->get();

        $headTables[0]="Fecha";
        $i=1;
        foreach ($arrContTipo as $act) { $headTables[$i]=$act->etiqueta; $i=$i+1;}
        $headTables[$i]="Status";
        if(auth()->user()->misact == "S"){
            $actTable = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
            ->join("actividaduser","actividads.id","=","actividaduser.actividaduid")
            ->where("actividads.actividadstatus","=","A")
            ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)
            ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
            ->where("actividaduser.email","=",auth()->user()->email)
            ->where("actividads.tipoactividaduid","=",$id)
            ->orderBy("actividads.actividadinicio","asc")
            ->select("actividads.id","actividads.actividadinicio","actividads.actividadfin","actividads.actividadstatus","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.id AS tipoactsid")
            ->get();
            //dd(auth()->user()->misact);
        }else{
            $actTable = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                    ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                    ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)//filtro de version multiempresa
                                    ->where("actividads.tipoactividaduid","=",$id)
                                    ->orderBy("actividads.actividadinicio","asc")
                                    ->select("actividads.id","actividads.actividadinicio","actividads.actividadfin","actividads.actividadstatus","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.id AS tipoactsid")
                                    ->get();

        }
        //$actTable=Actividad::where("tipoactividaduid","LIKE",$id)->get();

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
                                            ->where('actividaduid','LIKE',$actt->id)
                                            ->where('actividadtipocontenido.status','=','A')
                                            ->where('actividadtipocontenido.mostrar','=','SI')
                                            ->select('actividadcontenido.id','actividadcontenido.uniopuid','actividadcontenido.actividaduid','actividadcontenido.contenidotipoactuid','actividadcontenido.valortexto','actividadcontenido.valornumero','actividadcontenido.valorfecha','actividadcontenido.valorcarpeta','actividadcontenido.valorlista','actividadcontenido.valorgrupact','actividadcontenido.idlista','actividadtipocontenido.etiqueta','actividadtipocontenido.posicion','contenidotipo.tipodato','actividadtipocontenido.posicion','actividadtipocontenido.status')
                                            ->orderBy("actividadtipocontenido.posicion")
                                            ->get();

                    foreach ($contenidos as $cont) {
                        $valorfecha="";
                        $valorlista="";
                        $valorAct="";
                        $valorDoc="";
                        $valortexto=substr($cont->valortexto,0,350);
                        if(!is_null($cont->valorfecha) ){
                            $f=new Carbon($cont->valorfecha);
                            $valorfecha=$f->format('d-m-Y');
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

                        $resumen= $resumen .'<td>'. $valortexto . $cont->valornumero . $valorfecha. $valorlista. $valorAct. $valorDoc.'</td>';

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
            $notificVencidas=actividadesVencidas();
            $notificDay=actividadesDay();
            return view('calendario.tabla', compact('i','y','id','notificVencidas','notificDay','descTipAct','headTables','contTables','uniops','direccion','tipoacts','useruniops','usertipoacts','valor','useruniopsId','grupotipos','programas','menuDashboard'));

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
