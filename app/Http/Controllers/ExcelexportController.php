<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\ActividadExport;
use App\Exports\EmpresaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Collection;

use App\Tipoact;
use App\Actividad;
use App\Actividadtipodato;
use App\Actividadcontenido;
use App\Elemento;
use App\Documentos;
use App\Empresa;
use App\User;
use App\Unidadop;

use Carbon\Carbon;




class ExcelexportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function userExport(){
        $usuarios=new UsersExport;
        dd($usuarios);
        return Excel::download($usuarios, 'users.xlsx');
    }

    public function actividadesExcelExport($id){
        //$id='4e1c0ae2-e31e-46a8-8763-e8386266ecc5';

        $ta = Tipoact::where('uid','=',$id)->get();
        foreach($ta as $at){
            $df= Carbon::now();
            $dateFile=$df->format('dmY');
            $timeFile=$df->format('Hi');
            $filename=$at->titulo."_".$dateFile."-".$timeFile .".csv";
            //$filename=$at->titulo.".xlsx";
        }
        $arrContTipo=Actividadtipodato::where('tipoactid','=',$id)
                                      ->where('status','=','A')
                                      ->orderBy('posicion')->get();
        $headTables="Fecha";
        $i=1;

        foreach ($arrContTipo as $act) {
            $headTables=$headTables .';'.$act->etiqueta;
        }
        $headTables=$headTables.";Status";
        //dd($arrContTipo);
        $actTable = Actividad::join("tipoacts","actividads.tipoactividaduid","=","tipoacts.uid")
                             ->where("actividads.empresauid","=",auth()->user()->uidempresa)//filtro de version multiempresa
                             ->where("actividads.unidadopuid","=",auth()->user()->selectuniop)//filtro de version multiempresa
                             ->where("actividads.tipoactividaduid","=",$id)
                             ->orderBy("actividads.actividadinicio","asc")
                             ->select("actividads.id","actividads.actividadinicio","actividads.actividadfin","actividads.actividadstatus","tipoacts.tipoactcolor","tipoacts.titulo","tipoacts.id AS tipoactsid")
                             ->get();

        $arrayCont[0]=strtoupper($headTables);
        $y=1;

        foreach ($actTable as $actt) {
            $resumen="";
            $contenidos = Actividadcontenido::join("actividadtipocontenido","actividadtipocontenido.id","=","actividadcontenido.contenidotipoactuid")
                                            ->join("contenidotipo","contenidotipo.id","=","actividadtipocontenido.contenidotipoid")
                                            ->where('actividaduid','LIKE',$actt->id)
                                            ->where('actividadtipocontenido.status','=',"A")
                                            ->select('actividadcontenido.id','actividadcontenido.uniopuid','actividadcontenido.actividaduid','actividadcontenido.contenidotipoactuid','actividadcontenido.valortexto','actividadcontenido.valornumero','actividadcontenido.valorfecha','actividadcontenido.valorcarpeta','actividadcontenido.valorlista','actividadcontenido.idlista','actividadcontenido.valorgrupact','actividadtipocontenido.etiqueta','actividadtipocontenido.posicion','contenidotipo.tipodato','actividadtipocontenido.posicion','actividadtipocontenido.status')
                                            ->orderBy("actividadtipocontenido.posicion")
                                            ->get();
            $valorfecha="";
            $valorlista="";
            $valorAct="";
            $valorDoc="";
            foreach ($contenidos as $cont) {

                $valortexto=substr($cont->valortexto,0,350);

                /*if(!is_null($cont->valortexto) ){
                    $valortexto=substr($cont->valortexto,0,350);
                    $resumen= $resumen . $valortexto.';';
                }*/

                if(!is_null($cont->valorfecha) ){
                    $f=new Carbon($cont->valorfecha);
                    $valorfecha=$f->format('d/m/Y');
                    //$resumen= $resumen . $valorfecha.';';
                }
                if(!is_null($cont->valorlista) ){
                    $elementos=Elemento::where('id','LIKE',$cont->valorlista)->get();
                    foreach ($elementos as $elemento) {
                        $valorlista=$elemento->elemnombre;
                    }
                    //$resumen= $resumen . $valorlista.';';
                }

                if(!is_null($cont->valorgrupact) ){
                    //dd($cont->valorgrupact);
                    $actItems=Actividad::where("actividadgrupouid","=",$actt->id)->get();
                    $rowActItems=$actItems->count();
                    $valorAct=$rowActItems." actividad(es)";
                    //$resumen= $resumen . $valorAct.';';
                }

                if(!is_null($cont->valorcarpeta ) ){
                    //dd($cont->valorcarpeta);
                    $docItems=Documentos::where("actividaduid","=",$actt->id)->get();
                    $rowDocItems=$docItems->count();
                    $valorDoc=$rowDocItems." documento(s)";
                    //$resumen= $resumen . $valorDoc.';';

                }

               $resumen= $resumen . $valortexto . $cont->valornumero .  $valorlista. $valorAct. $valorDoc. $valorfecha.';';
               $valorfecha="";
               $valorlista="";
               $valorAct="";
               $valorDoc="";

            }
            $db = new Carbon($actt->actividadinicio);
            $fecha = $db->format('d/m/Y');
            if($resumen!=""){
                $arrayCont[$y]=$fecha.';'.$resumen.$actt->actividadstatus;
            }else{
                $arrayCont[$y]=$fecha.';'.$actt->actividadstatus;
            }
            $y++;
            $resumen="";
        }
        //dd($arrayCont);
        for ($i=0; $i < $y; $i++) {
            $dd=$arrayCont[$i];
            $arrayContenido[$i]=preg_split('|;|',$dd);
        }
        $verResult=preg_split('|;|',$headTables);

        $v1=collect($arrayContenido);

        $export = new ActividadExport([$v1]);
        //return $v1;
        return Excel::download($export, $filename);


    }

    public function empresaExport()
    {
        $empresas=Empresa::All();
        $datosEmpresa[0]=['uid'=>'UID',
                            'nombre'  =>'Nombre',
                            'nusers'  =>'Usuarios Activos',
                            'nunipos' =>'Unidades Op.',
                            'tsize'   =>'Espacio Disco',
                            'vigencia'=>'Fecha de Vigencia',
                            'status'  =>'Status'
            ];
            $i=1;
        foreach ($empresas as $empresa) {
            $unidadOp=Unidadop::where('empresauid','=',$empresa->id)
                              ->where('unidadopstatus','=','A')
                              ->get();
            $totalUniOP=$unidadOp->count();
            $usuarios=User::where('uidempresa','=',$empresa->id)
                          ->where('status','=','A')
                          ->get();
            $infoDom=FUNC_sizeCarpeta($empresa->id);
            $totalUser=$usuarios->count();
            $datosEmpresa[]=['uid'=>$empresa->id,
                            'nombre'=>$empresa->empresanombre,
                            'nusers'=>$totalUser,
                            'nunipos'=>$totalUniOP,
                            'tsize'=>$infoDom['tsize'],
                            'vigencia'=>$empresa->empresavigente,
                            'status'=>$empresa->empresastatus
            ];
            $i++;
        }
        $datEmpresas=collect($datosEmpresa);
        $df= Carbon::now();
        $dateFile=$df->format('dmY');
        $timeFile=$df->format('Hi');
        $filename="empresas_".$dateFile."-".$timeFile .".xlsx";

        $export = new EmpresaExport([$datEmpresas]);
        return Excel::download($export, $filename);
    }
}
