<?php

$id=$request->idactividaddt;
        $vareditacyper = $request->textoptionsEdit;
        $uideditacyper = $request->actperiocidauid;
        $fi=$request->dateinicio." ".$request->timeinicio2;
        $ff=$request->datefin." ".$request->timefin2;
        $actividadI=new Carbon($fi);
        $actividadF=new Carbon($ff);

        $actividad = Actividad::find($id);

        $actividad->actividadlugar    = $request->place;
        $actividad->actividaddescip   = $request->actividaddescipdetale;
        $actividad->actividadinicio   = $actividadI;
        $actividad->actividadfin      = $actividadF;
        $actividad->useruid           = auth()->user()->id;
        $actividad->actividadstatus   = $request->status;
        $actividad->programauid       = $request->programa;
        $actividad->actividadlugar    = $request->place;


        $saved=$actividad->save();

        $uidActPer=$actividad->actperiocidauid;
        if($uidActPer==""){$actper="NO"; }else{$actper="SI";}
        
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
        if($request->finperiodo!=""){
            $finperiodo  = $request->finperiodo." ".$request->timefin2;
            $periodofin=new Carbon($finperiodo);
        }else{$periodofin = "";}





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
                    $msg="<b>ID PROGRAMA: </b>".$program."<br /><b>ACTUAL_P:</b><br /> <b>ActividadPeriodicaUID=></b>".$uideditacyper;//.$vareditacyper."<br /><b>UID Periodo: </b>".$uideditacyper;
                    //return $msg;
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
                    $actPeriodica->programauid = $program;
                    $actPeriodica->ubica       = $ubica;
                    $actPeriodica->descrip     = $descrip;
                    $actPeriodica->status     = $request->status;

                    $actPeriodica->save();

                    $job = new EditActividadPeriodica();
                    dispatch($job)->delay(now()->addMinutes(1));
                }



        }

      Session::flash('save','Los Registro fue Editaron Exitosamente');
      //return redirect('actividad/'.$id.'/edit');
      $data['ncont']=$request->ncontenido;
      $data['isAP']=$actper;
      $data['actual']=$vareditacyper;
      $data['statusclass']=$statusclass;
      $data['statustext']=$statustext;
      $data['descrip'] = $request->actividaddescipdetale;
      $data['success'] = $saved;
      return $data;
?>
