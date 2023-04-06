<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
//use Barryvdh\DomPDF\Facade\Pdf;



class ConvertController extends Controller
{
    public function index(){
        return view('pdf');
    }
    public function convertDocToPDF(){
        
        $file = public_path('document/uploaded/sample3.docx');
        $file_pdf = public_path('document/uploaded/sample3.pdf');

        //$data  = file_get_contents($file);
        //header('Content-Type: application/docx');
        //header('Content-Length: '.strlen($data));
       // header('X-Content-Type-Options: nosniff');
       $filePath = $file;
      
      
       
    
       
    
        echo $data;
       
        // Make sure that 'system' is available. Perhaps with regexp...
        
      
       // die(ini_get('disable_functions'));
        // Set the new settings
        //echo `ls`;
       
        //phpinfo();
       // $output = "The current user is " . shell_exec('whoami');
       // echo $output;

        /*
        $domPdfPath = base_path('vendor/dompdf/dompdf');
        
        \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
        \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
       
      
        \PhpOffice\PhpWord\Settings::setPdfRenderer('DomPDF', $domPdfPath);
      
      
        $file = public_path('document/uploaded/sample3.docx');
        
        $Content = \PhpOffice\PhpWord\IOFactory::load($file);
       
       
        $HTMLWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content,'HTML');
        $HTMLWriter->save(public_path('document/converted/sample3.html'));
       
        $sample3 = public_path('document/converted/sample3.html');
        $pdf_sample = public_path('document/converted/sample3.pdf');
       
        //$HTMLContent = \PhpOffice\PhpWord\IOFactory::load($sample3); 
        $text = Pdf::loadFile($sample3)->save($pdf_sample)->stream($pdf_sample);
        //echo "<pre>";
        //print_r($HTMLContent);
        */
       
        echo 'File has been successfully converted';
        
    }
}