<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Menu;
use Caffeinated\Shinobi\Models\Permission;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $permisos=Permission::select("slug")->get();
        //$vistas = ['home', 'welcome', 'actividad.index', 'cusuario.index','roles.index','tipact.index','uniope.index'];

        $vistas=[];
        foreach ($permisos as $permiso) {
            $vistas[]=$permiso->slug;
        }
        //dd($vistas);
        view()->composer($vistas, function($view) {
            $view->with('menus', Menu::menus());
        });
        //dd($vistas);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
