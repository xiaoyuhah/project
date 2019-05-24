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

// Route::get('/', function () {
//     return view('index/index');
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'Index\IndexController@index');

Route::prefix('/login')->group(function(){
    Route::get('login','Index\LoginController@login');
    Route::post('logindo','Index\LoginController@logindo');
    Route::get('logout','Index\LoginController@logout');
    Route::get('register','Index\LoginController@register');
    Route::post('sendEmail','Index\LoginController@sendEmail');
    Route::post('sendmail','Index\LoginController@sendmail');
    Route::get('test','Index\LoginController@test');
    Route::post('regsubmit','Index\LoginController@regsubmit');
});

Route::prefix('/user')->group(function(){
    Route::get('index','Index\UserController@index');

});

Route::prefix('/goods')->group(function(){
    Route::get('list/{id?}','Index\GoodsController@list');
    Route::get('getCateId','Index\GoodsController@getCateId');
    Route::post('goodstype','Index\GoodsController@goodstype');
    Route::get('detail/{id}','Index\GoodsController@detail');
});

Route::prefix('/cart')->group(function(){
    Route::post('addcart','Index\CartController@addcart');
    Route::get('cartlist','Index\CartController@cartlist');
    Route::post('changeNumber','Index\CartController@changeNumber');
    Route::post('getTotal','Index\CartController@getTotal');
    Route::post('getCount','Index\CartController@getCount');
    Route::get('confirm/{id}','Index\CartController@confirm');
});

Route::prefix('/address')->group(function(){
    Route::get('add','Index\AddressController@add');
    Route::post('getArea','Index\AddressController@getArea');
    Route::get('getAreaInfo/{id}','Index\AddressController@getAreaInfo');
    Route::post('addressadd','Index\AddressController@addressadd');
});

Route::prefix('/order')->group(function(){
    Route::post('confirmOrder','Index\OrderController@confirmOrder');
    Route::get('success/{id}','Index\OrderController@success');
    Route::get('alipay/{id}','Index\OrderController@alipay');
    Route::get('returnUrl','Index\OrderController@returnUrl');
    Route::get('malipay/{id}','Index\OrderController@malipay');
    Route::post('nofitipay','Index\OrderController@nofitipay');
});
