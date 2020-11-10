<?php

use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/catch-emails', 'EmailController@catchEmails');


Route::group(['middleware' => ['auth:api']], function() {
    Route::group(['prefix' => 'campaigns'], function () {
//        Route::get('/', 'CampaignController@index');
//        Route::post('/', 'CampaignController@store');
//        Route::patch('{campaign}', 'CampaignController@update');
//        Route::delete('{campaign}', 'CampaignController@destroy');
        Route::get('/searches/{campaign}', 'CampaignController@searches');
    });
//    Route::group(['prefix' => 'keywords'], function () {
//        Route::get('/', 'KeywordController@index');
//        Route::post('/', 'KeywordController@store');
//        Route::patch('{keyword}', 'KeywordController@update');
//        Route::delete('{keyword}', 'KeywordController@destroy');
//    });
    Route::resource('campaigns', 'CampaignController');
    Route::resource('keywords', 'KeywordController');
    Route::post('keywords/import', 'KeywordController@import');
    Route::resource('excluded-domain', 'ExcludedDomainController');
    Route::post('excluded-domain/import', 'ExcludedDomainController@import');
    Route::resource('templates', 'TemplateController');
    Route::resource('imap-accounts', 'ImapAccountController');

    Route::post('/send-emails', 'EmailController@sendEmails');

    Route::group(['prefix' => 'emails'], function () {
        Route::get('/', 'EmailController@index');
        Route::post('/reply-email', 'EmailController@replyEmail');
    });


    //Route::resource('opportunities', 'OpportunityController');
    Route::group(['prefix' => 'opportunities'], function () {
        Route::post('delete', 'OpportunityController@delete');
        Route::post('favorite', 'OpportunityController@favorite');
        Route::post('delete', 'OpportunityController@delete');
        Route::get('favorites', 'OpportunityController@favorites');
    });


    Route::post('change-password', 'HomeController@changePassword')->name('changePassword');

    Route::post('settings', 'SettingController@store');
    Route::get('settings', 'SettingController@index');
});
