<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\User;
use App\Roleuserh;
use App\Tipoact;
use App\Unidadop;
use App\Permisorolh;
use App\Usertipoact;
use App\Useruniop;
use App\Programas;
use App\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Image;
use Session;
use Alert;


class PerfilController extends Controller
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
        return redirect()->route('home.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /**/

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
        //
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
        $user = User::findOrFail($userid);
       /* $user->selecttipact = $id;
        $user->save();*/

        /**** Todas las unidades operativas de una empresa ****/
        $uniops =Unidadop::where("empresauid","LIKE",$uidempresa)
                         ->where("unidadopstatus","LIKE","A")->get();
                         foreach ($uniops as $uniop) {
                            if($uniop->unidadopuid==$useruniopsId){
                                $direccion= $uniop->unidadopnombre;
                            }

                        }
        //dd($uniops);
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

        // ruta de las imagenes guardadas
        $programas=Programas::where('empresauid','=',$uidempresa)
                            ->where('uniopuid','=',$useruniopsId)
                            ->where('status','=','A')
                            ->get();

        $usuario = User::find($userid);
        $id="";
        $menuDashboard=dashboardMenu();
        $notificVencidas=actividadesVencidas();
        $notificDay=actividadesDay();
        return view('perfil.edit',compact('id','notificVencidas','notificDay','usuario','direccion','valor','usertipoacts','useruniops','unidadesops','useruniopsId','uniops','tipoacts','programas','menuDashboard'));
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

        $usuario = User::find($id);
        if($request->passw!=""){
            $pw=Hash::make($request->passw);
            $usuario->password=$pw;
        }
        $usuario->timezone   = $request->timezone;
        $usuario->language   = $request->idioma;
        $usuario->vista      = $request->vista;

        if( $usuario->save() ){

            Session::flash('save','Los Datos se ACTUALIZARON Exitosamente');
            return redirect()->route('home.index');
        }else{
            Session::flash('error','Ups, ha ocurrido un error');
        }
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

    public function updatephoto(Request $request)
    {

         $id= auth()->user()->id;
         $ruta = public_path().'/upload/avatar';
         $date = new Carbon(now());
         $dateupload= $date->format('Ymd_hhmmss');
          $this->validate($request, [
                'photo' => 'required|image'
            ]);

         $imagenOriginal = $request->file('photo');
         $extension = $imagenOriginal->getClientOriginalExtension();
         if (isset($imagenOriginal)){

            $imagen = Image::make($imagenOriginal);
            $tmp="/". auth()->user()->uidempresa . "-".auth()->user()->id;
            $temp_name = $tmp.$dateupload . '.' . $extension;

            $uploadfile=$imagen->save($ruta . $temp_name, 100);

            $usuario = User::find($id);
            $usuario->photo = $temp_name;
            $saved=$usuario->save();
            $ruta=asset('/upload/avatar'.$temp_name);
            $data['success'] = $saved;
            $data['path'] = $ruta;

            return $data;
        }


    }


}
