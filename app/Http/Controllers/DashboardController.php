<?php

namespace App\Http\Controllers;

use App\Dashboard;
use App\DBItem;
use App\Tipoact;
use App\Unidadop;

use App\Useruniop;
use App\Roleuserh;
use App\Usertipoact;
use App\Grupotipoact;
use App\Actividadtipodato;
use App\Contenidotipo;
use App\Actividadcontenido;
use DB;
use App\Programas;
use App\Colores;
use Session;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Listado;


use App\Actividad;
use Illuminate\Http\Request;

class DashboardController extends Controller
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

        $rolesActivo = Roleuserh::join("roles","roles.id","=","role_user.role_id")
        ->where("user_id","=",auth()->user()->id)
        ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
        ->get();

        foreach ($rolesActivo as $rolActivo) {
            $valor = $rolActivo->slug;
        }

        $usertipoacts = Usertipoact::where("user_id","=",auth()->user()->id)->get();
        $useruniops = Useruniop::where("user_id","=",auth()->user()->id)->get();
        $tipoacts = Tipoact::where("status","=","A")
                                  ->where("empresauid","=",auth()->user()->uidempresa)
                                  ->orderBy('titulo','asc')
                                  ->get();


        $uniops = Unidadop::where("deleted","=",0)
                         ->where("empresauid","=",auth()->user()->uidempresa)
                         ->where("unidadopstatus","=","A")->get();
                         foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid == auth()->user()->selectuniop){
                                $direccion = $uniop->unidadopnombre;
                            }
                        }

        $grupotipoactividads = Grupotipoact::where("empresauid","=",auth()->user()->uidempresa)
                                           ->where("status","=","A")
                                           ->orderBy("parent")
                                           ->orderBy("orden")
                                           ->get();

        $programas=Programas::where('empresauid','=',auth()->user()->uidempresa)
                            ->where('status','=','A')
                            ->get();



        $dashboards=Dashboard::where('empresauid','=',auth()->user()->uidempresa)
                             ->orderBy('dbpos','ASC')
                             ->get();

        $menuDashboard=dashboardMenu();
        $nColumnas=$menuDashboard->count() + 1;
        //dd($dashboards);
        $colores=Colores::all();
        $id="";
        $useruniopsId=auth()->user()->selectuniop;

        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('dashboard.index',compact('id','notificVencidas','notificDay','dashboards','direccion','uniops','valor','usertipoacts','useruniops','tipoacts','useruniopsId','grupotipoactividads','programas','colores','menuDashboard','nColumnas')); /*en compact va la variable de las vista*/
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
        $request->validate([
            'dbnombre'=>'required',
        ]);

        $dashboard = new Dashboard;

        $dashboard->empresauid      = auth()->user()->uidempresa;
        //$dashboard->uniopuid        = auth()->user()->selectuniop;
        $dashboard->dbnom           = $request->dbnombre;
        $dashboard->dbdesc          = $request->dbdescrip;
        $dashboard->dbpos           = $request->dbpos;
        $dashboard->status          = "A";



        if( $dashboard->save()){
            Session::flash('save','La Registro fue CREADO Exitosamente');
            $idact=$dashboard->id;
        return redirect('dashboard/'.$idact.'/edit');
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                              ->where("user_id","=",auth()->user()->id)
                              ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                              ->get();

        foreach ($rolesActivo as $rolActivo) {
        $valor= $rolActivo->slug;
        }

        $usertipoacts =Usertipoact::where("user_id","=",auth()->user()->id)->get();
        $useruniops = Useruniop::where("user_id","=",auth()->user()->id)->get();
        $tipoacts = Tipoact::where("status","=","A")
                                  ->where("empresauid","=",auth()->user()->uidempresa)
                                  ->orderBy('titulo','asc')
                                  ->get();

        $uniops =Unidadop::where("deleted","=",0)
                         ->where("empresauid","=",auth()->user()->uidempresa)
                         ->where("unidadopstatus","=","A")->get();
                         foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==auth()->user()->selectuniop){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }

        $grupotipoactividads = Grupotipoact::where("empresauid","=",auth()->user()->uidempresa)
                                    ->where("status","=","A")
                                    ->orderBy("parent")
                                    ->orderBy("orden")
                                    ->get();

        $programas=Programas::where('empresauid','=', auth()->user()->uidempresa)
                            ->where('uniopuid','=', auth()->user()->selectuniop)
                            ->where('status','=','A')
                            ->get();

        $useruniopsId=auth()->user()->selectuniop;
        $dashboard=Dashboard::find($id);
        $tipoContenidos=Actividadtipodato::where('empresauid','=', auth()->user()->uidempresa)
                                     ->get();
        $dbItem=DBItem::where('dashboarduid','=',$id)
                      ->where('empresauid','=', auth()->user()->uidempresa)
                      ->orderBy('itempos','asc')
                      ->get();

        //dd($id);
        $nColumnas=$dbItem->count() + 1;
        $menuDashboard=dashboardMenu();
        $id="";
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('dashboard.edit',compact('id','notificVencidas','notificDay','dashboard','dbItem','uniops','direccion','valor','usertipoacts','useruniops','tipoacts','useruniopsId','grupotipoactividads','nColumnas','programas','tipoContenidos','menuDashboard'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'dbnombre'=>'required',
        ]);

        $dashboard = Dashboard::find($id);

        $dashboard->empresauid      = auth()->user()->uidempresa;
        $dashboard->uniopuid        = auth()->user()->selectuniop;
        $dashboard->dbnom           = $request->dbnombre;
        $dashboard->dbdesc          = $request->dbdescrip;
        $dashboard->dbpos           = $request->dbpos;
        $dashboard->status          = $request->dbstatus;



        if( $dashboard->save()){
            Session::flash('save','La Registro fue CREADO Exitosamente');
            $idact=$dashboard->id;
        return redirect('dashboard/'.$idact.'/edit');
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }

    public function addcontenido(Request $request)
    {
        $request->validate([
            'itemtipo' => 'required',
            'tipoactuid' => 'required',
            /*'agrupartipocontuid' => 'required',
            'itemoperacion' => 'required',
            'itemgrafico' => 'required', */
            'itempos' => 'required',
            'status' => 'required'
        ]);


        $dbItem = new DBItem;
        $dbItem->empresauid          = auth()->user()->uidempresa;
        $dbItem->itemtipo            = $request->itemtipo;
        $dbItem->tipoactuid          = $request->tipoactuid;
        $dbItem->dashboarduid        = $request->dashboarduid;
        $dbItem->agrupartipocontuid  = $request->agrupartipocontuid;
        $dbItem->itemoperacion       = $request->itemoperacion;
        $dbItem->itemgrafico         = $request->itemgrafico;
        $dbItem->itemdesde           = $request->agrupartipocontuid2;
        $dbItem->itempos             = $request->itempos;
        $dbItem->status              = $request->status;

        $dbItem->save();


        return redirect('dashboard/'.$request->dashboarduid.'/edit');

    }

    public function editcontenido(Request $request)
    {
        $request->validate([
            'itemtipo' => 'required',
            'tipoactuid' => 'required',
            /*'agrupartipocontuid' => 'required',
            'itemoperacion' => 'required',
            'itemgrafico' => 'required',*/
            'itempos' => 'required',
            'status' => 'required'
        ]);

        $dbItem = DBItem::find($request->id);
        $dbItem->empresauid          = auth()->user()->uidempresa;
        $dbItem->itemtipo            = $request->itemtipo;
        $dbItem->tipoactuid          = $request->tipoactuid;
        $dbItem->dashboarduid        = $request->dashboarduid;
        $dbItem->agrupartipocontuid  = $request->agrupartipocontuid;
        $dbItem->itemoperacion       = $request->itemoperacion;
        $dbItem->itemgrafico         = $request->itemgrafico;
        $dbItem->itemdesde           = $request->agrupartipocontuid2;
        $dbItem->itempos             = $request->itempos;
        $dbItem->status              = $request->status;

        $dbItem->save();


        return redirect('dashboard/'.$request->dashboarduid.'/edit');

    }

    public function getTipoContenido($id) {

        //$getTipoContenidos = DB::table("actividadtipocontenido")->where("tipoactid",$id)->pluck("id","etiqueta");
        $getTipoContenidos = Actividadtipodato::join('contenidotipo','contenidotipo.id','=','actividadtipocontenido.contenidotipoid')
                                                ->where('actividadtipocontenido.tipoactid','=',$id)
                                                ->where('actividadtipocontenido.status','=','A')
                                                ->orderBy('actividadtipocontenido.posicion')
                                                ->select('actividadtipocontenido.id','actividadtipocontenido.etiqueta','contenidotipo.tipodato')
                                                ->get(); /* ->where('contenidotipo.tipodato','=','lista')*/
        //dd($getTipoContenidos);
        $datos='<option value=""></option>'; $datosfaker="";
        foreach ($getTipoContenidos as $getTipoContenido) {

            if(($getTipoContenido->tipodato=="titulo") or ($getTipoContenido->tipodato=="desing")  or ($getTipoContenido->tipodato=="actividad") or ($getTipoContenido->tipodato=="documento")){
                $datosfaker=$datosfaker . '<option value="'.$getTipoContenido->id.'">'.$getTipoContenido->etiqueta.'</option>';
            }else{
                $datos = $datos . '<option value="'.$getTipoContenido->id.'">'.$getTipoContenido->etiqueta.'</option>';
            }
            //$datos= $datos . '<option value="'.$getTipoContenido->id.'">'.$getTipoContenido->etiqueta.'</option>';
        }
        $data['success'] = true;
        $data['item'] = $datos;
        return $data;


    }
    public function getTipoContenido2($id) {

        $getTipoContenidos = Actividadtipodato::join('contenidotipo','contenidotipo.id','=','actividadtipocontenido.contenidotipoid')
                                                ->where('actividadtipocontenido.tipoactid','=',$id)
                                                ->where('actividadtipocontenido.status','=','A')
                                                ->orderBy('actividadtipocontenido.posicion')
                                                ->select('actividadtipocontenido.id','actividadtipocontenido.etiqueta','contenidotipo.tipodato')
                                                ->get(); /* ->where('contenidotipo.tipodato','=','lista')*/
        //dd($getTipoContenidos);
        $datos='<option value=""></option>'; $datosfaker="";
        foreach ($getTipoContenidos as $getTipoContenido) {
            if(($getTipoContenido->tipodato=="numeric") or ($getTipoContenido->tipodato=="monto") or ($getTipoContenido->tipodato=="lista")){
                $datos = $datos . '<option value="'.$getTipoContenido->id.'">'.$getTipoContenido->etiqueta.'</option>';
            }
        }
        $data['success'] = true;
        $data['item'] = $datos;
        return $data;


    }
    public function view(Request $request)
    {
        $iudDB=$request->iudDB; $inicio=$request->dateinicio; $fin=$request->datefin; $status=$request->status;

        if(!isset($inicio)){
            $inicio='01-01-'.date('Y');
            $fi= new Carbon($inicio);
        }else{
            $fi=new Carbon($inicio);
            $inicio = $fi->format('d-m-Y');
        }

        if(!isset($fin)){
            $fin=date('d-m-Y').'  23:59:59';
            $ff = new Carbon($fin);
            $fin = $ff->format('d-m-Y');
        }else{
            $ff=new Carbon($fin.'  23:59:59');
            $fin = $ff->format('d-m-Y');
        }

        if(!isset($status)){ $status='A';}

        $rolesActivo = Roleuserh::join("roles","roles.id","=","role_user.role_id")
        ->where("user_id","=",auth()->user()->id)
        ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
        ->get();

        foreach ($rolesActivo as $rolActivo) {
            $valor = $rolActivo->slug;
        }

        $usertipoacts = Usertipoact::where("user_id","=",auth()->user()->id)->get();
        $useruniops = Useruniop::where("user_id","=",auth()->user()->id)->get();
        $tipoacts = Tipoact::where("status","=","A")
                                  ->where("empresauid","=",auth()->user()->uidempresa)
                                  ->orderBy('titulo','asc')
                                  ->get();


        $uniops = Unidadop::where("deleted","=",0)
                         ->where("empresauid","=",auth()->user()->uidempresa)
                         ->where("unidadopstatus","=","A")->get();
                         foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid == auth()->user()->selectuniop){
                                $direccion = $uniop->unidadopnombre;
                            }
                        }

        $grupotipoactividads = Grupotipoact::where("empresauid","=",auth()->user()->uidempresa)
                                           ->where("status","=","A")
                                           ->orderBy("parent")
                                           ->orderBy("orden")
                                           ->get();

        $programas=Programas::where('empresauid','=',auth()->user()->uidempresa)
                            ->where('uniopuid','=',auth()->user()->selectuniop)
                            ->where('status','=','A')
                            ->get();


        $dashboards=Dashboard::where('empresauid','=',auth()->user()->uidempresa)
                             ->where('id','=',$iudDB)
                             ->get();


        //$valoresGraficos=contarActividades($id);

        $valoresGraficos=graficarDashboard($iudDB,$fi,$ff,$status);
        
        $colores=Colores::all();
        $id="";
        $useruniopsId=auth()->user()->selectuniop;
        //dd($valoresGraficos);
        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('dashboard.view',compact('iudDB','valoresGraficos','notificVencidas','notificDay','id','dashboards','direccion','uniops','valor','usertipoacts','useruniops','tipoacts','useruniopsId','grupotipoactividads','programas','colores','menuDashboard','inicio','fin','status'));
    }
}
