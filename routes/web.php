<?php

// use Illuminate\Routing\Route;
// use Symfony\Component\Routing\Route;
// use Symfony\Component\Routing\Annotation\Route;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['web']], function () {

    Route::get('admin', 'Admin\AuthController@index')->name('loginPage');

    Route::get('admin/forgot-password', 'Admin\AuthController@forgotPasswordPage')->name('forgotPasswordPage');
    Route::post('admin/forgotPassword', 'Admin\AuthController@forgotPassword')->name('forgotPassword');

    Route::get('admin/reset-password', 'Admin\AuthController@resetPasswordPage')->name('resetPasswordPage');
    Route::post('admin/resetPassword', 'Admin\AuthController@resetPassword')->name('resetPassword');

    Route::post('admin/checkLogin', 'Admin\AuthController@checkLogin')->name('checkLogin');
    Route::post('admin/changePassword', 'Admin\AuthController@changePasswordLogin');

    Route::group(['middleware' => ['admin', 'CheckPermission']], function () {

        Route::post('admin/logout', 'Admin\AuthController@logout')->name('logout');
        Route::get('admin/profile/', 'Admin\AuthController@showProfile')->name('profile.show');
        Route::post('admin/profile/update', 'Admin\AuthController@updateProfile')->name('profile.update');

        Route::get('admin/change-password/', 'Admin\AuthController@showChangePassword')->name('password.show');
        Route::post('admin/change-password/update', 'Admin\AuthController@updatePassword')->name('password.update');

        /*======== (-- DashboardController --) ========*/
        Route::get('admin/dashboard', 'Admin\DashboardController@showDashboard')->name('show.dashboard');

        Route::get('admin/dashboard/referral-point/ajaxGetList', 'Admin\DashboardController@getReferralPoint');
        Route::post('admin/dashboard/referral-point/add/save', 'Admin\DashboardController@saveReferralPoint')->name('save.referralPoint');
        Route::post('admin/dashboard/referral-point/edit/update', 'Admin\DashboardController@updateReferralPoint')->name('update.referralPoint');
        Route::get('admin/dashboard/referral-point/status/{id?}', 'Admin\DashboardController@statusReferralPoint')->name('status.referralPoint');
        Route::get('admin/dashboard/referral-point/delete/{id?}', 'Admin\DashboardController@deleteReferralPoint')->name('delete.referralPoint');

        /*======== (-- UserController --) ========*/
        Route::get('admin/users/sub-admins', 'Admin\UserController@showSubAdmins')->name('subAdmin.show');
        Route::get('admin/users/sub-admins/ajaxGetSubAdmins', 'Admin\UserController@ajaxGetSubAdmins');
        Route::get('admin/users/sub-admins/add', 'Admin\UserController@addSubAdmin')->name('subAdmin.add');
        Route::post('admin/users/sub-admins/add/save', 'Admin\UserController@saveSubAdmin')->name('subAdmin.save');
        Route::get('admin/users/sub-admins/edit/{id?}', 'Admin\UserController@editSubAdmin')->name('subAdmin.edit');
        Route::post('admin/users/sub-admins/edit/update', 'Admin\UserController@updateSubAdmin')->name('subAdmin.update');
        Route::get('admin/users/sub-admins/status/{id?}', 'Admin\UserController@statusSubAdmin')->name('subAdmin.status');
        Route::get('admin/users/sub-admins/details/{id?}', 'Admin\UserController@userAdminsDetail')->name('subAdmin.details');

        Route::get('admin/users/user', 'Admin\UserController@showUser')->name('show.user');
        Route::get('admin/users/user/ajaxGetList', 'Admin\UserController@getUser');
        Route::get('admin/users/user/add', 'Admin\UserController@addUser')->name('add.user');
        Route::post('admin/users/user/add/save', 'Admin\UserController@saveUser')->name('save.user');
        Route::get('admin/users/user/edit/{id?}', 'Admin\UserController@editUser')->name('edit.user');
        Route::post('admin/users/user/edit/update', 'Admin\UserController@updateUser')->name('update.user');
        Route::get('admin/users/user/status/{id?}', 'Admin\UserController@statusUser')->name('status.user');
        Route::get('admin/users/user/details/{id?}', 'Admin\UserController@detailsUser')->name('details.user');

        /*======== (-- InfographicsController --) ========*/
        Route::get('admin/info-graphics/enviro-vocabulary', 'Admin\InfographicsController@showEnviroVocabulary')->name('show.enviroVocabulary');
        Route::get('admin/info-graphics/enviro-vocabulary/ajaxGetList', 'Admin\InfographicsController@getEnviroVocabulary');
        Route::get('admin/info-graphics/enviro-vocabulary/add', 'Admin\InfographicsController@addEnviroVocabulary')->name('add.enviroVocabulary');
        Route::post('admin/info-graphics/enviro-vocabulary/add/save', 'Admin\InfographicsController@saveEnviroVocabulary')->name('save.enviroVocabulary');
        Route::get('admin/info-graphics/enviro-vocabulary/edit/{id?}', 'Admin\InfographicsController@editEnviroVocabulary')->name('edit.enviroVocabulary');
        Route::post('admin/info-graphics/enviro-vocabulary/edit/update', 'Admin\InfographicsController@updateEnviroVocabulary')->name('update.enviroVocabulary');
        Route::get('admin/info-graphics/enviro-vocabulary/status/{id?}', 'Admin\InfographicsController@statusEnviroVocabulary')->name('status.enviroVocabulary');
        Route::get('admin/info-graphics/enviro-vocabulary/delete/{id?}', 'Admin\InfographicsController@deleteEnviroVocabulary')->name('delete.enviroVocabulary');
        Route::get('admin/info-graphics/enviro-vocabulary/detail/{id?}', 'Admin\InfographicsController@detailEnviroVocabulary')->name('details.enviroVocabulary');

        Route::get('admin/info-graphics/did-you-know', 'Admin\InfographicsController@showDidYouKnow')->name('show.didYouKnow');
        Route::get('admin/info-graphics/did-you-know/ajaxGetList', 'Admin\InfographicsController@getDidYouKnow');
        Route::get('admin/info-graphics/did-you-know/add', 'Admin\InfographicsController@addDidYouKnow')->name('add.didYouKnow');
        Route::post('admin/info-graphics/did-you-know/add/save', 'Admin\InfographicsController@saveDidYouKnow')->name('save.didYouKnow');
        Route::get('admin/info-graphics/did-you-know/edit/{id?}', 'Admin\InfographicsController@editDidYouKnow')->name('edit.didYouKnow');
        Route::post('admin/info-graphics/did-you-know/edit/update', 'Admin\InfographicsController@updateDidYouKnow')->name('update.didYouKnow');
        Route::get('admin/info-graphics/did-you-know/status/{id?}', 'Admin\InfographicsController@statusDidYouKnow')->name('status.didYouKnow');
        Route::get('admin/info-graphics/did-you-know/delete/{id?}', 'Admin\InfographicsController@deleteDidYouKnow')->name('delete.didYouKnow');
        Route::get('admin/info-graphics/did-you-know/detail/{id?}', 'Admin\InfographicsController@detailDidYouKnow')->name('details.didYouKnow');

        /*======== (-- DownloadsController --) ========*/
        Route::get('admin/downloads/free-downloads', 'Admin\DownloadsController@showFreeDownloads')->name('show.freeDownloads');
        Route::get('admin/downloads/free-downloads/ajaxGetList', 'Admin\DownloadsController@getFreeDownloads');
        Route::get('admin/downloads/free-downloads/add', 'Admin\DownloadsController@addFreeDownloads')->name('add.freeDownloads');
        Route::post('admin/downloads/free-downloads/add/save', 'Admin\DownloadsController@saveFreeDownloads')->name('save.freeDownloads');
        Route::get('admin/downloads/free-downloads/edit/{id?}', 'Admin\DownloadsController@editFreeDownloads')->name('edit.freeDownloads');
        Route::post('admin/downloads/free-downloads/edit/update', 'Admin\DownloadsController@updateFreeDownloads')->name('update.freeDownloads');
        Route::get('admin/downloads/free-downloads/status/{id?}', 'Admin\DownloadsController@statusFreeDownloads')->name('status.freeDownloads');
        Route::get('admin/downloads/free-downloads/delete/{id?}', 'Admin\DownloadsController@deleteFreeDownloads')->name('delete.freeDownloads');
        Route::get('admin/downloads/free-downloads/detail/{id?}', 'Admin\DownloadsController@detailFreeDownloads')->name('details.freeDownloads');

        Route::get('admin/downloads/manage-pledge', 'Admin\DownloadsController@showManagePledge')->name('show.managePledge');
        Route::get('admin/downloads/manage-pledge/ajaxGetList', 'Admin\DownloadsController@getManagePledge');
        Route::get('admin/downloads/manage-pledge/add', 'Admin\DownloadsController@addManagePledge')->name('add.managePledge');
        Route::post('admin/downloads/manage-pledge/add/save', 'Admin\DownloadsController@saveManagePledge')->name('save.managePledge');
        Route::get('admin/downloads/manage-pledge/edit/{id?}', 'Admin\DownloadsController@editManagePledge')->name('edit.managePledge');
        Route::post('admin/downloads/manage-pledge/edit/update', 'Admin\DownloadsController@updateManagePledge')->name('update.managePledge');
        Route::get('admin/downloads/manage-pledge/status/{id?}', 'Admin\DownloadsController@statusManagePledge')->name('status.managePledge');
        Route::get('admin/downloads/manage-pledge/delete/{id?}', 'Admin\DownloadsController@deleteManagePledge')->name('delete.managePledge');
        Route::get('admin/downloads/manage-pledge/detail/{id?}', 'Admin\DownloadsController@detailManagePledge')->name('details.managePledge');

        /*======== (-- JournalManagementController --) ========*/
        Route::get('admin/journal-management/requested-list', 'Admin\JournalManagementController@showRequestedJournal')->name('show.requestedJournal');
        Route::get('admin/journal-management/requested-list/ajaxGetList', 'Admin\JournalManagementController@getRequestedJournal');
        Route::get('admin/journal-management/requested-list/status/{id?}', 'Admin\JournalManagementController@statusRequestedJournal')->name('status.requestedJournal');
        Route::get('admin/journal-management/requested-list/delete/{id?}', 'Admin\JournalManagementController@deleteRequestedJournal')->name('delete.requestedJournal');
        Route::get('admin/journal-management/requested-list/detail/{id?}', 'Admin\JournalManagementController@detailRequestedJournal')->name('details.requestedJournal');

        Route::get('admin/journal-management/accepted-list', 'Admin\JournalManagementController@showAcceptedJournal')->name('show.acceptedJournal');
        Route::get('admin/journal-management/accepted-list/ajaxGetList', 'Admin\JournalManagementController@getAcceptedJournal');
        Route::get('admin/journal-management/accepted-list/status/{id?}', 'Admin\JournalManagementController@statusAcceptedJournal')->name('status.acceptedJournal');
        Route::get('admin/journal-management/accepted-list/delete/{id?}', 'Admin\JournalManagementController@deleteAcceptedJournal')->name('delete.acceptedJournal');
        Route::get('admin/journal-management/accepted-list/detail/{id?}', 'Admin\JournalManagementController@detailAcceptedJournal')->name('details.acceptedJournal');

        /*======== (-- VideoController --) ========*/
        Route::get('admin/video', 'Admin\VideoController@showVideo')->name('show.video');
        Route::get('admin/video/ajaxGetList', 'Admin\VideoController@getVideo');
        Route::get('admin/video/add', 'Admin\VideoController@addVideo')->name('add.video');
        Route::post('admin/video/add/save', 'Admin\VideoController@saveVideo')->name('save.video');
        Route::get('admin/video/edit/{id?}', 'Admin\VideoController@editVideo')->name('edit.video');
        Route::post('admin/video/edit/update', 'Admin\VideoController@updateVideo')->name('update.video');
        Route::get('admin/video/status/{id?}', 'Admin\VideoController@statusVideo')->name('status.video');
        Route::get('admin/video/delete/{id?}', 'Admin\VideoController@deleteVideo')->name('delete.video');
        Route::get('admin/video/detail/{id?}', 'Admin\VideoController@detailVideo')->name('details.video');

        /*======== (-- TaskManagementController --) ========*/
        Route::get('admin/task-management/manage-task-level', 'Admin\TaskManagementController@showManageTaskLevel')->name('show.manageTaskLevel');
        Route::get('admin/task-management/manage-task-level/ajaxGetList', 'Admin\TaskManagementController@getManageTaskLevel')->name('get.manageTaskLevel');
        Route::get('admin/task-management/manage-task-level/add', 'Admin\TaskManagementController@addManageTaskLevel')->name('add.manageTaskLevel');
        Route::post('admin/task-management/manage-task-level/add/save', 'Admin\TaskManagementController@saveManageTaskLevel')->name('save.manageTaskLevel');
        Route::get('admin/task-management/manage-task-level/edit/{id?}', 'Admin\TaskManagementController@editManageTaskLevel')->name('edit.manageTaskLevel');
        Route::post('admin/task-management/manage-task-level/edit/update', 'Admin\TaskManagementController@updateManageTaskLevel')->name('update.manageTaskLevel');
        Route::get('admin/task-management/manage-task-level/status/{id?}', 'Admin\TaskManagementController@statusManageTaskLevel')->name('status.manageTaskLevel');
        Route::get('admin/task-management/manage-task-level/delete/{id?}', 'Admin\TaskManagementController@deleteManageTaskLevel')->name('delete.manageTaskLevel');
        Route::get('admin/task-management/manage-task-level/detail/{id?}', 'Admin\TaskManagementController@detailManageTaskLevel')->name('details.manageTaskLevel');

        Route::get('admin/task-management/manage-task-quarter', 'Admin\TaskManagementController@showManageTaskQuarter')->name('show.manageTaskQuarter');
        Route::get('admin/task-management/manage-task-quarter/ajaxGetList', 'Admin\TaskManagementController@getManageTaskQuarter')->name('get.manageTaskQuarter');
        Route::get('admin/task-management/manage-task-quarter/add', 'Admin\TaskManagementController@addManageTaskQuarter')->name('add.manageTaskQuarter');
        Route::post('admin/task-management/manage-task-quarter/add/save', 'Admin\TaskManagementController@saveManageTaskQuarter')->name('save.manageTaskQuarter');
        Route::get('admin/task-management/manage-task-quarter/edit/{id?}', 'Admin\TaskManagementController@editManageTaskQuarter')->name('edit.manageTaskQuarter');
        Route::post('admin/task-management/manage-task-quarter/edit/update', 'Admin\TaskManagementController@updateManageTaskQuarter')->name('update.manageTaskQuarter');
        Route::get('admin/task-management/manage-task-quarter/status/{id?}', 'Admin\TaskManagementController@statusManageTaskQuarter')->name('status.manageTaskQuarter');
        Route::get('admin/task-management/manage-task-quarter/delete/{id?}', 'Admin\TaskManagementController@deleteManageTaskQuarter')->name('delete.manageTaskQuarter');
        Route::get('admin/task-management/manage-task-quarter/detail/{id?}', 'Admin\TaskManagementController@detailManageTaskQuarter')->name('details.manageTaskQuarter');

        Route::get('admin/task-management/manage-tasks', 'Admin\TaskManagementController@showManageTasks')->name('show.manageTasks');
        Route::get('admin/task-management/manage-tasks/ajaxGetList', 'Admin\TaskManagementController@getManageTasks')->name('get.manageTasks');
        Route::get('admin/task-management/manage-tasks/add', 'Admin\TaskManagementController@addManageTasks')->name('add.manageTasks');
        Route::post('admin/task-management/manage-tasks/add/save', 'Admin\TaskManagementController@saveManageTasks')->name('save.manageTasks');
        Route::get('admin/task-management/manage-tasks/edit/{id?}', 'Admin\TaskManagementController@editManageTasks')->name('edit.manageTasks');
        Route::post('admin/task-management/manage-tasks/edit/update', 'Admin\TaskManagementController@updateManageTasks')->name('update.manageTasks');
        Route::get('admin/task-management/manage-tasks/status/{id?}', 'Admin\TaskManagementController@statusManageTasks')->name('status.manageTasks');
        Route::get('admin/task-management/manage-tasks/delete/{id?}', 'Admin\TaskManagementController@deleteManageTasks')->name('delete.manageTasks');
        Route::get('admin/task-management/manage-tasks/detail/{id?}', 'Admin\TaskManagementController@detailManageTasks')->name('details.manageTasks');

        Route::get('admin/task-management/task-requests', 'Admin\TaskManagementController@showTaskRequests')->name('show.taskRequests');
        Route::get('admin/task-management/task-requests/ajaxGetList', 'Admin\TaskManagementController@getTaskRequests')->name('get.taskRequests');
        Route::get('admin/task-management/task-requests/status/{id?}/{type?}', 'Admin\TaskManagementController@statusTaskRequests')->name('status.taskRequests');
        Route::get('admin/task-management/task-requests/detail/{id?}', 'Admin\TaskManagementController@detailTaskRequests')->name('details.taskRequests');


        /*======== (-- NotificationController --) ========*/
        Route::get('admin/notification/send-notification', 'Admin\NotificationController@showSendNotification')->name('show.sendNotification');
        Route::get('admin/notification/send-notification/ajaxGetList', 'Admin\NotificationController@getSendNotification');
        Route::post('admin/notification/send-notification/add/save', 'Admin\NotificationController@saveSendNotification')->name('save.sendNotification');
        Route::post('admin/notification/send-notification/edit/update', 'Admin\NotificationController@updateSendNotification')->name('update.sendNotification');
        Route::get('admin/notification/send-notification/status/{id?}', 'Admin\NotificationController@statusSendNotification')->name('status.sendNotification');
        Route::get('admin/notification/send-notification/delete/{id?}', 'Admin\NotificationController@deleteSendNotification')->name('delete.sendNotification');

        // Route::get('admin/send-notification', 'Admin\SendNotificationController@showSendNotification')->name('show.sendNotification');
        // Route::get('admin/send-notification/ajaxGetList', 'Admin\SendNotificationController@ajaxGetSendNotification');
        // Route::post('admin/send-notification/add/save', 'Admin\SendNotificationController@saveSendNotification')->name('save.sendNotification');
        // Route::post('admin/send-notification/edit/update', 'Admin\SendNotificationController@updateSendNotification')->name('update.sendNotification');
        // Route::get('admin/send-notification/delete/{id?}', 'Admin\SendNotificationController@deleteSendNotification')->name('delete.sendNotification');


        /*======== (-- SchoolManagementController --) ========*/
        Route::get('admin/school-management/manage-school', 'Admin\SchoolManagementController@showManageSchool')->name('show.manageSchool');
        Route::get('admin/school-management/manage-school/ajaxGetList', 'Admin\SchoolManagementController@getManageSchool');
        Route::post('admin/school-management/manage-school/add/save', 'Admin\SchoolManagementController@saveManageSchool')->name('save.manageSchool');
        Route::post('admin/school-management/manage-school/edit/update', 'Admin\SchoolManagementController@updateManageSchool')->name('update.manageSchool');
        Route::get('admin/school-management/manage-school/status/{id?}', 'Admin\SchoolManagementController@statusManageSchool')->name('status.manageSchool');
        Route::get('admin/school-management/manage-school/delete/{id?}', 'Admin\SchoolManagementController@deleteManageSchool')->name('delete.manageSchool');

        Route::get('admin/school-management/manage-class', 'Admin\SchoolManagementController@showManageClass')->name('show.manageClass');
        Route::get('admin/school-management/manage-class/ajaxGetList', 'Admin\SchoolManagementController@getManageClass');
        Route::post('admin/school-management/manage-class/add/save', 'Admin\SchoolManagementController@saveManageClass')->name('save.manageClass');
        Route::post('admin/school-management/manage-class/edit/update', 'Admin\SchoolManagementController@updateManageClass')->name('update.manageClass');
        Route::get('admin/school-management/manage-class/status/{id?}', 'Admin\SchoolManagementController@statusManageClass')->name('status.manageClass');
        Route::get('admin/school-management/manage-class/delete/{id?}', 'Admin\SchoolManagementController@deleteManageClass')->name('delete.manageClass');

        /*======== (-- LeaderBoardController --) ========*/
        Route::get('admin/leader-board/top-ranked', 'Admin\LeaderBoardController@showTopRanked')->name('show.topRanked');
        Route::get('admin/leader-board/top-ranked/ajaxGetList', 'Admin\LeaderBoardController@getTopRanked')->name('get.topRanked');
        Route::get('admin/leader-board/top-ranked/detail/{id?}', 'Admin\LeaderBoardController@detailTopRanked')->name('details.topRanked');
        Route::get('admin/leader-board/top-ranked/download/', 'Admin\LeaderBoardController@downloadTopRanked')->name('download.topRanked');

        Route::get('admin/leader-board/over-all-point', 'Admin\LeaderBoardController@showOverAllPoint')->name('show.overAllPoint');
        Route::get('admin/leader-board/over-all-point/ajaxGetList', 'Admin\LeaderBoardController@getOverAllPoint')->name('get.overAllPoint');
        Route::get('admin/leader-board/over-all-point/detail/{id?}', 'Admin\LeaderBoardController@detailOverAllPoint')->name('details.overAllPoint');

        /*======== (-- RoleController --) ========*/
        Route::get('admin/roles-permissions/roles', 'Admin\RoleController@showRole');
        Route::post('admin/roles-permissions/roles/add/save', 'Admin\RoleController@saveRole')->name('roles.save');
        Route::get('admin/roles-permissions/roles/delete/{id}', 'Admin\RoleController@deleteRole');

        Route::get('admin/roles-permissions/permissions', 'Admin\RoleController@showPermission');
        Route::get('admin/roles-permissions/permissions/edit/{id}', 'Admin\RoleController@showEditPermission');
        Route::post('admin/roles-permissions/permissions/edit/update', 'Admin\RoleController@updatePermission')->name('permissions.update');


        /*======== (-- CmsController --) ========*/
        Route::get('admin/cms/banner', 'Admin\CmsController@showCmsBanner')->name('show.banner');
        Route::get('admin/cms/banner/ajaxGetList', 'Admin\CmsController@ajaxGetBanner');
        Route::get('admin/cms/banner/add', 'Admin\CmsController@addCmsBanner')->name('add.banner');
        Route::post('admin/cms/banner/add/save', 'Admin\CmsController@saveCmsBanner')->name('save.banner');
        Route::get('admin/cms/banner/edit/{id?}', 'Admin\CmsController@editCmsBanner')->name('edit.banner');
        Route::post('admin/cms/banner/edit/update', 'Admin\CmsController@updateCmsBanner')->name('update.banner');
        Route::get('admin/cms/banner/status/{id?}', 'Admin\CmsController@cmsBannerStatus')->name('status.banner');
        Route::get('admin/cms/banner/delete/{id?}', 'Admin\CmsController@cmsBannerDelete')->name('delete.banner');
        Route::get('admin/cms/banner/detail/{id?}', 'Admin\CmsController@cmsBannerDetail')->name('details.banner');
        Route::post('admin/cms/banner/ajaxGetTestPackage', 'Admin\CmsController@ajaxGetBannerTestPackage')->name('get.bannerTestPackage');

        Route::get('admin/cms/privacy-policy', 'Admin\CmsController@showPrivacyPolicy')->name('show.privacyPolicy');
        Route::post('admin/cms/privacy-policy/save', 'Admin\CmsController@savePrivacyPolicy')->name('save.privacyPolicy');

        Route::get('admin/cms/guidelines', 'Admin\CmsController@showGuidelines')->name('show.guidelines');
        Route::post('admin/cms/guidelines/save', 'Admin\CmsController@saveGuidelines')->name('save.guidelines');

        Route::get('admin/cms/faq', 'Admin\CmsController@showFaq')->name('show.faq');

        Route::get('admin/cms/about-us', 'Admin\CmsController@showaboutUs')->name('show.aboutUs');
        Route::post('admin/cms/about-us/save', 'Admin\CmsController@saveaboutUs')->name('save.aboutUs');


        /*======== (-- Error Page --) ========*/
        // Route::get('admin/page/404', 'Admin\CommonController@show404')->name('404');
        // Route::get('admin/page/500', 'Admin\CommonController@show500')->name('500');



        /*======== (-- CommonController --) ========*/
        Route::get('admin/get-class/{id?}', 'Admin\CommonController@getClass')->name('get.class');
        Route::get('admin/get-quarter/{id?}', 'Admin\CommonController@getQuarter')->name('get.quarter');
        Route::get('admin/get-user-list/{champLevel?}', 'Admin\CommonController@getUserList')->name('get.userList');
    });

    Route::get('/', 'Web\HomeController@homeShow')->name('fontend.home.show');

    Route::get('privacy-policy', function () {
        return view('web.cms.privacy_policy.index');
    });

    Route::get('landing-page', function () {
        return view('web.landing_page.index');
    })->name('landingPage.home');

    Route::get('privacy-policy', function () {
        return view('web.landing_page.privacy-policy');
    })->name('landingPage.privacyPolicy');
});
