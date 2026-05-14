<?php


use App\Http\Controllers\ImportController;
use App\Http\Controllers\VoterListController;
use App\Http\Controllers\VerifierController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BlockcodeController;
use App\Http\Controllers\Admin\BlockcodeInformationController;
use App\Http\Controllers\Admin\VoterlistBlockcodeController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Backend\TotalRecordsController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\ConstituencyController;
use App\Http\Controllers\Backend\UserPaymentController;
use App\Http\Controllers\Backend\BlockcodeDelimitationsController;
use App\Http\Controllers\Backend\VerifierUsersUCWiseController;
use App\Http\Controllers\Backend\AppWebUsersController;
use App\Http\Controllers\Backend\ImportVoterlistController;
use App\Http\Controllers\Backend\BlockcodeVotelistController;
use App\Http\Controllers\Backend\NotificationController;
/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| USER Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect('/backend/dashboard');
    })->name('dashboard');
});


Route::post('/logout-user', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout-user');







//OLD ROUTES NEW



Route::get('/survey-workers/login', [SurveyWorkersController::class, 'surveyLogin']);
Route::any('/survey-workers/login-submit', [SurveyWorkersController::class, 'surveyLoginSubmit']);
Route::get('/survey-workers/dashboard', [SurveyWorkersController::class, 'dashboard']);
Route::get('/survey-workers/new-otp', [SurveyWorkersController::class, 'getNewOTP']);





//OLD ROUTES END

Route::get('/auto_import_files', [ImportController::class, 'auto_import_files']);


Route::get('/import_blockcode', [VerifierController::class, 'import_blockcode']);
Route::post('/import-blockcode', [VerifierController::class, 'import'])->name('blockcode.import');
Route::get('/import_voterlist', [VerifierController::class, 'import_voterlist']);
//Route::post('/import-voters', [VerifierController::class, 'importvoters'])->name('importvoters.import');
Route::post('/import-voters', [VerifierController::class, 'importvotersUrdu'])->name('importvoters.import');



Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', [VoterListController::class, 'index'])->name('user.dashboard');
    Route::get('/voterlist/{blockcode}', [VoterListController::class, 'voterListing']);


});



// Route::middleware(['auth', 'role:user'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('user.dashboard');
//     })->name('user.dashboard');
// });

/*
|--------------------------------------------------------------------------
| ADMIN Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'backend'], function() {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('backend_dashboard');


    Route::resource('notifications', NotificationController::class)->names('backend.notifications');
    Route::get('/notification/verify/{id}',[NotificationController::class, 'verify'])->name('notification-verify');




    // TESTING
    Route::get('/blockcode-xlsx-import', [ImportVoterlistController::class, 'blockcodeInformationsFormXlsx']);
    Route::post('/blockcode-xlsx-import-submit', [ImportVoterlistController::class, 'blockcodeInformationsImportXlsx']);

    // TESTING
    Route::get('/voterlist-xlsx-import', [ImportVoterlistController::class, 'voterInformationsFormXlsx']);
    Route::post('/voterlist-xlsx-import-submit', [ImportVoterlistController::class, 'votelistInformationsImportXlsx']);
    // TESTING
    Route::get('/update-polling-scheme', [ImportVoterlistController::class, 'updatePollingSchemeForm']);
    Route::post('/update-polling-scheme-submit', [ImportVoterlistController::class, 'updatePollingSchemeSubmit']);

    Route::get('/update-voterlist-voters', [ImportVoterlistController::class, 'updateVoterlistForm']);
    Route::post('/update-voterlist-voters-submit', [ImportVoterlistController::class, 'updateVoterlistSubmit']);



    // New Users
    Route::resource('/verifiers','App\Http\Controllers\Backend\VerifierController');
    Route::resource('/verifiers-stats','App\Http\Controllers\Backend\VerifierStatsController');
    Route::resource('/verifier-users-uc-wise','App\Http\Controllers\Backend\VerifierUsersUCWiseController');
    //
    Route::any('/verifiers-status/date-export-csv', [VerifierStatusController::class, 'pdfExportDateWise']);

    Route::any('/verifiers-blockcode-assign', [VerifierAdminController::class, 'verifierBlockcodeAssign']);
    Route::any('/verifiers-blockcode-assigned', [VerifierAdminController::class, 'verifierBlockcodeAssigned']);
    Route::any('/verifiers-mulitple-blockcode-assign', [VerifierAdminController::class, 'verifierMultipleBlockcodeAssigned']);


    Route::get('/verifier-user-ucwise/delete/{id}', [VerifierUsersUCWiseController::class, 'deleteuser']);
    Route::get('/verifier-user-ucwise/verify/{id}', [VerifierUsersUCWiseController::class, 'verifyuser']);
    Route::get('/blockcode-informations', [ImportVoterlistController::class, 'blockcodeInformationsForm']);
    Route::post('/blockcode-informations-import', [ImportVoterlistController::class, 'blockcodeInformationsImport']);
    Route::post('/voter-informations-import', [ImportVoterlistController::class, 'voterInformationsImport']);

    Route::get('/voter-informations', [ImportVoterlistController::class, 'voterInformationsForm']);
    Route::get('/blockcode-files', [ImportVoterlistController::class, 'blockcodeFilesForm']);
    Route::post('/upload-blockcode-files', [ImportVoterlistController::class, 'uploadBlockcodeFiles']);

    Route::get('/import-update-blockcode-in-voter-information', [ImportVoterlistController::class, 'voterUpdateBlockcodeInVoterInformationForm']);
    Route::post('/submit-update-blockcode-in-voter-information', [ImportVoterlistController::class, 'voterUpdateBlockcodeInVoterInformationSubmit']);

    Route::resource('/app-web-users','App\Http\Controllers\Backend\AppWebUsersController');
    Route::get('/app-web-users-location/{ids}', [AppWebUsersController::class, 'userLocation']);
    Route::patch('/app-web-users-kill-switch/{id}', [AppWebUsersController::class, 'toggleKillSwitch'])->name('app-web-users.kill-switch');

    Route::get('/ppp-users', [AppWebUsersController::class, 'getPPPUsers']);
    Route::get('/searched-voters', [AppWebUsersController::class, 'searchedVoters']);

    Route::get('/app-web-users-kill-switch/{ids}', [AppWebUsersController::class, 'killSwitch']);
    Route::get('/app-web-users-export', [AppWebUsersController::class, 'usersExport']);
    Route::get('/app-web-users-import', [AppWebUsersController::class, 'importUsers']);
    Route::post('/app-web-users-import-submit', [AppWebUsersController::class, 'importingAppWebUsers']);


    Route::resource('/blockcodes-voterlists','App\Http\Controllers\Backend\BlockcodeVotelistController');






    Route::resource('/blockcodes-delimitations','App\Http\Controllers\Backend\BlockcodeDelimitationsController');
    Route::resource('/total-records','App\Http\Controllers\Backend\TotalRecordsController');
    Route::resource('/users-payments','App\Http\Controllers\Backend\UserPaymentController');


    // Export
    Route::any('/date-export-csv', [TotalRecordsController::class, 'pdfExportDateWise']);
    Route::any('/time-export-csv', [TotalRecordsController::class, 'pdfExportDateTime']);
    Route::any('/month-export-csv', [TotalRecordsController::class, 'pdfExportMonthWise']);




    Route::get('/count-stats', [HomeController::class, 'countStats']);

    Route::resource('/users','App\Http\Controllers\Backend\UsersController');
    Route::get('/user/delete/{id}', [UsersController::class, 'destroy']);
    Route::get('/user/verify/{id}', [UsersController::class, 'verify']);
    Route::resource('/constituencies','Backend\ConstituencyController');
    Route::resource('/team-users','App\Http\Controllers\Backend\TeamUsersController');
    Route::resource('/blockcodes','App\Http\Controllers\Backend\BlockcodeController');
    Route::get('/blockcodes/view-records/{blockcode}',[BlockcodeController::class, 'viewBlockCodeRecord']);
    Route::get('/blockcodes/edit-records/{blockcode}',[BlockcodeController::class, 'editBlockCodeRecord']);
    Route::get('/blockcodes/view-records/delete/{blockcode}',[BlockcodeController::class, 'deleteBlockCodeRecord']);
    Route::get('/blockcodes/view-records/delete-record/{id}',[BlockcodeController::class, 'deleteVoterRecord']);

    Route::get('/blockcodes/delete/{id}', [ConstituencyController::class, 'destroy'])->name('users-blockcodes-delete');

    Route::post('/blockcodes/update-electoral-information', [BlockcodeController::class, 'updateElectoralInformation']);

    Route::get('/blockcodes-user-payment/{id}',[UserPaymentController::class, 'viewUserPayment']);
    Route::post('/blockcodes-user-payment/submit',[UserPaymentController::class, 'submitPayment']);
    Route::get('/blockcodes-user-payment/pdf-print/{user_id}',[UserPaymentController::class, 'printPDF']);

    Route::get('/all-users-payments-submit',[UserPaymentController::class, 'allUserSubmitPayment']);


});




Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::resource('users', UserController::class);
    Route::resource('blockcodes', BlockcodeController::class);
    Route::get('/users-blockcodes/{blockcode}', [BlockcodeController::class, 'showVotersWithBlockcode']);
    Route::get('/blockcodes/voters/{blockcode}', [BlockcodeController::class, 'voterListing']);
    Route::get('/users-dashboard', [UserController::class, 'showUserDashboard']);

});

/*
|--------------------------------------------------------------------------
| verifier Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:verifier'])->prefix('verifier')->group(function () {

    //Route::get('/dashboard', function () {return view('verifier.dashboard');})->name('verifier.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('verifier.dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');


    Route::resource('blockcode-information', BlockcodeInformationController::class);
    Route::delete('blockcode-information/{blockcode_information}', [BlockcodeInformationController::class, 'destroy'])->name('blockcode-information.destroy');


    Route::resource('voterlist-blockcodes', VoterlistBlockcodeController::class);
    Route::post('/voterlist-blockcodes-import', [VoterlistBlockcodeController::class, 'importVoterlist']);
    Route::get('/voterlist-blockcodes-rescan/{blockcode}', [VoterlistBlockcodeController::class, 'rescanVoterlistBlockcode']);
    Route::get('/voterlist-blockcodes-push-to-database/{blockcode}', [VoterlistBlockcodeController::class, 'pushToMainTable']);


    Route::get('/rescan-blockcode', [VoterlistBlockcodeController::class, 'rescanBlockcode']);
    Route::get('/voterlist', [VoterlistBlockcodeController::class, 'voterlist']);



});

/*
|--------------------------------------------------------------------------
| Profile (All Logged Users)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::put('/preview-voters/{voter}', [BlockcodeInformationController::class, 'updatePreview'])->name('voterspreview.update');



Route::post('/update-completed-status', [VoterListController::class, 'updateCompletedStatus'])->name('update.completed.status');
Route::post('/update-testing-status', [VoterListController::class, 'updateTestedStatus']);
Route::post('/ajax/search-blockcode', [VoterListController::class, 'searchBlockcodes']);
Route::post('/ajax/voters/update/{id}', [VoterListController::class, 'updateVoter'])->name('voters.update');
Route::post('/ajax/voters/store', [VoterListController::class, 'storeVoter']);


Route::get('/ajax/get-districts/{id}', [BlockcodeInformationController::class, 'getDistrict']);
Route::get('/ajax/get-tehsils/{id}', [BlockcodeInformationController::class, 'getTehsil']);
Route::any('/blockcode-information/update-status', [BlockcodeInformationController::class, 'updateStatus'])->name('blockcode-information.update-status');
// web.php
Route::get('blockcode-information/{id}/status-data', [BlockcodeInformationController::class, 'getStatusData'])
    ->name('blockcode-information.status-data');

Route::post('/ajax/add-tehsil', [BlockcodeInformationController::class, 'storeTehsil'])->name('ajax.add-tehsil');
Route::post('/ajax/add-district', [BlockcodeInformationController::class, 'storeDistrict'])->name('ajax.add-district');
