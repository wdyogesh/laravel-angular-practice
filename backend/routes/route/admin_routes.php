<?php

use Illuminate\Http\Request;
use App\Http\Middleware\SuperAdmin;

// Module route
Route::get('all-modules', 'Admin\ModulesController@getAllModules')->middleware([SuperAdmin::class]);
Route::post('update-modules', 'Admin\ModulesController@updateModules')->middleware([SuperAdmin::class]);

Route::get('active-modules', 'Admin\ModulesController@getActiveModules');
Route::get('get-profile', 'Admin\AdminManagementController@getProfile');

//  for User Management routes
Route::prefix('user-management')->group(function() {

    $controller='Admin\UserManagementController@';

    Route::post('get-all-users',$controller.'getUserData');

    Route::get('get-user-count/{role_id}', $controller . 'getUserCount');

    Route::get('get-user-details/{id}',$controller.'getUserDetails');

    Route::post('update-profile', $controller.'updateProfileData');

    Route::post('update-user-profile', $controller.'updateUserProfile');

    Route::post('toggle-user', $controller.'toggleUser');

    Route::post('do-update-profile-picture', $controller.'doUpdateProfilePicture');

    Route::get('delete-user-permanently/{id}', $controller.'deleteUserPermanently');

    Route::post('delete-multiple-users-permanently', $controller.'deleteMultipleUsersPermanently');
});

// for Admin Management routes
Route::prefix('admin-management')->group(function() {

    $controller = 'Admin\AdminManagementController@';

    Route::post('get-admin-data', $controller.'getAdminData');

    Route::get('get-admin-detail/{id}',$controller.'getAdminDetails');

    Route::post('/add-admin', $controller.'addAdmin');
});

//for email templates relaetd routes
Route::prefix('email')->group(function() {

    $controller='MailController@';

    Route::get('/get-email-templates',$controller.'getEmailTemplates');

    Route::get('/get-email-template-data/{title}', $controller.'getEmailTemplate');

    Route::post('/update-email-template-data', $controller.'updateEmailTemplateData');
});

Route::prefix('cms')->group(function() {
    $controller = 'Admin\CMSController@';

    Route::get('/get-cms-list', $controller.'getCmsList');

    Route::get('/get-cms-detail/{id}', $controller.'getCmsDetail');

    Route::post('/update-cms-detail', $controller.'updateCMSDetail');
});

Route::prefix('setting')->group(function() {
    $controller = 'Admin\SettingController@';
    // Route::post('/add-payment-gateway', $controller.'addPaymentGateway');
    // Route::get('/get-payment-gateway/{id}', $controller.'getPaymentGateway');
    // Route::get('/get-mail-details', $controller.'getEmailDetails');
    // Route::get('/get-all-keys', $controller.'getAllKeys');
    Route::get('/get-keys-meta', $controller.'getKeysMeta');
    Route::post('/add-keys-meta', $controller.'addKeysMeta');
    Route::get('/get-api-details/{id}', $controller . 'getApiDetails');
    Route::post('/edit-api', $controller . 'editApi');
    Route::get('/delete-api/{id}', $controller . 'deleteApi');
    Route::post('/add-keys', $controller.'addKey');
    Route::get('/get-keys/{id}', $controller.'getKey');
    Route::get('/delete-api-key/{id}', $controller. 'deleteKey');
});
