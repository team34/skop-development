<?php 
ob_start();
$pathurl =  plugin_dir_url(__FILE__); 
$parentpath = dirname(plugin_dir_path( __FILE__ ));
$dirpathurl =  dirname(plugin_dir_url(__FILE__)); 
$dirpath = plugin_dir_path( __FILE__ );
$templatepath = $parentpath.'/templates';
$accessFrom = $_SERVER["HTTP_REFERER"];

$url = plugins_url();
$pluginURL = $url."/skopes/frontend/wordtemplate/files";
$full_path = getcwd();

require_once dirname($parentpath).'/classes/CreateDocx.inc';
require_once $parentpath.'/codefiles/function.php';
$docx = new CreateDocx();
global $wp,$wpdb;

$objUser = new clsSkopes();
$userid = get_current_user_id();

$docxfile = 'RFQ_Response_result'.$userid.'_html.docx';
$filename = $parentpath.'/docx/'.$docxfile;
$downloadPath = $pluginURL.'/docx/'.$docxfile;
$downloadFileName = base64_encode('RFQ_Response.pdf');

// check if create doc file exist , then convert it into PDF

if(file_exists($filename)){

	//$document = new TransformDoc();
	//$document->setStrFile($filename);
	//$document->generatePDF();  
	
	$newdocx = new getdocx();
	$template_filename ='RFQ_Response_template';
	$result_filename ='RFQ_Response_result'; 
	
	$filename1 = $newdocx->createuserdocx($template_filename,$result_filename);
	$docx->createDocx($filename1);
	
	exec("java -jar ".$full_path."/wp-content/plugins/skopes/frontend/wordtemplate/lib/openoffice/jodconverter-2.2.2/lib/jodconverter-cli-2.2.2.jar $filename $filename.pdf");
	
	/* exec("java -jar /home/beta/public_html/wp-content/plugins/skopes/frontend/wordtemplate/lib/openoffice/jodconverter-2.2.2/lib/jodconverter-cli-2.2.2.jar $filename $filename.pdf"); */
	
	$finalFile = base64_encode($downloadPath.".pdf");  
	$url = site_url(); 
	//$file = $url.'/?act=getreport&pdffile='.$finalFile."&downloadFileName=".$downloadFileName; 
	$file = $accessFrom.'&pdffile='.$finalFile.'&downloadFileName='.$downloadFileName; 
	ob_end_clean();
	header("Location: $file");
	
}else{

// if file does not exist the create doc file first then convert it to PDF

	
	$newdocx = new getdocx();
	$template_filename ='RFQ_Response_template';
	$result_filename ='RFQ_Response_result'; 
	
	$filename1 = $newdocx->createuserdocx($template_filename,$result_filename);
	$docx->createDocx($filename1);
	
	//$document = new TransformDoc();	 
	//$document->setStrFile($filename);
	//$document->generatePDF();
	
	exec("java -jar ".$full_path."/wp-content/plugins/skopes/frontend/wordtemplate/lib/openoffice/jodconverter-2.2.2/lib/jodconverter-cli-2.2.2.jar $filename $filename.pdf");
	
	/* exec("java -jar /home/beta/public_html/wp-content/plugins/skopes/frontend/wordtemplate/lib/openoffice/jodconverter-2.2.2/lib/jodconverter-cli-2.2.2.jar $filename $filename.pdf"); */
	
	$finalFile = base64_encode($downloadPath.".pdf");  
	$url = site_url(); 
	//$file = $url.'/?act=getreport&pdffile='.$finalFile."&downloadFileName=".$downloadFileName; 
	$file = $accessFrom.'&pdffile='.$finalFile.'&downloadFileName='.$downloadFileName; 
	ob_end_clean();
	header("Location: $file");
	
}
?>
