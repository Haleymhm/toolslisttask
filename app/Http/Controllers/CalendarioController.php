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

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Caffeinated\Shinobi\Facades\Shinobi;
use Caffeinated\Shinobi\Models\Role;
use Carbon\Carbon;
use Calendar;
use Session;
use DB;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
* @filesource
* @author
* @since 1.0
*  @copyright
*/
class CalendarioController extends Controller
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
        $id="";

        $uniops = Unidadop::where("unidadopstatus","LIKE","A")
                         ->where("empresauid","LIKE",$uidempresa)->get();
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
        //$usertipoactId=$usertipoacts->tipoacts_id;
        $grupotipos=Grupotipoact::where("empresauid","LIKE",$uidempresa)->get();

         $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                             ->where("user_id","LIKE",$userid)
                             ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                             ->get();
        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }

        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $events = [];
        if(auth()->user()->misact == "S"){
            $actividades = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                    ->join("actividaduser","actividads.id","=","actividaduser.actividaduid")
                                    ->where("actividads.actividadstatus","<>","X")
                                    ->where("tipoacts.mcal","=","SI")
                                    ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)
                                    ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                    ->where("actividaduser.email","=",auth()->user()->email)
                                    ->select("actividads.id","actividads.actividadinicio","actividads.actividadfin","tipoacts.tipoactcolor","tipoacts.titulo")
                                    ->get();

            if($actividades->count()) {
                foreach ($actividades as $key => $actividad) {
                    $events[] = Calendar::event(
                        $actividad->titulo,
                        true,
                        new \DateTime($actividad->actividadinicio),
                        new \DateTime($actividad->actividadfin),
                        null,
                        // Add color and link on event
                        [
                            'color' => $actividad->tipoactcolor ,
                            'textColor'=> 'white',
                            'url' => route('actividad.edit',$actividad->id),
                            'allDay' => false,
                        ]
                    );
                }
            }
        }else{
           // dd(auth()->user()->misact);
            $actividades = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                    ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                    ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)//filtro de version multiempresa
                                    ->where("actividads.actividadstatus","<>","X")
                                    ->where("tipoacts.mcal","=","SI")
                                    ->select("actividads.id","actividads.actividadinicio","actividads.actividadfin","tipoacts.tipoactcolor","tipoacts.titulo")
                                    ->get();
            //dd($actividades);
            if($actividades->count()) {
                foreach ($actividades as $key => $actividad) {


                            $events[] = Calendar::event(
                                $actividad->titulo,
                                true,
                                new \DateTime($actividad->actividadinicio),
                                new \DateTime($actividad->actividadfin),
                                null,
                                // Add color and link on event
                                [
                                    'color' => $actividad->tipoactcolor ,
                                    'textColor'=> 'white',
                                    'url' => route('actividad.edit',$actividad->id),
                                    'allDay' => false,
                                ]
                            );

                }
            }
        }



        $lang="es";

        $calendar = \Calendar::addEvents($events)
                            ->setOptions(['header' => array('left' => ' prev,today,next',
                                                            'center' => 'title',
                                                            'right' => 'agendaDay,agendaWeek,month,day,B1'),

                                          'aspectRatio'=> 2,
                                          'locale'       => $lang,   /* Idioma de las etiquetas del Calendario */
                                          'defaultView'  => 'month', /* vista incial del calendario */
                                          'navLinks'     => true,    /* Inidca si podemos hacel Click en el numero de dia o semana */
                                          'weekNumbers'  => true,    /* Indica el NUemro de Semanas en el Calendario */
                                          'selectable'   => true,
                                          'allDayDefault'=> false,   /* Indica si las actividades se reservan TODO el DIA*/
                                          'eventLimit'   => true,    /* Imprime solos los dias del mes seleccionado*/
                                          'themeSystem'  => 'bootstrap3',
                                          'showNonCurrentDates'=> false,
                                          'height'=> 'auto',
                                          'contentHeight'=> 'auto',
                                          /* Botones de Navegacion con imagenes */
                                         /*'buttonIcons'  => array('prev'     => 'left-single-arrow',
                                                                  'next'     => 'right-single-arrow',
                                                                  'prevYear' => 'left-double-arrow',
                                                                  'nextYear' => 'right-double-arrow'
                                         ),*/
                                          /* Botones de Personalizados */
                                          'customButtons' => [ 'B1' => ['text' => 'Tabla',
                                                                        'click' => 'function(){
                                                                            location.href="/tabla/create";
                                                                        }'
                                                                        ],

                                                             ],
                                        ]) /*V*/
                            ->setCallbacks([
                                             'dayClick' => 'function(date, calEvent, jsEvent, view, resourceObj){

                                                                var fecha = date; //Fecha actual
                                                                var hor = moment(date).format("HH:mm");
                                                                if (hor == "00:00") { var fh_ini = FechaHora30(); } else { fh_ini = date; }

                                                                var fh_fin = moment(fh_ini).add(1, "hours");

                                                                $("#dateinicioAC").val(moment(date).format("DD-MM-YYYY"));
                                                                $("#timeinicioAC").val(moment(fh_ini).format("HH:mm")).trigger("change");
                                                                $("#datefinAC").val(moment(date).format("DD-MM-YYYY"));
                                                                $("#timefinAC").val(moment(fh_fin).format("HH:mm")).trigger("change");

                                                                $("#fullCalModal").modal();
                                                            }',




                                            ]);


        $db= Carbon::now();
        $de= Carbon::now();
        $de2=$de->addHour();
        $datebegin = $db->format('d-m-Y');
        $timebegin = $db->toTimeString();
        $dateend = $de->format('d-m-Y');
        $timeend = $de2->toTimeString();

        $menuDashboard=dashboardMenu();

        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        //$sendMail=enviarCorreo();
        return view('calendario.index', compact('id','notificVencidas','notificDay','calendar','actividades','uniops','direccion','tipoacts','useruniops','usertipoacts','valor','useruniopsId','grupotipos','valor','programas','menuDashboard'));

    }

   

    public function view($id)
    {
    /*
    * Metodo que se encarga de mostrar todoes los eventos de una empresa
    * filtrado por unidad operativa
    * @param uid $id
    * @return event-calendar
    * @author this tag is parsed, but this @version tag is ignored
    * @version 1.0 this version tag is parsed
    */
        $uidempresa=auth()->user()->uidempresa;
        $userid=auth()->user()->id;

        $user = User::findOrFail($userid);
        $user->selectuniop = $id;
        $user->save();

        $useruniopsId=auth()->user()->selectuniop;
        $useruniopsId=$id;
        //dd("ID Unidad GET=>".$id." ::::: Unidad User=>".$useruniopsId);
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



        $events = [];
        if(auth()->user()->misact == "S"){
            $actividades = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                    ->join("actividaduser","actividads.id","=","actividaduser.actividaduid")
                                    ->where("actividads.actividadstatus","<>","X")
                                    ->where("tipoacts.mcal","=","SI")
                                    ->where("actividads.unidadopuid","=",$useruniopsId)
                                    ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                    ->where("actividaduser.email","=",auth()->user()->email)
                                    ->orderBy("actividads.actividadinicio","asc")
                                    ->select("actividads.id","actividads.actividadinicio","actividads.actividadfin","tipoacts.tipoactcolor","tipoacts.titulo")
                                    ->get();

            if($actividades->count()) {
                foreach ($actividades as $key => $actividad) {
                    $events[] = Calendar::event(
                        $actividad->titulo,
                        true,
                        new \DateTime($actividad->actividadinicio),
                        new \DateTime($actividad->actividadfin),
                        null,
                        // Add color and link on event
                        [
                            'color' => $actividad->tipoactcolor ,
                            'textColor'=> 'white',
                            'url' => route('actividad.edit',$actividad->id),
                            'allDay' => false,
                        ]
                    );
                }
            }
        }else{
            $actividades = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                    ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                    ->where("actividads.unidadopuid","=",$useruniopsId)//filtro de version multiempresa
                                    ->where("actividads.actividadstatus","<>","X")
                                    ->where("tipoacts.mcal","=","SI")
                                    ->orderBy("actividads.actividadinicio","asc")
                                    ->select("actividads.id","actividads.actividadinicio","actividads.actividadfin","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.id AS tipoactsid")
                                    ->get();
            //dd($actividades);
            if($actividades->count()) {
                foreach ($actividades as $key => $actividad) {
                            $events[] = Calendar::event(
                                $actividad->titulo,
                                true,
                                new \DateTime($actividad->actividadinicio),
                                new \DateTime($actividad->actividadfin),
                                null,
                                // Add color and link on event
                                [
                                    'color' => $actividad->tipoactcolor ,
                                    'textColor'=> 'white',
                                    'url' => route('actividad.edit',$actividad->id),
                                    'allDay' => false,
                                ]
                            );

                }
            }
        }


        $lang="es";

        $calendar = \Calendar::addEvents($events)
                            ->setOptions(['header' => array('left' => 'prev,today,next',
                                                            'center' => 'title',
                                                            'right' => 'listMonth,agendaDay,agendaWeek,month,day,B1'),

                                          'aspectRatio'=> 1.5,
                                          'locale'       => $lang,   /* Idioma de las etiquetas del Calendario */
                                          'defaultView'  => 'month', /* vista incial del calendario */
                                          'navLinks'     => true,    /* Inidca si podemos hacel Click en el numero de dia o semana */
                                          'weekNumbers'  => true,    /* Indica el NUemro de Semanas en el Calendario */
                                          'selectable'   => true,
                                          'allDayDefault'=> false,   /* Indica si las actividades se reservan TODO el DIA*/
                                          'eventLimit'   => true,    /* Imprime solos los dias del mes seleccionado*/
                                          'themeSystem'  => 'bootstrap3',
                                          'showNonCurrentDates'=> false,
                                          'height'=> 'auto',
                                          'contentHeight'=> 'auto',
                                          /* Botones de Navegacion con imagenes */
                                          'buttonIcons'  => array('prev'     => 'left-single-arrow',
                                                                  'next'     => 'right-single-arrow',
                                                                  'prevYear' => 'left-double-arrow',
                                                                  'nextYear' => 'right-double-arrow'
                                                              ),
                                          /* Botones de Personalizados */
                                          'customButtons' => [ 'B1' => ['text' => 'Tabla',
                                                                        'click' => 'function(){
                                                                                        location.href="/tabla/create";
                                                                            }'
                                                                        ],
                                                             ],
                                        ]) /*V*/
                            ->setCallbacks([ /*'viewRender' => 'function() {alert("Callbacks!");}',*/
                                             /*'navLinkDayClick' => 'function(event) { location.href="/actividad/create"; }',*/
                                             /*'dayClick' => 'function(event) { location.href="/actividad/create"; }',*/
                                             'dayClick' => 'function(date, calEvent, jsEvent, view, resourceObj){

                                                var fecha = date; //Fecha actual
                                                var hor = moment(date).format("HH:mm");
                                                if (hor == "00:00") { var fh_ini = FechaHora30(); } else { fh_ini = date; }

                                                var fh_fin = moment(fh_ini).add(1, "hours");

                                                $("#dateinicioAC").val(moment(date).format("DD-MM-YYYY"));
                                                $("#timeinicioAC").val(moment(fh_ini).format("HH:mm")).trigger("change");
                                                $("#datefinAC").val(moment(date).format("DD-MM-YYYY"));
                                                $("#timefinAC").val(moment(fh_fin).format("HH:mm")).trigger("change");

                                                $("#fullCalModal").modal();
                                            }',


                                            ]);




        $id="";

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('calendario.index', compact('id','notificVencidas','notificDay','calendar','actividades','uniops','direccion','tipoacts','useruniops','usertipoacts','valor','useruniopsId','grupotipos','programas','menuDashboard'));


    }

    public function utp($id)
    {
    /*
    * Metodo que se encarga de mostrar todoes los eventos de una empresa
    * filtrado por Tipo de actividad
    * @param uid $id
    * @return event-calendar
    * @author haleymhm@gmail.com
    * @version 1.0
    */
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


         $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                             ->where("user_id","LIKE",$userid)
                             ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                             ->get();

        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }

        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',auth()->user()->selectuniop)
                            ->where('status','=','A')
                            ->get();


        $events = [];
        if(auth()->user()->misact == "S"){
            $actividades = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                    ->join("actividaduser","actividads.id","=","actividaduser.actividaduid")
                                    ->where("actividads.actividadstatus","<>","X")
                                    ->where("tipoacts.mcal","=","SI")
                                    ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)
                                    ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                    ->where("actividaduser.email","=",auth()->user()->email)
                                    ->where("actividads.tipoactividaduid","=",$id)
                                    ->orderBy("actividads.actividadinicio","asc")
                                    ->select("actividads.id","actividads.actividadinicio","actividads.actividaddescip","actividads.actividadfin","actividads.actividadstatus","actividads.actividadlugar","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.comporta", "tipoacts.tipoactdescrip","tipoacts.id AS tipoactsid")
                                    ->get();

            if($actividades->count()) {
                foreach ($actividades as $key => $actividad) {
                    $events[] = Calendar::event(
                        $actividad->titulo,
                        true,
                        new \DateTime($actividad->actividadinicio),
                        new \DateTime($actividad->actividadfin),
                        null,
                        // Add color and link on event
                        [
                            'color' => $actividad->tipoactcolor ,
                            'textColor'=> 'white',
                            'url' => route('actividad.edit',$actividad->id),
                            'allDay' => false,
                        ]
                    );
                }
            }
        }else{
            $actividades = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                    ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                    ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)//filtro de version multiempresa
                                    ->where("actividads.actividadstatus","<>","X")
                                    ->where("tipoacts.mcal","=","SI")
                                    ->where("actividads.tipoactividaduid","=",$id)
                                    ->orderBy("actividads.actividadinicio","asc")
                                    ->select("actividads.id","actividads.actividadinicio","actividads.actividaddescip","actividads.actividadfin","actividads.actividadstatus","actividads.actividadlugar","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.comporta", "tipoacts.tipoactdescrip","tipoacts.id AS tipoactsid")
                                    ->get();
            //dd($actividades);
            if($actividades->count()) {
                foreach ($actividades as $key => $actividad) {
                            $events[] = Calendar::event(
                                $actividad->titulo,
                                true,
                                new \DateTime($actividad->actividadinicio),
                                new \DateTime($actividad->actividadfin),
                                null,
                                // Add color and link on event
                                [
                                    'color' => $actividad->tipoactcolor ,
                                    'textColor'=> 'white',
                                    'url' => route('actividad.edit',$actividad->id),
                                    'allDay' => false,
                                ]
                            );

                }
            }
        }


        $lang="es";

        $calendar = \Calendar::addEvents($events)
                            ->setOptions(['header' => array('left' => 'prev,today,next',
                                                            'center' => 'title',
                                                            'right' => 'listMonth,agendaDay,agendaWeek,month,day,B1'),

                                          'aspectRatio'=> 1.5,
                                          'locale'       => $lang,   /* Idioma de las etiquetas del Calendario */
                                          'defaultView'  => 'month', /* vista incial del calendario */
                                          'navLinks'     => true,    /* Inidca si podemos hacel Click en el numero de dia o semana */
                                          'weekNumbers'  => true,    /* Indica el NUemro de Semanas en el Calendario */
                                          'selectable'   => true,
                                          'allDayDefault'=> false,   /* Indica si las actividades se reservan TODO el DIA*/
                                          'eventLimit'   => true,    /* Imprime solos los dias del mes seleccionado*/
                                          'themeSystem'  => 'bootstrap3',
                                          'showNonCurrentDates'=> false,
                                          'height'=> 'auto',
                                          'contentHeight'=> 'auto',
                                          /* Botones de Navegacion con imagenes */
                                          'buttonIcons'  => array('prev'     => 'left-single-arrow',
                                                                  'next'     => 'right-single-arrow',
                                                                  'prevYear' => 'left-double-arrow',
                                                                  'nextYear' => 'right-double-arrow'
                                                              ),
                                          /* Botones de Personalizados */
                                          'customButtons' => [ 'B1' => ['text' => 'Tabla',
                                                                        'click' => 'onTablaCalendario'
                                                                        ],
                                                             ],
                                        ]) /*V*/
                            ->setCallbacks(['dayClick' => 'function(date, calEvent, jsEvent, view, resourceObj){

                                                var fecha = date; //Fecha actual
                                                var hor = moment(date).format("HH:mm");
                                                if (hor == "00:00") { var fh_ini = FechaHora30(); } else { fh_ini = date; }

                                                var fh_fin = moment(fh_ini).add(1, "hours");

                                                $("#dateinicioAC").val(moment(date).format("DD-MM-YYYY"));
                                                $("#timeinicioAC").val(moment(fh_ini).format("HH:mm")).trigger("change");
                                                $("#datefinAC").val(moment(date).format("DD-MM-YYYY"));
                                                $("#timefinAC").val(moment(fh_fin).format("HH:mm")).trigger("change");

                                                $("#fullCalModal").modal();
                                            }',


                                            ]);



        /****** COSAS PARA EL MODO TABLA  ******************/
        $modView="";
        $ta = Tipoact::where("uid","LIKE",$id)->get();
        foreach ($ta as $tas) { $modView=$tas->tvista; $descTipAct=$tas->titulo; $comporta=$tas->comporta;}
        if($comporta=$tas->comporta==2){
            return redirect()->action('DocumentosController@tablaDocumento', ['uid' => $id]);
        }elseif($comporta=$tas->comporta==3){  
            if(($valor=='admin') or ($valor=='root')){
                return redirect()->action('DocumentosController@tablaDocumento', ['uid' => $id]);
            }else{
                return redirect()->action('DocumentosController@unicoDocumento', ['uid' => $id]);
            } 
        }
        
        if($modView=="lis"){
        $arrayHeadteble="algo";
        $arrContTipo=Actividadtipodato::where('tipoactid','=',$id)
                                      ->where("mostrar",'=','SI')
                                      ->where('status','=','A')
                                      ->orderBy('posicion','asc')->get();

        $headTables[0]="Fecha";
        $i=1;
        foreach ($arrContTipo as $act) { $headTables[$i]=$act->etiqueta; $i=$i+1;}
        $headTables[$i]="Status";
        if(auth()->user()->misact == "S"){
            $actTable = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
            ->join("actividaduser","actividads.id","=","actividaduser.actividaduid")
            ->where("actividads.actividadstatus","<>","X")
            ->where("tipoacts.tvista","=","lis")
            ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)
            ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
            ->where("actividaduser.email","=",auth()->user()->email)
            ->where("actividads.tipoactividaduid","=",$id)
            ->orderBy("actividads.actividadinicio","asc")
            ->select("actividads.id","actividads.actividadinicio","actividads.actividaddescip","actividads.actividadfin","actividads.actividadstatus","actividads.actividadlugar","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.comporta", "tipoacts.tipoactdescrip","tipoacts.id AS tipoactsid")
            ->get();
            //dd(auth()->user()->misact);
        }else{
            $actTable = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                    ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                    ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)//filtro de version multiempresa
                                    ->where("actividads.tipoactividaduid","=",$id)
                                    ->where("actividads.actividadstatus","<>","X")
                                    ->where("tipoacts.tvista","=","lis")
                                    ->orderBy("actividads.actividadinicio","asc")
                                    ->select("actividads.id","actividads.actividadinicio","actividads.actividaddescip","actividads.actividadfin","actividads.actividadstatus","actividads.actividadlugar","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.comporta", "tipoacts.tipoactdescrip","tipoacts.id AS tipoactsid")
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
            $notificVencidas=actividadesVencidas();
            $notificDay=actividadesDay();
            return view('calendario.tabla', compact('i','y','id','notificVencidas','notificDay','descTipAct','headTables','contTables','uniops','direccion','tipoacts','useruniops','usertipoacts','valor','useruniopsId','grupotipos','programas','menuDashboard'));
        }
        else{
            $menuDashboard=dashboardMenu();
            $notificVencidas=actividadesVencidas();
            $notificDay=actividadesDay();
            return view('calendario.index', compact('calendar','id','notificVencidas','notificDay','actividades','uniops','direccion','tipoacts','useruniops','usertipoacts','valor','useruniopsId','grupotipos','programas','menuDashboard'));
         }




    }


    public function user($id)
    {

        $uidempresa=auth()->user()->uidempresa;
        $userid=auth()->user()->id;
        $mailuser=auth()->user()->email;
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
        foreach ($useruniops as $useruniop) {
            $useruniopsId=$useruniop->unidadopuid;
        }

        /**** Todas las unidades operativas a las que pertenece un usuario ****/
        $usertipoacts =Usertipoact::where("user_id","LIKE",$userid)->get();


         $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                             ->where("user_id","LIKE",$userid)
                             ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                             ->get();
        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug; }

        $programas=Programas::where('empresauid','LIKE',$uidempresa)
                            ->where('uniopuid','LIKE',auth()->user()->selectuniop)
                            ->get();

                            $events = [];
                            if(auth()->user()->misact == "S"){
                                $actividades = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                                        ->join("actividaduser","actividads.id","=","actividaduser.actividaduid")
                                                        ->where("actividads.actividadstatus","<>","X")
                                                        ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)
                                                        ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                                        ->where("actividaduser.email","=",auth()->user()->email)
                                                        ->where("actividads.tipoactividaduid","=",$id)
                                                        ->orderBy("actividads.actividadinicio","asc")
                                                        ->select("actividads.id","actividads.actividadinicio","actividads.actividadfin","tipoacts.tipoactcolor","tipoacts.titulo")
                                                        ->get();

                                if($actividades->count()) {
                                    foreach ($actividades as $key => $actividad) {
                                        $events[] = Calendar::event(
                                            $actividad->titulo,
                                            true,
                                            new \DateTime($actividad->actividadinicio),
                                            new \DateTime($actividad->actividadfin),
                                            null,
                                            // Add color and link on event
                                            [
                                                'color' => $actividad->tipoactcolor ,
                                                'textColor'=> 'white',
                                                'url' => route('actividad.edit',$actividad->id),
                                                'allDay' => false,
                                            ]
                                        );
                                    }
                                }
                            }else{
                                $actividades = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                                                        ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                                                        ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)//filtro de version multiempresa
                                                        ->where("actividads.actividadstatus","<>","X")
                                                        ->where("actividads.tipoactividaduid","=",$id)
                                                        ->orderBy("actividads.actividadinicio","asc")
                                                        ->select("actividads.id","actividads.actividadinicio","actividads.actividadfin","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.id AS tipoactsid")
                                                        ->get();
                                //dd($actividades);
                                if($actividades->count()) {
                                    foreach ($actividades as $key => $actividad) {
                                                $events[] = Calendar::event(
                                                    $actividad->titulo,
                                                    true,
                                                    new \DateTime($actividad->actividadinicio),
                                                    new \DateTime($actividad->actividadfin),
                                                    null,
                                                    // Add color and link on event
                                                    [
                                                        'color' => $actividad->tipoactcolor ,
                                                        'textColor'=> 'white',
                                                        'url' => route('actividad.edit',$actividad->id),
                                                        'allDay' => false,
                                                    ]
                                                );

                                    }
                                }
                            }


        $lang="es";

        $calendar = \Calendar::addEvents($events)
                            ->setOptions(['header' => array('left' => 'prev,today,next',
                                                            'center' => 'title',
                                                            'right' => 'listMonth,agendaDay,agendaWeek,month,day,B1'),

                                          'aspectRatio'=> 1.5,
                                          'locale'       => $lang,   /* Idioma de las etiquetas del Calendario */
                                          'defaultView'  => 'month', /* vista incial del calendario */
                                          'navLinks'     => true,    /* Inidca si podemos hacel Click en el numero de dia o semana */
                                          'weekNumbers'  => true,    /* Indica el NUemro de Semanas en el Calendario */
                                          'selectable'   => true,
                                          'allDayDefault'=> false,   /* Indica si las actividades se reservan TODO el DIA*/
                                          'eventLimit'   => true,    /* Imprime solos los dias del mes seleccionado*/
                                          'themeSystem'  => 'bootstrap3',
                                          'showNonCurrentDates'=> false,
                                          'height'=> 'auto',
                                          'contentHeight'=> 'auto',
                                          /* Botones de Navegacion con imagenes */
                                          'buttonIcons'  => array('prev'     => 'left-single-arrow',
                                                                  'next'     => 'right-single-arrow',
                                                                  'prevYear' => 'left-double-arrow',
                                                                  'nextYear' => 'right-double-arrow'
                                                              ),
                                          /* Botones de Personalizados */
                                          'customButtons' => [ 'B1' => ['text' => 'Tabla',
                                                                        'click' => 'onTablaCalendario'
                                                                        ],
                                                             ],
                                        ]) /*V*/
                            ->setCallbacks(['dayClick' => 'function(date, calEvent, jsEvent, view, resourceObj){

                                                var fecha = date; //Fecha actual
                                                var hor = moment(date).format("HH:mm");
                                                if (hor == "00:00") { var fh_ini = FechaHora30(); } else { fh_ini = date; }

                                                var fh_fin = moment(fh_ini).add(1, "hours");

                                                $("#dateinicioAC").val(moment(date).format("DD-MM-YYYY"));
                                                $("#timeinicioAC").val(moment(fh_ini).format("HH:mm")).trigger("change");
                                                $("#datefinAC").val(moment(date).format("DD-MM-YYYY"));
                                                $("#timefinAC").val(moment(fh_fin).format("HH:mm")).trigger("change");

                                                $("#fullCalModal").modal();
                                            }',


                                            ]);

        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('calendario.index', compact('id','notificVencidas','notificDay','calendar','actividades','uniops','direccion','tipoacts','useruniops','usertipoacts','valor','useruniopsId','grupotipos','programas','menuDashboard'));


    }

    public function misactividades(Request $request)
    {
        if($request->misact=="on"){
            $ma="N";
        }else{
            $ma="S";
        }
       //dd($ma);
        $user = User::findOrFail(auth()->user()->id);
        $user->misact = $ma;
        $saved=$user->save();
        $data['success'] = $saved;
        return $data;
    }

}
