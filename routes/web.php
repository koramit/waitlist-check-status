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
Route::post('get-status', function (Illuminate\Http\Request $request) {
    if ( $request->token != env('KT_token') ) {
        return ['reply_code' => 0, 'reply_text' => 'not allow'];
    }

    $status = App\CurrentStatus::getStatus($request->hn);

    $patient = (new App\APIs\PatientDataProvider)->getPatient($request->hn);

    $reply  = "ผป. : " . $request->hn . "\n";
    $reply .= "ชื่อ : " . (isset($patient['first_name']) ? mb_substr($patient['first_name'], 0, 2) . '***' : '') . "\n";
    $reply .= "สกุล : " . (isset($patient['last_name']) ? mb_substr($patient['last_name'], 0, 2) . '***' : '') . "\n";
    $reply .= "ตรวจเมื่อ : " . ($status->DateFU ? $status->DateFU->format('d-m-Y') : '') . "\n";
    $reply .= "สถานะ : " . $status->getStatusThai() . "\n";

    return ['reply_code' => 0, 'reply_text' => $reply];
});
