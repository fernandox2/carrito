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

Auth::routes();

Route::group(['middleware'=>['guest']],function(){
    Route::get('/', function () { return view('welcome'); });
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});




Route::group(['middleware'=>['auth']],function(){

	Route::get('/empresadelusuario', 'EmpresaController@buscarempresalogin');

	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
     
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
     
	Route::get('/home', 'HomeController@index')->name('home');
 
    Route::group(['middleware' => ['Supervisor']], function () {
	   
		Route::get('/empresa', 'EmpresaController@index');
		Route::post('/empresa/registrar', 'EmpresaController@store');
		Route::post('/empresa/revisarFirma', 'EmpresaController@firmaOK');
		Route::post('/empresa/registrarFirma', 'EmpresaController@storeFirma');
		Route::put('/empresa/actualizar', 'EmpresaController@update');
		Route::put('/empresa/eliminar', 'EmpresaController@eliminar');
		Route::put('/empresa/activar', 'EmpresaController@activar');

		Route::get('/producto', 'ProductoController@index');
		Route::post('/producto/registrar', 'ProductoController@store');
		Route::post('/producto/revisarFirma', 'ProductoController@firmaOK');
		Route::post('/producto/registrarFirma', 'ProductoController@storeFirma');
		Route::put('/producto/actualizar', 'ProductoController@update');
		Route::put('/producto/eliminar', 'ProductoController@eliminar');
		Route::put('/producto/activar', 'ProductoController@activar');

		
 
    });
 
    Route::group(['middleware' => ['Vendedor']], function () {
       
    });
 
    Route::group(['middleware' => ['Administrador']], function () {
         
		Route::get('/empresa', 'EmpresaController@index');
		Route::post('/empresa/registrar', 'EmpresaController@store');
		Route::post('/empresa/revisarFirma', 'EmpresaController@firmaOK');
		Route::post('/empresa/registrarFirma', 'EmpresaController@storeFirma');
		Route::put('/empresa/actualizar', 'EmpresaController@update');
		Route::put('/empresa/eliminar', 'EmpresaController@eliminar');
		Route::put('/empresa/activar', 'EmpresaController@activar');

		Route::get('/venta', 'VentaController@index');
		Route::post('/venta/registrar', 'VentaController@store');
		Route::put('/venta/actualizar', 'VentaController@update');
		Route::put('/venta/eliminar', 'VentaController@eliminar');
		Route::put('/venta/activar', 'VentaController@activar');



    });
 
});
 
//Route::get('/home', 'HomeController@index')->name('home');


/*


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
});

Route::get('/categoria', 'CategoriaController@index');
Route::post('/categoria/registrar', 'CategoriaController@store');
Route::put('/categoria/actualizar', 'CategoriaController@update');
Route::put('/categoria/eliminar', 'CategoriaController@eliminar');
Route::put('/categoria/activar', 'CategoriaController@activar');

Route::get('/empresa', 'EmpresaController@index');
Route::post('/empresa/registrar', 'EmpresaController@store');
Route::post('/empresa/revisarFirma', 'EmpresaController@firmaOK');
Route::post('/empresa/registrarFirma', 'EmpresaController@storeFirma');
Route::put('/empresa/actualizar', 'EmpresaController@update');
Route::put('/empresa/eliminar', 'EmpresaController@eliminar');
Route::put('/empresa/activar', 'EmpresaController@activar');

Route::get('/setDePruebas', 'SIIController@setDePruebas');
Route::get('/ConsumoFolios', 'SIIController@ConsumoFolios');
Route::get('/PDF_Boleta', 'SIIController@PDF_Boleta');


*/