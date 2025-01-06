<?php
namespace App\Helpers;

use App\Models\UserApproval;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MailHelpers
{
    public static function sendPDF(array $tempImages, $user, array $dataItem)
    {

        $images = [];
        if($tempImages){
            foreach ($tempImages as $image) {
                $path = env('APP_URL').'/storage/'.$image; // Get the file path
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path); // Read the file contents
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $images[] = $base64; // Store Base64-encoded image
            }
        }

        $tempApproval = UserApproval::with('user')->get();
        foreach ($tempApproval as $imageAppr) {
            $ttd_path = $imageAppr['ttd_path'];
            $pathTemp = env('APP_URL').'/storage/'. $ttd_path; // Get the file path
            $type = pathinfo($pathTemp, PATHINFO_EXTENSION);
            $dataApprov = file_get_contents($pathTemp); // Read the file contents
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataApprov);
            $approval[] = $base64; // Store Base64-encoded image
        }

        // change the ttd_path to base64 in $tempApproval
        $tempApproval = $tempApproval->toArray();
        foreach ($tempApproval as $key => $value) {
            $tempApproval[$key]['ttd_path'] = $approval[$key];
        }

        $datas = [
            'name' => $user->name,
            'email' => $user->email,
            'data' => $dataItem,
            'approval' => $tempApproval
        ];
        
        // Generate the PDF with the images
        $pdf = Pdf::loadView('pdf.images', compact('images', 'datas'));

        // Store the PDF temporarily
        $pdfPath = storage_path('app/public/temp.pdf');
        file_put_contents($pdfPath, $pdf->output());

        // Send the email
        $email = $user->email;
        $pdfRes = Mail::send('emails.pdf', $datas, function ($message) use ($pdfPath, $email) {
            $message->to($email)
                    ->subject('Your PDF with Images')
                    ->attach($pdfPath, [
                        'as' => 'Images.pdf',
                        'mime' => 'application/pdf',
                    ]);
        });

        // $pdfRes = $pdfRes ? 'success' : 'failed';
        // if ($pdfRes === 'failed') {
        //     return response()->json(['message' => 'Failed to send PDF!'], 500);
        // }

        // Delete the temporary PDF
        unlink($pdfPath);

        return response()->json(['message' => 'PDF sent successfully!']);
    }
}