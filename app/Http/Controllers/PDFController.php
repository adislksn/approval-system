<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // For Dompdf
use Illuminate\Support\Facades\Mail;

class PDFController extends Controller
{
    public function index()
    {
        return view('pdf.index');
    }
    
    public function uploadAndSendPDF(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'required|email',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->getRealPath(); // Get the file path
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path); // Read the file contents
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $images[] = $base64; // Store Base64-encoded image
            }
        }

        // Generate the PDF with the images
        $pdf = PDF::loadView('pdf.images', compact('images'));

        // Store the PDF temporarily
        $pdfPath = storage_path('app/public/temp.pdf');
        file_put_contents($pdfPath, $pdf->output());

        // Send the email
        Mail::send('emails.pdf', [], function ($message) use ($pdfPath, $request) {
            $message->to($request->email)
                    ->subject('Your PDF with Images')
                    ->attach($pdfPath, [
                        'as' => 'Images.pdf',
                        'mime' => 'application/pdf',
                    ]);
        });

        // Delete the temporary PDF
        unlink($pdfPath);

        return response()->json(['message' => 'PDF sent successfully!']);
    }

}
