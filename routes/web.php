<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
Auth::routes();

Route::get('/','HomeController@index');
Route::get('/logout', function () {
    Auth::logout();
    Session::flash('alert-uniope','Alertas');
    return redirect('login');
    Session::flush();
});

Route::get('/register', function () {
    Auth::logout();
    return redirect('login');
});

Route::get('export/userxls', 'ExcelexportController@export')->name('export.userxls');
Route::get('export/actividadxls/{id}', 'ExcelexportController@actividadesExcelExport')->name('export.actividadxls');
Route::get('cpanel/empresa/export', 'ExcelexportController@empresaExport')->name('export.empresaexport');

Route::resource('/home','HomeController');
Route::resource('/graficos','GraficosController');
Route::resource('/tabla','TablaController');

Route::resource('/documentos','DocumentosController');
Route::get('/documentos/tabladoc/{id}/list','DocumentosController@tablaDocumento')->name('documentos.tabladocumento');
Route::get('/documentos/tabladoc/{id}/view','DocumentosController@viewDocumento')->name('documentos.viewdocumento');
Route::get('/documentos/tabladoc/{id}/doc','DocumentosController@unicoDocumento')->name('documentos.unicodocumento');

Route::resource('/dashboard', 'DashboardController');
Route::post('/dashboard/addcontenido', 'DashboardController@addcontenido')->name('dashboard.addcontenido');
Route::post('/dashboard/editcontenido', 'DashboardController@editcontenido')->name('dashboard.editcontenido');
Route::get('/dashboard/gettc/{id}', 'DashboardController@getTipoContenido');
Route::get('/dashboard/gettc2/{id}', 'DashboardController@getTipoContenido2');
Route::post('/dashboard/view', 'DashboardController@view')->name('dashboard.view');


Route::get('actividad/buscartask', 'ActividadController@buscartask')->name('actividad.buscartask');
Route::get('actividad/searchtask', 'ActividadController@searchtask')->name('actividad.searchtask');
Route::post('actividad/asociartask', 'ActividadController@asociartask')->name('actividad.asociartask');

/* SISTEMA DE ROLES Y PERMISOS
 PAQUETE SHINOBI
 */
Route::middleware(['auth'])->group(function () {
	Route::get('/documentos/tabladoc/{id}/list','DocumentosController@tablaDocumento')->name('documentos.tabladocumento');
	Route::get('/documentos/tabladoc/{id}/view','DocumentosController@viewDocumento')->name('documentos.viewdocumento');
	Route::get('/documentos/tabladoc/{id}/doc','DocumentosController@unicoDocumento')->name('documentos.unicodocumento');

//Roles
	Route::post('roles/store', 'RolesController@store')->name('roles.store')
		->middleware('permission:roles.create');

	Route::get('roles', 'RolesController@index')->name('roles.index')
		->middleware('permission:roles.index');

	Route::get('roles/create', 'RolesController@create')->name('roles.create')
		->middleware('permission:roles.create');

	Route::put('roles/{role}', 'RolesController@update')->name('roles.update')
		->middleware('permission:roles.edit');

	Route::get('roles/{role}', 'RolesController@show')->name('roles.show')
		->middleware('permission:roles.show');

	Route::delete('roles/{role}', 'RolesController@destroy')->name('roles.destroy')
		->middleware('permission:roles.destroy');

	Route::get('roles/{role}/edit', 'RolesController@edit')->name('roles.edit')
		->middleware('permission:roles.edit');

	//Users
	Route::post('cusuario/store', 'CusuarioController@store')->name('cusuario.store')
		->middleware('permission:cusuario.create');

	Route::get('cusuario/create', 'CusuarioController@create')->name('cusuario.create')
		->middleware('permission:cusuario.create');

	Route::get('cusuario', 'CusuarioController@index')->name('cusuario.index')
		->middleware('permission:cusuario.index');

	Route::put('cusuario/{user}', 'CusuarioController@update')->name('cusuario.update')
		->middleware('permission:cusuario.edit');

	Route::get('cusuario/{user}', 'CusuarioController@show')->name('cusuario.show')
		->middleware('permission:cusuario.show');

	Route::delete('cusuario/{user}', 'CusuarioController@destroy')->name('cusuario.destroy')
		->middleware('permission:cusuario.destroy');

	Route::get('cusuario/{user}/edit', 'CusuarioController@edit')->name('cusuario.edit')
		->middleware('permission:cusuario.edit');


	//Actividades

	Route::get('actividad/', 'ActividadController@index')->name('actividad.index')
		->middleware('permission:actividad.index');

	Route::get('actividad/create', 'ActividadController@create')->name('actividad.create')
		->middleware('permission:actividad.create');

	Route::post('actividad/store', 'ActividadController@store')->name('actividad.store')
		->middleware('permission:actividad.create');

	Route::get('actividad/{user}/edit', 'ActividadController@edit')->name('actividad.edit')
		->middleware('permission:actividad.edit');

	Route::put('actividad/{id}', 'ActividadController@update')->name('actividad.update')
        ->middleware('permission:actividad.edit');

    Route::post('actividad/updateActividad', 'ActividadController@updateActividad')->name('actividad.updateActividad')
            ->middleware('permission:actividad.updateActividad');

	Route::delete('actividad/{role}', 'ActividadController@destroy')->name('actividad.destroy')
		->middleware('permission:actividad.destroy');

	Route::get('actividad/{id}', 'ActividadController@show')->name('actividad.show')
		->middleware('permission:actividad.show');/** formulario solo lectura **/

	Route::get('actividad/{id}/view', 'ActividadController@view')->name('actividad.view')
		->middleware('permission:actividad.view'); /** calendario con filto UniOpe **/

	Route::get('actividad/{id}/utp', 'ActividadController@utp')->name('actividad.utp')
		->middleware('permission:actividad.utp'); /** calendario con filto TipAct **/

	Route::post('actividad/adduser', 'ActividadController@adduser')->name('actividad.adduser')
		->middleware('permission:actividad.adduser'); /** agregar participante **/

	Route::post('actividad/remuser', 'ActividadController@remuser')->name('actividad.remuser')
		->middleware('permission:actividad.remuser'); /** remover participante **/

	Route::post('actividad/editdetails', 'ActividadController@editdetails')->name('actividad.editdetails')
		->middleware('permission:actividad.editdetails');

	Route::post('actividad/addfiles', 'ActividadController@addfiles')->name('actividad.addfiles')
        ->middleware('permission:actividad.addfiles');

    Route::post('actividad/crateActivadPeriodica', 'ActividadController@crateActivadPeriodica')->name('actividad.actividad.crateactivadperiodica')
        ->middleware('permission:actividad.crateactivadperiodica');

    Route::post('actividad/storeactgrupo', 'ActividadController@storeactgrupo')->name('actividad.storeactgrupo')
        ->middleware('permission:actividad.storeactgrupo');

    Route::post('actividad/editactividadper', 'ActividadController@editactividadper')->name('actividad.editactividadper')
        ->middleware('permission:actividad.editactividadper');

	//Tipo de Actividad
	Route::post('tipoact/store', 'TipoactController@store')->name('tipoact.store')
		->middleware('permission:tipoact.create');

	Route::get('tipoact', 'TipoactController@index')->name('tipoact.index')
		->middleware('permission:tipoact.index');

	Route::get('tipoact/create', 'TipoactController@create')->name('tipoact.create')
		->middleware('permission:tipoact.create');

	Route::put('tipoact/{role}', 'TipoactController@update')->name('tipoact.update')
		->middleware('permission:tipoact.edit');

	Route::get('tipoact/{role}', 'TipoactController@show')->name('tipoact.show')
		->middleware('permission:tipoact.show');

	Route::delete('tipoact/{role}', 'TipoactController@destroy')->name('tipoact.destroy')
		->middleware('permission:tipoact.destroy');

	Route::get('tipoact/{role}/edit', 'TipoactController@edit')->name('tipoact.edit')
		->middleware('permission:tipoact.edit');

	//Unidad Operativa
	Route::post('uniop/store', 'UnidadopController@store')->name('uniop.store')
		->middleware('permission:uniop.create');

	Route::get('uniop/create', 'UnidadopController@create')->name('uniop.create')
		->middleware('permission:uniop.create');

	Route::get('uniop', 'UnidadopController@index')->name('uniop.index')
		->middleware('permission:uniop.index');

	Route::put('uniop/{user}', 'UnidadopController@update')->name('uniop.update')
		->middleware('permission:uniop.edit');

	Route::get('uniop/{user}', 'UnidadopController@show')->name('uniop.show')
		->middleware('permission:uniop.show');

	Route::delete('uniop/{user}', 'UnidadopController@destroy')->name('uniop.destroy')
		->middleware('permission:uniop.destroy');

	Route::get('uniop/{user}/edit', 'UnidadopController@edit')->name('uniop.edit')
		->middleware('permission:uniop.edit');

	//PERMISOS
	Route::post('permiso/store', 'PermisoController@store')->name('permiso.store')
		->middleware('permission:permiso.create');

	Route::get('permiso/create', 'PermisoController@create')->name('permiso.create')
		->middleware('permission:permiso.create');

	Route::get('permiso', 'PermisoController@index')->name('permiso.index')
		->middleware('permission:permiso.index');

	Route::put('permiso/{user}', 'PermisoController@update')->name('permiso.update')
		->middleware('permission:permiso.edit');

	Route::get('permiso/{user}', 'PermisoController@show')->name('permiso.show')
		->middleware('permission:permiso.show');

	Route::delete('permiso/{user}', 'PermisoController@destroy')->name('permiso.destroy')
		->middleware('permission:permiso.destroy');

	Route::get('permiso/{user}/edit', 'PermisoController@edit')->name('permiso.edit')
		->middleware('permission:permiso.edit');

	// GRUPO DE TIPOS DE ACTIVIDAD
	Route::post('grupotipoact/store', 'GrupotipoactController@store')->name('grupotipoact.store')
		->middleware('permission:grupotipoact.create');

	Route::get('grupotipoact', 'GrupotipoactController@index')->name('grupotipoact.index')
		->middleware('permission:grupotipoact.index');

	Route::get('grupotipoact/create', 'GrupotipoactController@create')->name('grupotipoact.create')
		->middleware('permission:grupotipoact.create');

	Route::put('grupotipoact/{uid}', 'GrupotipoactController@update')->name('grupotipoact.update')
		->middleware('permission:grupotipoact.edit');

	Route::get('grupotipoact/{uid}', 'GrupotipoactController@show')->name('grupotipoact.show')
		->middleware('permission:grupotipoact.show');

	Route::delete('grupotipoact/{uid}', 'GrupotipoactController@destroy')->name('grupotipoact.destroy')
		->middleware('permission:grupotipoact.destroy');

	Route::get('grupotipoact/{uid}/edit', 'GrupotipoactController@edit')->name('grupotipoact.edit')
		->middleware('permission:grupotipoact.edit');

	/** PRUEBAS DE CALENDARIO **/
	Route::get('calendario', 'CalendarioController@index')->name('calendario.index')
		->middleware('permission:calendario.index');
	Route::get('calendario/{id}/view', 'CalendarioController@view')->name('calendario.view')
		->middleware('permission:calendario.view'); /** calendario con filto UniOpe **/
	Route::get('calendario/{id}/utp', 'CalendarioController@utp')->name('calendario.utp')
		->middleware('permission:calendario.utp'); /** calendario con filto TipAct **/
	Route::get('calendario/{id}/user', 'CalendarioController@user')->name('calendario.user')
        ->middleware('permission:calendario.user'); /** calendario con filto TipAct **/

    Route::post('calendario/misactividades', 'CalendarioController@misactividades')->name('calendario.misactividades')
		->middleware('permission:calendario.misactividades'); /** calendario con filto TipAct **/


	/*** TIPO CONTENIDO ***/
	Route::post('tipoact/addcontenido', 'TipoactController@addcontenido')->name('tipoact.addcontenido')
		->middleware('permission:tipoact.addcontenido');
	Route::put('tipoact/editcontenido/{id}', 'TipoactController@editcontenido')->name('tipoact.editcontenido')
		->middleware('permission:tipoact.editcontenido');
    Route::post('tipoact/anularcontenido', 'TipoactController@anularcontenido')->name('tipoact.anularcontenido')
            ->middleware('permission:tipoact.anularcontenido');

	//Listados
	Route::post('listado/store', 'ListadoController@store')->name('listado.store')
		->middleware('permission:listado.create');

	Route::get('listado', 'ListadoController@index')->name('listado.index')
		->middleware('permission:listado.index');

	Route::get('listado/create', 'ListadoController@create')->name('listado.create')
		->middleware('permission:listado.create');

	Route::put('listado/{id}', 'ListadoController@update')->name('listado.update')
		->middleware('permission:listado.edit');

	Route::get('listado/{id}', 'ListadoController@show')->name('listado.show')
		->middleware('permission:listado.show');

	Route::delete('listado/{id}', 'ListadoController@destroy')->name('listado.destroy')
		->middleware('permission:listado.destroy');

	Route::get('listado/{id}/edit', 'ListadoController@edit')->name('listado.edit')
		->middleware('permission:listado.edit');
	/*** ELEMENTOS ***/
	Route::post('listado/addcontenido', 'ListadoController@addcontenido')->name('listado.addcontenido')
		->middleware('permission:listado.addcontenido');
	Route::put('listado/editcontenido/{id}', 'ListadoController@editcontenido')->name('listado.editcontenido')
		->middleware('permission:listado.editcontenido');


	// Perfil
	Route::post('perfil/store', 'PerfilController@store')->name('perfil.store')
		->middleware('permission:perfil.create');

	Route::get('perfil', 'PerfilController@index')->name('perfil.index')
		->middleware('permission:perfil.index');

	Route::get('perfil/create', 'PerfilController@create')->name('perfil.create')
		->middleware('permission:perfil.create');

	Route::put('perfil/{uid}', 'PerfilController@update')->name('perfil.update')
		->middleware('permission:perfil.edit');

	Route::get('perfil/{uid}', 'PerfilController@show')->name('perfil.show')
		->middleware('permission:perfil.show');

	Route::delete('perfil/{uid}', 'PerfilController@destroy')->name('perfil.destroy')
		->middleware('permission:perfil.destroy');

	Route::get('perfil/{uid}/edit', 'PerfilController@edit')->name('perfil.edit')
		->middleware('permission:perfil.edit');

	Route::post('perfil/updatephoto', 'PerfilController@updatephoto')->name('perfil.updatephoto')
		->middleware('permission:perfil.updatephoto');

	//EMPRESA
	Route::post('empresa/updatelogo', 'EmpresaController@updatelogo')->name('empresa.updatelogo')
		->middleware('permission:empresa.updatelogo');
	Route::put('empresa/{uid}', 'EmpresaController@update')->name('empresa.update')
		->middleware('permission:empresa.edit');
	Route::get('empresa/{uid}/edit', 'EmpresaController@edit')->name('empresa.edit')
        ->middleware('permission:empresa.edit');
    Route::get('empresa/store', 'EmpresaController@store')->name('empresa.store')
        ->middleware('permission:empresa.store');



    //PROGRAMAS
	Route::post('programas/store', 'ProgramasController@store')->name('programas.store')
    ->middleware('permission:programas.create');

    Route::get('programas/create', 'ProgramasController@create')->name('programas.create')
        ->middleware('permission:programas.create');

    Route::get('programas', 'ProgramasController@index')->name('programas.index')
        ->middleware('permission:programas.index');

    Route::put('programas/{user}', 'ProgramasController@update')->name('programas.update')
        ->middleware('permission:programas.edit');

    Route::get('programas/{user}', 'ProgramasController@show')->name('programas.show')
        ->middleware('permission:programas.show');

    Route::delete('programas/{user}', 'ProgramasController@destroy')->name('programas.destroy')
        ->middleware('permission:programas.destroy');

    Route::get('programas/{user}/edit', 'ProgramasController@edit')->name('programas.edit')
        ->middleware('permission:programas.edit');

    /** vista de Programas **/


    Route::get('vprogramas', 'VistaProgramaController@index')->name('vprogramas.index')
        ->middleware('permission:vprogramas.index');

    Route::get('vprogramas/{user}', 'VistaProgramaController@show')->name('vprogramas.show')
        ->middleware('permission:vprogramas.show');

    Route::get('vprogramas/{uidta}/uidta/{uidpr}/uidpr', 'VistaProgramaController@viewcontenido')->name('vprogramas.viewcontenido')
        ->middleware('permission:vprogramas.viewcontenido');




        Route::get('cpanel/admin', 'ControlPanelController@index');
        Route::get('cpanel/empresa/{uid}/edit', 'ControlPanelController@edit');
        Route::post('cpanel/empresa/store', 'ControlPanelController@store');
        Route::post('cpanel/empresa/storeedit', 'ControlPanelController@stroreEdit');
        Route::post('cpanel/empresa/createuser', 'ControlPanelController@crearUser');
        Route::post('cpanel/empresa/edituser', 'ControlPanelController@editarUser');
        Route::post('cpanel/empresa/createuniop', 'ControlPanelController@crearUniOp');
        Route::post('cpanel/empresa/edituniop', 'ControlPanelController@editUniOp');
        Route::post('cpanel/empresa/aplicaplantilla', 'ControlPanelController@aplicarPlantilla');
        Route::post('cpanel/empresa/deleteempresa', 'ControlPanelController@deleteEmpresa');



});

