<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Roleuserh;
use App\Usertipoact;
use App\Useruniop;
use App\Tipoact;
use App\Unidadop;
use App\Grupotipoact;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Image;
use Session;
use App\Programas;
use App\Dashboard;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $userid=auth()->user()->id;
        $uidempresa=auth()->user()->uidempresa;
        $useruniopsId=auth()->user()->selectuniop;
        $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
        ->where("user_id","LIKE",$userid)
        ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
        ->get();

        foreach ($rolesActivo as $rolActivo) { $valor= $rolActivo->slug;   }

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
                             ->where('status','=','A')
                             ->get();

        $menuDashboard=dashboardMenu();
        $empresas=Empresa::find($uidempresa);
        $id="";
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('empresa.edit',compact('id','notificVencidas','notificDay','empresas','uniops','direccion','valor','usertipoacts','useruniops','tipoacts','useruniopsId','grupotipoactividads','programas','menuDashboard'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $empresa = Empresa::find($id);
        $empresa->rutrif            = $request->rutrif;
        $empresa->empresanombre     = $request->empresanombre;
        $empresa->empresadireccion  = $request->empresadireccion;
        $empresa->empresatelefono   = $request->empresatelefono;
        $empresa->empresaemail      = $request->empresaemail;

        if( $empresa->save() ){
            Session::flash('update','Los Datos se ACTUALIZARON Exitosamente');
            return redirect()->route('home.index');
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
            return redirect()->route('home.index');
        }

    }


    public function updatelogo(Request $request)
    {

         $id= auth()->user()->uidempresa;
         $ruta = public_path().'/upload/avatar/';
         $date = new Carbon(now());
         $dateupload= $date->format('Ymd_hhmmss');
          $this->validate($request, [
                'photo' => 'required|image'
            ]);

         $imagenOriginal = $request->file('photo');
         $extension = $imagenOriginal->getClientOriginalExtension();
         if (isset($imagenOriginal)){
            
            $imagen = Image::make($imagenOriginal);
            $tmp = auth()->user()->uidempresa;
            $temp_name = $tmp.$dateupload . '.' . $extension;

            $uploadfile=$imagen->save($ruta . $temp_name, 100);

            $usuario = Empresa::find($id);
            $usuario->empresalogo = $temp_name;
            $saved=$usuario->save();
            $ruta=asset('/upload/avatar/'.$temp_name);
            $data['success'] = $saved;
            $data['path'] = $ruta;

            return $data;
        }


    }
}
