<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {

    Route::post('/auth/login', 'SanctumController@issue');

    Route::prefix('leagues')->group(function () {

        // Leagues
        Route::post('/', 'LeagueController@create');
        Route::get('/', 'LeagueController@list');

        Route::get('/{league}', 'LeagueController@view');
        //TODO: edit league
        //TODO: delete league

        // League's teams
        Route::post('/{league}/teams', 'LeagueController@join');
        Route::get('/{league}/teams', 'LeagueController@teams');
        //TODO: leave or kick team
        //TODO: edit team

        // League's invites
        Route::post('/{league}/invites', 'InviteController@create');
        Route::get('/{league}/invites', 'InviteController@list');
        //TODO: delete invite

        // League's matches
        Route::post('/{league}/matches', 'MatchController@create');
        //Route::get('/{league}/matches', 'MatchController@list');
    });
});
