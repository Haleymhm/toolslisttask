<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tipoact;
use App\Roleuserh;

class Menu extends Model
{
    protected $table="groupstipact";
   
    public static function GrupoMenu($parent, $uidempresa, $userid, $rol)
    { 
         $grupo = Menu::select('id','titulo','parent','orden','uid')
            ->where('status','LIKE' ,'A')
            ->where('empresauid','LIKE',$uidempresa)
            ->where('parent','=' , $parent)
            ->orderby('parent')
            ->orderby('orden')
            ->orderby('titulo')
            ->get()
            ->toArray();   

        $menu_return = [];
        foreach($grupo as $line_grupo) {
            $tipoact = Menu::TipoActMenu($line_grupo['id'], $uidempresa, $userid, $rol);            
            $submenu = Menu::GrupoMenu($line_grupo['id'], $uidempresa, $userid, $rol);
            $line_grupo['tipo'] = 'G';            
            if ($submenu != [] or $tipoact != [])  {            
                $item = [ array_merge($line_grupo, ['submenu' => array_merge($submenu, $tipoact)]) ];
                $menu_return = array_merge($menu_return, $item);
            }
        }
        return $menu_return; 
    }

    public static function TipoActMenu($parent_id, $uidempresa, $userid, $rol) {
        if($rol=='root' or $rol =='admin'){
            $datatipo = Tipoact::select('id','titulo','parent','orden','uid')
                ->where('status','LIKE' ,'A')
                ->where('empresauid','LIKE',$uidempresa)
                ->where('parent', '=' , $parent_id)
                ->orderby('parent')
                ->orderby('orden')
                ->orderby('titulo')
                ->get()
                ->toArray();
        }else{            
            $datatipo = Tipoact::join("tipoact_user","tipoacts_id","=","tipoacts.id")
                                ->where("user_id","LIKE",$userid)
                                ->select("tipoacts.id","tipoacts.titulo","tipoacts.parent","tipoacts.orden","tipoacts.uid")
                                ->where('status','LIKE' ,'A')
                                ->where('empresauid','LIKE',$uidempresa)
                                ->where('tipoacts.parent', '=' , $parent_id)
                                ->orderby('tipoacts.parent')
                                ->orderby('tipoacts.orden')
                                ->orderby('tipoacts.titulo')
                                ->get()
                                ->toArray();
        }
        $return_datatipo = [];
        foreach($datatipo as $line_datatipo) {
            $line_datatipo['tipo'] = 'A';  
            array_push($return_datatipo, $line_datatipo);        
        }
        return $return_datatipo;
    }
   
    public static function menus()
    {
        $uidempresa=auth()->user()->uidempresa;
        $userid=auth()->user()->id;
        $rolesActivo=Roleuserh::join("roles","roles.id","=","role_user.role_id")
                             ->where("user_id","LIKE", $userid)
                             ->select("roles.slug","roles.name","role_user.role_id","role_user.user_id")
                             ->get();

        foreach ($rolesActivo as $rolActivo) { $rol= $rolActivo->slug; }
        $datagrupos = Menu::GrupoMenu(0, $uidempresa, $userid, $rol);       
        return $datagrupos; 
    }
}
