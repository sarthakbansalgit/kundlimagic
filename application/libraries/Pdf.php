<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//namespace Dompdf;
require_once(APPPATH . 'libraries/dompdf/autoload.inc.php');

use Dompdf\Dompdf;
ini_set('display_errors', 1);
class Pdf 
{
    function createPDF($html, $filename='', $download=TRUE, $paper='A4', $orientation='portrait'){
        //ob_start();
      
         $dompdf = new Dompdf();
         $dompdf->loadHtml($html);
     $dompdf->setPaper($paper, $orientation);
         $dompdf->render();
         
         if($download)
             $dompdf->stream($filename.'.pdf', array('Attachment' => 1));
         else
             $dompdf->stream($filename.'.pdf', array('Attachment' => 0));
        //ob_clean();
    }
}
?>