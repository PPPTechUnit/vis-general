<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VoterlistController;
use App\Http\Controllers\Api\ApiHelperController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('add-user-sync', [AuthController::class, 'addUserSync']);
Route::post('searched-sync', [AuthController::class, 'searchedSync']);



// V2 API's
Route::get('new-otp', [AuthController::class, 'getNewOtp']);


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('verify-token', [AuthController::class, 'verifyToken']);
Route::post('user-details', [AuthController::class, 'userDetail']);

Route::post('logout', [AuthController::class, 'logout']);
Route::post('delete-account', [AuthController::class, 'deleteAccount']);

Route::get('get-polling-stations/{slug}', [AuthController::class, 'getPOllingStations']);

Route::get('get-occupation', [AuthController::class, 'getOccupation']);
Route::get('get-education', [AuthController::class, 'getEducation']);


// VOTER LIST
Route::group(['prefix' => 'voter-list'], function() {

    Route::get('blockcode-survey', [VoterlistController::class, 'userBlockcodeSurvey']);
    Route::get('ps-blockcode-survey', [VoterlistController::class, 'userPSBlockcodeSurvey']);
    Route::get('blockcode-survey/blockcode/{blockcode}', [VoterlistController::class, 'userBlockcodeSurveyDetails']);

    Route::get('/blockcode-servey-count/{blockcode}',  [VoterlistController::class, 'countBlockcodeServery']);
    Route::post('/survey-gharana', [VoterlistController::class, 'serveyGharana']);
    Route::post('/survey-gharana-web', [VoterlistController::class, 'serveyGharanaWeb']);

    Route::post('/get-count-add-cnic', [VoterlistController::class, 'getAllCountCNIC']);
    Route::post('/user-cnic-count', [VoterlistController::class, 'userCnicCount']);
    Route::post('/user-cnic-ps-blockcode', [VoterlistController::class, 'getAllCountCNIC_PS_Blockcode']);


    Route::post('/update-profile', [VoterlistController::class, 'updateProfile']);
    Route::post('/get-location', [VoterlistController::class, 'getLocation']);
    Route::post('get-voter-information-by-cnic', [VoterlistController::class, 'getVoterInformationByCNIC']);
    Route::post('sync-data', [VoterlistController::class, 'syncUserData']);
    Route::post('sync-data-uc-delimitation', [VoterlistController::class, 'syncUserDataUcDelimitation']);
    Route::post('sync-data-blockcode-information', [VoterlistController::class, 'syncUserDataBlockcodeInformation']);
    Route::post('sync-data-voter-information', [VoterlistController::class, 'syncUserDataVoterInformation']);
    Route::post('sync-data-voterlist', [VoterlistController::class, 'syncUserDataVoterList']);
});


//Helpers
Route::post('get-provinces', [ApiHelperController::class, 'getProvinces']);
Route::post('get-division', [ApiHelperController::class, 'getDivisions']);
Route::post('get-districts', [ApiHelperController::class, 'getDistricts']);
Route::post('get-tehsils', [ApiHelperController::class, 'getTehsils']);
Route::post('get-ucs', [ApiHelperController::class, 'getUCs']);
Route::post('get-selection-criteria', [ApiHelperController::class, 'getSelectionCriteria']);

