<?php

function prep_pdf($orientation = 'landscape')
{
	$CI = & get_instance();
	
	$CI->cezpdf->selectFont(base_url() . '/fonts');	
	
	$all = $CI->cezpdf->openObject();
	$CI->cezpdf->saveState();
	$CI->cezpdf->setStrokeColor(0,0,0,1);
	if($orientation == 'portrait') {
		$CI->cezpdf->ezSetMargins(50,70,50,50);
		$CI->cezpdf->ezStartPageNumbers(500,28,8,'','{PAGENUM}',1);
		$CI->cezpdf->line(20,40,578,40);
		$CI->cezpdf->addText(50,32,8,'Printed on ' . date('m/d/Y h:i:s a'));
		$CI->cezpdf->addText(50,22,8,'Copyright 2012. The Grand Palace Hotel Yogyakarta');
	}
	else {
		$CI->cezpdf->ezStartPageNumbers(1160,28,8,'','{PAGENUM}',1);
		$CI->cezpdf->line(20,40,1165,40);
		$CI->cezpdf->addText(50,32,8,'Printed on ' . date('d M Y h:i:s a'));
		$CI->cezpdf->addText(50,22,8,'Copyright 2012. The Grand Palace Hotel Yogyakarta');
	}
	$CI->cezpdf->restoreState();
	$CI->cezpdf->closeObject();
	$CI->cezpdf->addObject($all,'all');
}

?>