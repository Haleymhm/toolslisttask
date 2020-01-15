<?php

namespace App\Jobs;


use App\Actividad;
use App\Tipoact;

use App\Actividaduser;
use App\Contenidotipo;  /* Tipo de Datos */
use App\Actividadtipodato; /* tipoDato-tipoActividad */
use App\Actividadcontenido;
use App\Carpeta;
use App\ActividadPeriodica;
use Carbon\Carbon;

/*use Session;
use App\Unidadop;
use App\Useruniop;
use App\Usertipoact;
use App\Roleuserh;
use App\Documentos;
use App\Elemento;
use App\User;
use Illuminate\Support\Facades\Auth;*/
use Ramsey\Uuid\Uuid;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class CreateActividadPeriodica implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /*protected $idactividad;
    protected $model;*/
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
       /* $this->$idactividad = $idactividad;
        $this->model = $model;*/
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $userId=Auth()->user()->id;

        $actividadp=ActividadPeriodica::where('useruid','LIKE',$userId)->orderby('created_at','DESC')->take(1)->get();
        foreach($actividadp as $ap){
            $acperuid=$ap->id;
            $tauid=$ap->tipoactuid;
            $p=$ap->periocidad;
            $ubica=$ap->ubica;
            $descrip=$ap->descrip;
            $program = $ap->programauid;
            $tipoperiodo=$ap->tipoperiodo;
            $fip=new Carbon($ap->fechai);
            $ffp=new Carbon($ap->fechaf);
            $dateend = $fip->toDateString();
            $dateend = $fip->format('d-m-Y');
            $timeend = $ffp->toTimeString();
            $timeend = $ffp->format('H:i');
            $ftb = $dateend ." ".$timeend;
            $ff=new Carbon($ftb);
        }

        if(($tipoperiodo=='d') OR ($tipoperiodo=='v') OR ($tipoperiodo=='z')){
            $j= $fip->diffInDays($ffp);//Diferencia en Dias
            $div=$j/$p;
            $j=intval($div);

        }elseif($tipoperiodo=='s'){
            $j=$fip->diffInWeeks($ffp);//Diferencia en Semanas
            $div=$j/$p;
            $j=intval($div);
        }elseif($tipoperiodo=='m'){
            $j=$fip->diffInMonths($ffp);//Diferencia en Meses
            $div=$j/$p;
            $j=intval($div);
        }elseif($tipoperiodo=='a'){
            $j=$fip->diffInYears($ffp);//Diferencia en Años
            $div=$j/$p;
            $j=intval($div);
        }
        /** INICIO DEL CICLO GENERAL **/
$pacuzo=0;
        for($i=0; $i<$j; $i++){

            if($tipoperiodo=='d'){
                if($p<=1){
                    $actividadI=$fip->addDay();//sumar un dia a una fecha
                    $actividadF=$ff->addDay();//sumar un dia a una fecha
                    $zZz=$this->xXx($acperuid, $actividadI, $actividadF);

                }else{
                    $actividadI=$fip->addDays($p);//sumar un dia a una fecha
                    $actividadF=$ff->addDays($p);//sumar un dia a una fecha
                    $zZz=$this->xXx($acperuid, $actividadI, $actividadF);
                }

            }elseif($tipoperiodo=='s'){
                if($p<=1){
                    $actividadI=$fip->addWeek();//sumar un dia a una fecha
                    $actividadF=$ff->addWeek();//sumar un dia a una fecha
                    $zZz=$this->xXx($acperuid, $actividadI, $actividadF);
                }else{
                    $actividadI=$fip->addWeeks($p);//sumar un dia a una fecha
                    $actividadF=$ff->addWeeks($p);//sumar un dia a una fecha
                    $zZz=$this->xXx($acperuid, $actividadI, $actividadF);
                }

            }elseif($tipoperiodo=='m'){
                if($p<=1){
                    $actividadI=$fip->addMonth();//sumar un dia a una fecha
                    $actividadF=$ff->addMonth();//sumar un dia a una fecha
                    $zZz=$this->xXx($acperuid, $actividadI, $actividadF);
                }else{
                    $actividadI=$fip->addMonths($p);//sumar un dia a una fecha
                    $actividadF=$ff->addMonths($p);//sumar un dia a una fecha
                    $zZz=$this->xXx($acperuid, $actividadI, $actividadF);
                }
            }elseif($tipoperiodo=='a'){
                if($p<=1){
                    $actividadI=$fip->addYear();//sumar un dia a una fecha
                    $actividadF=$ff->addYear();//sumar un dia a una fecha
                    $zZz=$this->xXx($acperuid, $actividadI, $actividadF);
                }else{
                $actividadI=$fip->addYears($p);//sumar un dia a una fecha
                $actividadF=$ff->addYears($p);//sumar un dia a una fecha
                $zZz=$this->xXx($acperuid, $actividadI, $actividadF);
                }
            }elseif($tipoperiodo=='v'){

                if($p<=1){
                    $actividadI=$fip->addDay();//sumar un dia a una fecha
                    $actividadF=$ff->addDay();//sumar un dia a una fecha
                    if( ($actividadI->dayOfWeek === Carbon::SATURDAY) or ($actividadI->dayOfWeek === Carbon::SUNDAY)){


                    }else{
                        $zZz=$this->xXx($acperuid,$actividadI, $actividadF);
                        $pacuzo = $pacuzo+$zZz;
                    }
                }else{
                    $actividadI=$fip->addDays($p);//sumar un dia a una fecha
                    $actividadF=$ff->addDays($p);//sumar un dia a una fecha
                    if(($actividadI->dayOfWeek === Carbon::SATURDAY) or ($actividadI->dayOfWeek === Carbon::SUNDAY)){

                    }else{
                        $zZz=$this->xXx($acperuid, $actividadI, $actividadF);
                        $pacuzo = $pacuzo+$zZz;
                    }
                }
            }elseif($tipoperiodo=='z'){
                if($p<=1){
                    $actividadI=$fip->addDay();//sumar un dia a una fecha
                    $actividadF=$ff->addDay();//sumar un dia a una fecha
                    if ($actividadI->dayOfWeek === Carbon::SUNDAY){

                    }else{
                        $zZz=$this->xXx($acperuid, $actividadI, $actividadF);
                    }
                }else{
                    $actividadI=$fip->addDays($p);//sumar un dia a una fecha
                    $actividadF=$ff->addDays($p);//sumar un dia a una fecha
                    if ($actividadI->dayOfWeek === Carbon::SUNDAY){

                    }else{
                        $zZz=$this->xXx($acperuid, $actividadI, $actividadF);
                    }
                }
            }



        }/**** FIN DEL CICLO GENERAL  *****/

        //dd($tipoperiodo);
    }

    public function xXx($acperuid,$actividadI, $actividadF){

        $useruniopsId=auth()->user()->selectuniop;
        $destinationPath ='/upload/'.auth()->user()->uidempresa;
        $empresaUID=auth()->user()->uidempresa;
        $userId=Auth()->user()->id;
        $actividadp=ActividadPeriodica::where('id','=',$acperuid)->get();
        foreach($actividadp as $ap){
            $acperuid=$ap->id;
            $tauid=$ap->tipoactuid;
            $ubica=$ap->ubica;
            $descrip=$ap->descrip;
            $program = $ap->programauid;
            $status=$ap->status;
        }


        $tipoacts = Tipoact::where("uid","LIKE",$tauid)->get();
        $codTask=codigoActividad($tauid);
            foreach($tipoacts as $tipoact){ $colorAct=$tipoact->tipoactcolor; $tit=$tipoact->titulo;}
            $fiIB=str_replace('-','',$actividadI);
            $indxBusq=$fiIB.';'.$actividadI.';'.$tit.';'.$descrip.';'.$ubica.';'.$status;
            $actividadUid= Uuid::uuid4();
            $actividad= new Actividad;
            $actividad->id                = $actividadUid;
            $actividad->empresauid        = $empresaUID;
            $actividad->useruid           = $userId;
            $actividad->tipoactividaduid  = $tauid;
            /*$actividad->actividadtitulo   = $request->actividadtitulo;*/
            $actividad->actividaddescip   = $descrip;
            $actividad->actividadlugar    = $ubica;
            $actividad->unidadopuid       = $useruniopsId;
            $actividad->actividadinicio   = $actividadI;
            $actividad->actividadfin      = $actividadF;
            $actividad->actividadcolor    = $colorAct;
            $actividad->actividadstatus   = "A";
            $actividad->actperiocidauid   = $acperuid;
            $actividad->programauid       = $program;
            $actividad->actidadbusq       = $indxBusq;
            $actividad->actividadcodigo   = $codTask;

            if($actividad->save()){
                $ret=1;
                $empresa=numRegEmp();
            }else{
                $ret="no se crea la activiad";
            }
            $idactividad=$actividadUid;

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
                        'carpetanombre'=>''
                    ]);
                    $ac=$actividadContenido->id;
                    $nc=$carpeta->id;
                    $actividadContenido2=Actividadcontenido::find($ac);
                    $actividadContenido2->valorcarpeta=$nc;
                    $actividadContenido2->save();
                }
                }
            }
            //dd($acperuid);
            return($ret);
    } // jjsjsksk
}
