<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

function pdf_create($html, $filename='', $paper, $orientation, $stream=TRUE) 
{
    // require_once("dompdf/dompdf_config.inc.php");

    $dompdf = new DOMPDF(['isRemoteEnabled' => true]);
    $dompdf->set_paper($paper,$orientation);
    $dompdf->load_html($html);
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename.".pdf");
    } else {
        return $dompdf->output();
    }
}

function pdf_create2($output, $filename, $paper_type) 
{
    $dompdf = new Dompdf(['isRemoteEnabled' => true]);
    $dompdf->loadHtml($output);
    $dompdf->setPaper('A4', $paper_type);
    $dompdf->render();
    $dompdf->stream($filename);
}
?>