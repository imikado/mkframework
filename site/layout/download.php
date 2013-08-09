<?php
$nomFichierTab=preg_split('/./',$this->file);
$tabExtension=array(
					"gz"=> "application/x-gzip",
					"tgz"=> "application/x-gzip",
					"zip"=> "application/zip",
					"pdf"=> "application/pdf",
					"png"=> "image/png",
					"gif"=> "image/gif",
					"jpg"=> "image/jpeg",
					"txt"=> "text/plain",
					"htm"=> "text/html",
					"html"=> "text/html",
					"csv" => "text/csv"
					);
					
if(isset($tabExtension[$nomFichierTab[ count($nomFichierTab)-1 ] ]) ){
	$type=$tabExtension[$nomFichierTab[ count($nomFichierTab)-1 ] ];
}					
else{
	$type = "application/octet-stream";
}

//print_r($nomFichierTab);
//echo $type;

header("Content-disposition: attachment; filename=$nomFichier");
header("Content-Type: application/force-download");
header("Content-Transfer-Encoding: $type\n"); // Surtout ne pas enlever le \n
//header("Content-Length: ".filesize($nomFichier));
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public");
header("Expires: 0");

echo $this->load('main');
?>