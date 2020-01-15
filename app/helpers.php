<?php
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
//use Illuminate\Support\Collection;


use App\Actividad;
use App\Actividadtipodato;
use App\Actividadcontenido;
use App\Actividaduser;
use App\Contenidotipo;
use App\DBItem;
use App\Empresa;
use App\Tipoact;
use App\Elemento;
use App\Documentos;
/**
 * Archcivo que aplica nuevas funcionalidades las implementadas por laravel
 *
 * @author  Haleym Hidalgo <haleymhm@gmail.com>
 * @version 0.0.1
 * @copyright 2019 Bunttech
 */

    function active($path){
    /**
     * Activa la clase  de bootstrap que indica que un enlace esta activo en el menu
     *
     * @author Haleym Hidalgo <haleymhm@gmail.com>
     * @param  string $path  valor que indica a que enlace hizo click el asuario.
     * @return string active valor que indica en la vista que la clase esta activa
     *
     */
        return request()->is($path) ? 'active' : '';
    }

   function graficarDashboard($id, $fechaI, $fechaF, $status){
        $i=0;

        $arrayG[]=['itemTipo'=>'X','dbItem'=>'','hAxis'=>'','vAxis'=>'','xXx'=>'','TipAct'=>'','itemColor'=>'','itemIcono'=>'','itemTotal'=>'','idItem'=>'','tipog'=>'','datos'=>'',];
        $itemEtiqueta='';$color=''; $icono=''; $nTotal=0; $grupopor=''; $desde=''; $xXx='';
        $arrayDatos[]=['item'=>'', 'valor'=>0, 'TipoActi'=>'' ];

        $dbItems= DBItem::where('empresauid','=',auth()->user()->uidempresa)
                        ->where('dashboarduid','=',$id)
                        ->where('status','=','A')
                        ->orderBy('itempos','DESC')
                        ->get();

        foreach ($dbItems as $idb) {
            //dd('VARIABLES DEL HELPER   T   F-Inicio: '.$fechaI.'  F-Fin: '.$fechaF.'   Status: '.$status);

            if($idb->itemtipo=='T'){

                $acts= Actividad::where('empresauid','=',auth()->user()->uidempresa)
                                ->where('unidadopuid','=',auth()->user()->selectuniop)
                                ->where('tipoactividaduid','=',$idb->tipoactuid)
                                ->whereBetween('actividadinicio', [$fechaI, $fechaF])
                                ->where('actividadstatus','=',$status)
                                ->get();
                $nTotal=$acts->count();
                $tipActividad= Tipoact::where('empresauid','=',auth()->user()->uidempresa)->where('uid','=',$idb->tipoactuid)->get();
                foreach ($tipActividad as $tA) {
                    $tp=$tA->titulo;
                    $color=$tA->tipoactcolor;
                    $icono=$tA->icono;
                }
            }

            if($idb->itemtipo=='G'){
                
                $tipActividad= Tipoact::where('empresauid','=',auth()->user()->uidempresa)->where('uid','=',$idb->tipoactuid)->get();
                foreach ($tipActividad as $tA) { $tp = $tA->titulo;}                           
                    $arrayDatos=AgruparGrafico($idb->tipoactuid, $idb->agrupartipocontuid, $idb->itemdesde, $fechaI, $fechaF, $status, $idb->itemoperacion);
                    
                    $grupopor = etiqueta_contenido(auth()->user()->uidempresa, $idb->tipoactuid, $idb->agrupartipocontuid);

                    if($idb->itemoperacion=="AS")
                       $itemEtiqueta = 'Total '.etiqueta_contenido(auth()->user()->uidempresa, $idb->tipoactuid,$idb->itemdesde);  
                    
                    if($idb->itemoperacion=="AP")
                        $itemEtiqueta = 'Promedio '.etiqueta_contenido(auth()->user()->uidempresa, $idb->tipoactuid,$idb->itemdesde);  
                    
                    if($idb->itemoperacion=="AC")                   
                        $itemEtiqueta = etiqueta_contenido(auth()->user()->uidempresa, $idb->tipoactuid,$idb->agrupartipocontuid);
                       
            }
/* AQUI EL FIN DEL ID TIPO ITEM */

            $arrayG[]= ['itemTipo'=>$idb->itemtipo,
                        'dbItem'=>$itemEtiqueta,
                        'hAxis'=>$grupopor,
                        'vAxis'=>$desde,
                        'xXx'=>$xXx,
                        'TipAct'=>$tp,
                        'itemColor'=>$color,
                        'itemIcono'=>$icono,
                        'itemTotal'=>$nTotal,
                        'idItem'=>$idb->id,
                        'tipog'=>$idb->itemgrafico,
                        'datos'=>$arrayDatos];

            unset($arrayDatos);
            $arrayDatos[]=['item'=>'', 'valor'=>0, 'TipoActi'=>'' ];
        }

        $arrayGraficableS=collect($arrayG);
        $arrayGraficable=$arrayGraficableS->sortBy('dbItem');
        return ($arrayGraficable);

    }

    function AgruparGrafico($uidTA, $idContenido, $desde, $fechaI, $fechaF, $status, $itemoperacion) {
        $arrayDatos = [];

        $columna = columna_tipodato(tipodato_contenido(auth()->user()->uidempresa,  $uidTA, $idContenido));
        if ($columna != 'error') {
            $query_total = 'count(*) as total';
            $grupos_grafico = Actividadcontenido::select($columna.' as nombre', DB::raw($query_total))
                            ->join('actividads', 'actividadcontenido.actividaduid', '=', 'actividads.id')    
                            ->where('actividads.empresauid', '=', auth()->user()->uidempresa) 
                            ->where('actividads.unidadopuid', '=', auth()->user()->selectuniop)
                            ->where('tipoactividaduid','=', $uidTA)
                            ->where('contenidotipoactuid', '=', $idContenido)         
                            ->whereBetween('actividads.actividadinicio', [$fechaI, $fechaF])                   
                            ->groupBy($columna)
                            ->get();

            foreach($grupos_grafico as $grupo_grafico) {

                //sólo válido para listas
                $nombre = '';
                switch($columna) {
                    case 'valorlista':
                        $listadouid = id_lista(auth()->user()->uidempresa, $uidTA, $idContenido);
                        $nombre = item_listado(auth()->user()->uidempresa, $listadouid, $grupo_grafico->nombre);
                        break;
                    case 'valorfecha':
                        $nombre = date('d-m-Y', strtotime($grupo_grafico->nombre));
                        break;
                    default:
                        $nombre = $grupo_grafico->nombre;
                }




                if ($itemoperacion == 'AC')
                    $valor = $grupo_grafico->total;
                else
                {
                    $lista_grupo = Actividadcontenido::select('actividaduid')
                        ->join('actividads', 'actividadcontenido.actividaduid', '=', 'actividads.id')    
                        ->where('actividads.empresauid', '=', auth()->user()->uidempresa) 
                        ->where('actividads.unidadopuid', '=', auth()->user()->selectuniop)
                        ->where('tipoactividaduid','=', $uidTA)
                        ->where('contenidotipoactuid', '=', $idContenido)                   
                        ->whereBetween('actividads.actividadinicio', [$fechaI, $fechaF]) 
                        ->where($columna, '=', $grupo_grafico->nombre)
                        ->get()
                        ->toArray();
                       
                    if ($itemoperacion == 'AS')
                        $valor = Actividadcontenido::whereIn('actividaduid', $lista_grupo)
                            ->where('contenidotipoactuid', '=', $desde)
                            ->sum('valornumero');
                     else //'AP'
                            $valor = Actividadcontenido::whereIn('actividaduid', $lista_grupo)
                                ->where('contenidotipoactuid', '=', $desde)
                                ->avg('valornumero');
                }

                $arrayDatos[]=['item'=>$nombre,
                    'valor'=>$valor,
                    'TipoActi'=>' ', 
                    ];
            }           
        }

        if($arrayDatos == null)
            $arrayDatos[]=['item'=>'', 'valor'=>0, 'TipoActi'=>'' ];
       
        return $arrayDatos;
    }


    function actividadesVencidas(){
        $totalV=0;
        $db = Carbon::now();
        $datebegin=$db->format('Y-m-d')." 23:59";
        $tipActividad= Tipoact::where('empresauid','=',auth()->user()->uidempresa)
                                  ->where('status','=','A')
                                  ->get();

        foreach ($tipActividad as $tipAct) {

            $actVencidas=Actividad::where('empresauid','=',auth()->user()->uidempresa)
                                      ->where('unidadopuid','=',auth()->user()->selectuniop)
                                      ->where('tipoactividaduid','=',$tipAct->uid)
                                      ->where('actividadinicio','<',$datebegin)
                                      ->where('actividadstatus','=','A')
                                      ->get();

            $cant=$actVencidas->count();
            $totalV=$totalV + $cant;
            if($cant>0){
                $arrayVencidas[]=['uidTipAct'=>$tipAct->uid,'TipAct'=>$tipAct->titulo ,'Color'=>$tipAct->tipoactcolor,'Cant'=>$cant];
            }

        }
        $arrayVencidas[]=['uidTipAct'=>'0','TipAct'=>'0','Color'=>'0','Cant'=>$totalV];
        $actVencidas=collect($arrayVencidas);
        $ActividadesVencidas=$actVencidas->sortBy('uidTipAct');

        return ($ActividadesVencidas);

    }

    function actividadesDay(){

        $totalV=0;
        $db= Carbon::now();
        $datebegin=$db->format('Y-m-d')." 00:00";
        $dateend=$db->format('Y-m-d')." 23:59";
        $tipActividad= Tipoact::where('empresauid','=',auth()->user()->uidempresa)
                                  ->where('status','=','A')
                                  ->get();
        //$arrayVencidas[]=['uidTipAct'=>'','TipAct'=>'','Color'=>'','Cant'=>''];
        foreach ($tipActividad as $tipAct) {

            $actVencidas=Actividad::where('empresauid','=',auth()->user()->uidempresa)
                                      ->where('unidadopuid','=',auth()->user()->selectuniop)
                                      ->where('tipoactividaduid','=',$tipAct->uid)
                                      ->where('actividadinicio','=>',$datebegin)
                                      ->where('actividadinicio','=<',$dateend)
                                      ->where('actividadstatus','=','A')
                                      ->get();

            $cant=$actVencidas->count();
            $totalV=$totalV + $cant;
            if($cant>0){
                $arrayVencidas[]=['uidTipAct'=>$tipAct->uid,'TipAct'=>$tipAct->titulo ,'Color'=>$tipAct->tipoactcolor,'Cant'=>$cant];
            }

        }
        $arrayVencidas[]=['uidTipAct'=>'0','TipAct'=>'0','Color'=>'0','Cant'=>$totalV];
        $actVencidas=collect($arrayVencidas);
        $ActividadesdelDia=$actVencidas->sortBy('uidTipAct');

        return ($ActividadesdelDia);
    }

    function dashboardMenu(){
        $vardash=App\Dashboard::where('empresauid','=',auth()->user()->uidempresa)
                              ->where('status','=','A')
                              ->get();
        return ($vardash);
    }



    function validarCierre($id){
    /** REVISA QU LOS DATOS MARCADOS COMO OBLIGARIO ESTEN COMPLETADOS **/
        $actividad = Actividad::find($id);
        $ta=$actividad->tipoactividaduid;
        $actConts=Actividadtipodato::join('contenidotipo','actividadtipocontenido.contenidotipoid','=','contenidotipo.id')
                                   ->where('tipoactid','=',$ta)
                                   ->where('empresauid','=',auth()->user()->uidempresa)
                                   ->where('obligatorio','=','SI')
                                   ->select('actividadtipocontenido.id','actividadtipocontenido.obligatorio','contenidotipo.tipodato','contenidotipo.id AS contenidotipoid')
                                   ->get();


        $vacio=0;
        foreach($actConts as $actCont){
            $idcon=$actCont->id;
            $tipodato=$actCont->tipodato;

            $regDatos=Actividadcontenido::where('tipoactuid','=',$ta)
                                        ->where('empresauid','=',auth()->user()->uidempresa)
                                        ->where('actividaduid','=',$id)
                                        ->where('contenidotipoactuid','=',$idcon)
                                        ->get();

            foreach($regDatos as $regDato){
                if(($tipodato=="text") AND ($regDato->valortexto==null)){ $vacio++; }
                if(($tipodato=="textarea") AND ($regDato->valortexto==null)){ $vacio++; }
                if(($tipodato=="date") AND ($regDato->valorfecha==null)){ $vacio++; }
                if(($tipodato=="numeric") AND ($regDato->valornumero==null)){ $vacio++; }
                if(($tipodato=="monto") AND ($regDato->valornumero==null)){ $vacio++; }
                if(($tipodato=="lista") AND ($regDato->valorlista==null) OR ($regDato->valorlista=="0")){ $vacio++; }
                if($tipodato=="documento") {
                    $documentos=Documentos::where('empresauid','=',auth()->user()->uidempresa)
                                          ->where('actividaduid','=',$id)
                                          ->where('carpetauid','=',$regDato->valorcarpeta)
                                          ->get();
                    $docs=$documentos->count();
                    if($docs==0){$vacio++;}
                }
                if($tipodato=="actividad") {
                    $actividades = Actividad::where('empresauid','=',auth()->user()->uidempresa)
                                            ->where('actividadgrupouid','=',$regDato->valorgrupact)
                                            ->get();

                    $acts=$actividades->count();
                    if($acts==0){$vacio++;}
                }
            }
        }

        return $vacio;
    }/*** FIN DE LA FUNCION ***/

    /********************************************************************************/
    /*           FUNCION PARA SACAR EL TAMAÑO DEL HDD DE UNA CARPETA                */
    /********************************************************************************/
    function FUNC_sizeCarpeta($id_empresa) {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $dir2 = public_path() . "\upload\ "; //en windows
            //dd('Este un servidor usando Windows!');
        } else {
            $dir2 = public_path() . "/upload/";
            //dd('Este es un servidor que no usa Windows!');
        }
        //$dir2 = public_path() . "/upload/"; //en windows
        //$dir2 = public_path() . "\upload\ "; //en windows
        $dir =trim($dir2) . $id_empresa;
        $size = 0; $files = 0; $dirs=0;
        if (File::exists($dir )) {
            $dh = opendir($dir);
            while (($file = readdir($dh)) !== false){
                if ($file != "." and $file != "..") {
                    $path = $dir."/".$file;
                    if (is_dir($path)){
                        $size += dirsize($path);
                        $dirs ++;
                    }elseif (is_file($path)){
                        $size += filesize($path);
                        $files ++;
                    }
                }
            }
            closedir($dh);
        }
        //$size = number_format($size,2);
        if($size > 1024){
            $size  = $size/1024;
            $total = number_format($size,2).' kB';
            if($size > 1204){
                $size  = $size/1024;
                $total = number_format($size,2).' MB';
                if($size > 1204){
                    $size  = $size/1024;
                    $total = number_format($size,2). 'GB';
                }
            }
        }else{$total = $size.' B';}

        //$size = number_format($size/1048576,2);
        $infoDir=['id'=>$id_empresa,'ndir'=>$dirs,'nfiles'=>$files,'tsize'=>$total];
        return $infoDir;
    }

    function actividadBuesquedaIndex($id, $textIndex,$ta){
        $tipoactividads = Tipoact::where('uid','=',$ta)->get();
        foreach ($tipoactividads as $tipoactividad) {
            $wta=$tipoactividad->titulo;
        }

        $rolActiviadUser=Actividaduser::where('empresauid','=',auth()->user()->uidempresa)
                                      ->where('actividaduid','=',$id)
                                      ->get();
        $Usuarios="";
        foreach ($rolActiviadUser as $rolActiviadUse) {
            $Usuarios= $Usuarios . $rolActiviadUse->responsable.';';
        }
        $sSs=$textIndex .';'.$Usuarios.''.$wta;
        $actividad = Actividad::find($id);
        $actividad->actidadbusq = $sSs;
        $saved=$actividad->save();
        return($sSs);
    }
	
	
	 //tipo de dato de la columna (número)
    function tipodato_contenido($empresauid, $tipoactid, $id) {
        return Actividadtipodato::where('id', $id)
                            ->where('empresauid', '=', $empresauid)
                            ->where('tipoactid', '=', $tipoactid)                                   
                            ->value('contenidotipoid');                           
    }

    function etiqueta_contenido($empresauid, $tipoactid, $id) {
        return Actividadtipodato::where('id', $id)
            ->where('empresauid', '=', $empresauid)
            ->where('tipoactid', '=', $tipoactid)                                   
            ->value('etiqueta');     
    }

    //nombre de columna que se debe consultar
    function columna_tipodato($tipo) {
        if ($tipo == 1 || $tipo==3 || $tipo == 8)
        return "valortexto";
            else
                if ($tipo == 2 || $tipo == 5)
                    return "valornumero";
                else
                    if ($tipo == 4)
                        return "valorfecha";
                    else
                        if ($tipo == 7)
                            return "valorlista";
                        else
                            return "error";
    }

    function item_listado($empresauid, $listadouid, $id) {
        return Elemento::where ('empresauid', $empresauid)
            ->where('listadouid', $listadouid)
            ->where('id', $id)
            ->value('elemnombre');
    }


    function id_lista($empresauid, $tipoactid, $id) {
        return ActividadTipoDato::where('empresauid', $empresauid)
            ->where('tipoactid', $tipoactid)
            ->where('id', $id)
            ->value('idlista');
    }

    function codigoActividad($id){

        $empresa=Empresa::find(auth()->user()->uidempresa);
        $wWw=$empresa->empresanumero;
        $strl=strlen($wWw);
        if($strl==1){  $codact='000' . $wWw;}
        elseif($strl==2){ $codact='00' . $wWw;}
        elseif($strl==3){ $codact='0' . $wWw;}
        $tipoacts = Tipoact::where("uid","=",$id)->get();
        foreach($tipoacts as $tipoact){
          $prefix=$tipoact->prefijo;
        }
        $codact=$prefix .'-' . date('Ym') . $codact;
        return($codact);
    }

    function numRegEmp(){
        $empresa=Empresa::find(auth()->user()->uidempresa);
        $wWw=$empresa->empresanumero;
        $wWw=$wWw+1;
        $empresa->empresanumero=$wWw;
        $empresa->save();
    }

 ?>
