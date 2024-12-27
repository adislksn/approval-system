<?php
namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MailHelpers
{
    public static function sendPDF(array $images, $user, array $dataItem)
    {

        if($images){
            foreach ($images as $image) {
                $path = env('APP_URL').'/storage/'.$image; // Get the file path
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path); // Read the file contents
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $images[] = $base64; // Store Base64-encoded image
            }
        }

        $datas = [
            'name' => $user->name,
            'email' => $user->email,
            'data' => $dataItem,
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
        // unlink($pdfPath);

        return response()->json(['message' => 'PDF sent successfully!']);
    }
}