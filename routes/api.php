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


Route::post('auth/register', 'Api\AuthController@register');
Route::post('auth/login', 'Api\AuthController@Login');

Route::post('sendRegOtp', 'Api\AuthController@sendRegOtp');

Route::post('forgotPassword', 'Api\AuthController@forgotPassword');
Route::post('resetPassword', 'Api\AuthController@resetPassword');

Route::group(['middleware' => ['auth:api', 'userStatus']], function () {

    /*======== (-- AuthController --) ========*/
    Route::post('auth/logout', 'Api\AuthController@logout');
    Route::post('updateDeviceToken', 'Api\AuthController@updateDeviceToken');
    Route::get('getProfile', 'Api\AuthController@getProfile');
    Route::post('updateProfile', 'Api\AuthController@updateProfile');
    Route::post('uploadProfilePic', 'Api\AuthController@uploadProfilePic');
    Route::post('changePassword', 'Api\AuthController@changePassword');

    /*======== (-- InfographicsApiController --) ========*/
    Route::get('getEnviroVocabulary', 'Api\InfographicsApiController@getEnviroVocabulary');
    Route::get('getDidYouKnow', 'Api\InfographicsApiController@getDidYouKnow');

    /*======== (-- DownloadsApiController --) ========*/
    Route::get('getFreeDownloads', 'Api\DownloadsApiController@getFreeDownloads');
    Route::get('getPledge', 'Api\DownloadsApiController@getPledge');

    /*======== (-- VideoApiController --) ========*/
    Route::get('getVideo', 'Api\VideoApiController@getVideo');

    /*======== (-- TaskManagementApiController --) ========*/
    Route::post('saveTask', 'Api\TaskManagementApiController@saveTask');
    Route::get('getTask', 'Api\TaskManagementApiController@getTask');

    /*======== (-- JournalistApiController --) ========*/
    Route::post('saveJournal', 'Api\JournalManagementApiController@saveJournal');
    Route::get('getJournal', 'Api\JournalManagementApiController@getJournal');

    /*======== (-- CmsApiController --) ========*/
    Route::get('getBanner', 'Api\CmsApiController@getBanner');
    Route::get('getPrivacyPolicy', 'Api\CmsApiController@getPrivacyPolicy');
    Route::get('getGuidelines', 'Api\CmsApiController@getGuidelines');
    Route::get('getAboutUs', 'Api\CmsApiController@getAboutUs');

    /*======== (-- LeaderBoardApiController --) ========*/
    Route::get('getCurrentQuarterRanking', 'Api\LeaderBoardApiController@getCurrentQuarterRanking');
    Route::get('getOverAllRanking', 'Api\LeaderBoardApiController@getOverAllRanking');

    /*======== (-- NotificationController --) ========*/
    Route::get('getNotifications', 'Api\NotificationController@getNotifications');
    Route::get('getNotificationsCount', 'Api\NotificationController@getNotificationsCount');
});


/*======== (-- SchoolManagementApiController --) ========*/
Route::get('getClass', 'Api\SchoolManagementApiController@getClass');
