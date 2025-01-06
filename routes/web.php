<?php

// use App\Http\Controllers\PDFController;
use App\Models\Report;
use App\Models\UserApproval;
// use App\Models\User;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to('/access');
});

// Route::post('/send-pdf', [PDFController::class, 'uploadAndSendPDF'])->name('send-pdf');
// Route::get('/pdf', [PDFController::class, 'index'])->name('pdf');

// Route::get('/rancang', function () {
//     $images_temp = Report::first()->images;
//     $tempApproval = UserApproval::with('user')->get();
//     foreach ($images_temp as $image) {
//         $path = env('APP_URL').'/storage/'.$image; // Get the file path
//         $type = pathinfo($path, PATHINFO_EXTENSION);
//         $data = file_get_contents($path); // Read the file contents
//         $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
//         $images[] = $base64; // Store Base64-encoded image
//     }

//     foreach ($tempApproval as $imageAppr) {
//         $ttd_path = $imageAppr['ttd_path'];
//         $pathTemp = env('APP_URL').'/storage/'. $ttd_path; // Get the file path
//         $type = pathinfo($pathTemp, PATHINFO_EXTENSION);
//         $dataApprov = file_get_contents($pathTemp); // Read the file contents
//         $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataApprov);
//         $approval[] = $base64; // Store Base64-encoded image
//     }
    
//     // change the ttd_path to base64 in $tempApproval
//     $tempApproval = $tempApproval->toArray();
//     foreach ($tempApproval as $key => $value) {
//         $tempApproval[$key]['ttd_path'] = $approval[$key];
//     }

//     $datas = [
//         'approval' => $tempApproval
//     ];
//     // dd($datas);
//     return view('pdf.images', compact('images', 'datas'));
// });