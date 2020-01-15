<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Roleuserh;
use App\Usertipoact;
use App\Useruniop;
use App\Unidadop;
use App\Tipoact;
use App\Programas;
use App\Grupotipoact;
use App\Actividad;
use App\ActividadPeriodica;
use App\Actividadtipodato;
use App\Actividadcontenido;
use App\Elemento;
use App\Documentos;
use Carbon\Carbon;
use App\Dashboard;
use DB;

class VistaProgramaController extends Controller
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
        $id="";
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $poravance[]=['id'=>'', 'nombre'=>'', 'avance'=>''];
        foreach ($programas as $prog) {
            $idactpro=$prog->id;
            $db= new Carbon($prog->fechai);
            $fi=$db->toDateString();
            $fi=$db->format('d-m-Y');
            $de=new Carbon($prog->fechaf);
            $ff=$de->toDateString();
            $ff=$de->format('d-m-Y');
            $ActTotal=Actividad::where('programauid','=',$idactpro)->get();
            $rctact=$ActTotal->count();

            $ActAct=Actividad::where('programauid','=',$idactpro)
                             ->where('actividadstatus','=','A')->get();
            $rctotactact=$ActAct->count();

            if($rctact==0){ $ressul=0; }else{ $ressul=(($rctotactact * 100)/$rctact); }

            $complet=$rctact - $rctotactact;
            if($rctact==0){
                $avance2=0;
            }else{
                $avance2 = 100 - $ressul;
            }
            //$avance2= 100 - $ressul;
            $avance=intval($avance2);
            //dd($avance);
            $poravance[]=['id'=>$prog->id,
                          'nombre'=>$prog->prognombre,
                          'color'=>$prog->progcolor,
                          'icono'=>$prog->progicon,
                          'avance'=>$avance,
                          'complet'=>$complet,
                          'tact'=>$rctact,
                          'fi'=>$fi,
                          'ff'=>$ff
                        ];
        }
        $contTables=collect($poravance);
        //dd($contTables);

        $programasI=Programas::where('empresauid','=',$uidempresa)
        ->where('uniopuid','=',$useruniopsId)
        ->where('status','=','I')
        ->get();

        $poravanceI[]=['id'=>'', 'nombre'=>'', 'avance'=>''];
        foreach ($programasI as $prog) {
        $idactpro=$prog->id;
        $db= new Carbon($prog->fechai);
        $fi=$db->toDateString();
        $fi=$db->format('d-m-Y');
        $de=new Carbon($prog->fechaf);
        $ff=$de->toDateString();
        $ff=$de->format('d-m-Y');
        $ActTotal=Actividad::where('programauid','=',$idactpro)->get();
        $rctact=$ActTotal->count();

        $ActAct=Actividad::where('programauid','=',$idactpro)
                ->where('actividadstatus','=','A')->get();
        $rctotactact=$ActAct->count();

        if($rctact==0){ $ressul=0; }else{ $ressul=(($rctotactact * 100)/$rctact); }

        $complet=$rctact - $rctotactact;
        if($rctact==0){
            $avance2=0;
        }else{
            $avance2 = 100 - $ressul;
        }

        $avance=intval($avance2);

        $poravanceI[]=['id'=>$prog->id,
            'nombre'=>$prog->prognombre,
            'color'=>$prog->progcolor,
            'icono'=>$prog->progicon,
            'avance'=>$avance,
            'complet'=>$complet,
            'tact'=>$rctact,
            'fi'=>$fi,
            'ff'=>$ff
            ];
        }

        $contTablesI=collect($poravanceI);
        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('vprogramas.index',compact('id','notificVencidas','notificDay','uniops','direccion','valor','usertipoacts','useruniops','useruniopsId','tipoacts','grupotipos','usertipoacts','programas','contTables','contTablesI','menuDashboard'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function viewcontenido($uidta, $uidpr)
    {
        //dd("LLEGA A LA FUNCION viewcontenido DE VistaProgramaController");

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

    $programas=Programas::where('empresauid','=',$uidempresa)
                        ->where('uniopuid','=',$useruniopsId)
                        ->get();



        /****** COSAS PARA EL MODO TABLA  ******************/
        $modView="";
        $ta = Tipoact::where("uid","=",$uidta)->get();
        foreach ($ta as $tas) { $modView=$tas->tvista; $descTipAct=$tas->titulo;}
        $modView="lis";
        if($modView=="lis"){
        $arrayHeadteble="algo";
        $arrContTipo=Actividadtipodato::where("tipoactid","=",$uidta)
                                      ->where("mostrar","=","SI")
                                      ->orderBy("posicion","asc")->get();

        $headTables[0]="Fecha";
        $i=1;
        foreach ($arrContTipo as $act) { $headTables[$i]=$act->etiqueta; $i=$i+1;}
        $headTables[$i]="Status";
        $actTable=Actividad::where("tipoactividaduid","LIKE",$uidta)
                          ->where('programauid','=',$uidpr)->get();
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

                        $resumen= $resumen ."<td>". $valortexto . $cont->valornumero . $valorfecha. $valorlista."</td>";

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
        }

        $id="";
        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('vprogramas.tabla', compact('i','y','id','notificVencidas','notificDay','descTipAct','headTables','contTables','uniops','direccion','tipoacts','useruniops','usertipoacts','valor','useruniopsId','grupotipos','programas','menuDashboard'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $programaUID=$id;
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
        $id="";
        $programas=Programas::where('empresauid','LIKE',$uidempresa)
                            ->where('uniopuid','LIKE',$useruniopsId)
                            ->get();

        foreach ($programas as $prog1) {
            if($prog1->id == $programaUID){ $nombProg=$prog1->prognombre;}
        }

        $poravance[]=['id'=>'', 'nombre'=>'', 'avance'=>''];
        $sql = "SELECT DISTINCT tipoactividaduid FROM actividads WHERE programauid LIKE '".$programaUID."'";
        $rowTipoActividad=DB::select($sql);


        foreach ($rowTipoActividad as $prog) {
            $idTipAct=$prog->tipoactividaduid;
            $tipact=Tipoact::where('uid','=',$idTipAct)->get();

            foreach($tipact as $tp){
                $nombTip=$tp->titulo;
                $colorTip=$tp->tipoactcolor;
            }

            $ActTotal=Actividad::where('programauid','=',$programaUID)
                               ->where('tipoactividaduid','=',$idTipAct)
                               ->get();
            $rctact=$ActTotal->count();

            $ActActs=Actividad::where('programauid','=',$programaUID)
                            ->where('tipoactividaduid','=',$idTipAct)
                            ->where('actividadstatus','=','A')->get();
            $rctotactact=$ActActs->count();

            $fi="";$ff="";$msgper="se repite cada";
            foreach($ActTotal as $ActAct){
                $actperiocidauid =$ActAct->actperiocidauid;
                $actPers=ActividadPeriodica::where('id','=',$actperiocidauid)->get();
                foreach ($actPers as $actPer) {
                    $idactpro=$actPer->id;
                    $db= new Carbon($actPer->fechai);

                    $fi=$db->toDateString();
                    $fi=$db->format('d-m-Y');
                    $de=new Carbon($actPer->fechaf);
                    $ff=$de->toDateString();
                    $ff=$de->format('d-m-Y');
                    $periocidad=$actPer->periocidad;
                    $tipoper=$actPer->tipoperiodo;
                    if($tipoper=='d'){ $msgper=" se repite cada".$periocidad." dia(s)";}
                    if($tipoper=='s'){ $msgper=" se repite cada".$periocidad." semana(s)";}
                    if($tipoper=='m'){ $msgper=" se repite cada".$periocidad." mes(es)";}
                    if($tipoper=='a'){ $msgper=" se repite cada".$periocidad." año(s)";}
                }

            }

            $ressul=(($rctotactact * 100)/$rctact);
            $avance2= 100 - $ressul;
            $avance=intval($avance2);
            $complet=$rctact - $rctotactact;
            //dd($fi);
            //dd($avance);
            $poravance[]=['id'=>$idTipAct,
                          'nombre'=>$nombTip,
                          'color'=>$colorTip,
                          'totalact'=>$rctact, /** total de todas las actividades **/
                          'actacti'=>$rctotactact,
                          'complet'=>$complet,
                          'avance'=>$avance, /** pordntajes de las  actividades completadas **/
                          'fi'=>$fi,
                          'ff'=>$ff,
                          'msgper'=>$msgper
                        ];
        }

        $contTables=collect($poravance);
        $id="";
        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();

        return view('vprogramas.show',compact('id','notificVencidas','notificDay','uniops','direccion','valor','usertipoacts','useruniops','useruniopsId','tipoacts','grupotipos','usertipoacts','programas','contTables','nombProg','programaUID','menuDashboard'));
    }


}
