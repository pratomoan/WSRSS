<?php 
	$ns = "http://".$_SERVER['HTTP_HOST']."/wsrss/wsrss.php";//setting namespace
	require_once 'lib/nusoap.php'; // load nusoap toolkit library in controller
	$server = new soap_server; // create soap server object
	$server->configureWSDL("RSS WEB SERVICE WITH SOAP WSDL", $ns); // wsdl configuration
	$server->wsdl->schemaTargetNamespace = $ns; // server namespace

	########################RSS##############################################################
	// Complex Array Keys and Types RSS++++++++++++++++++++++++++++++++++++++++++
	$server->wsdl->addComplexType("newsData","complexType","struct","all","",
		array(
		"rssurl"=>array("name"=>"rssurl","type"=>"xsd:string")
		)
	);
	// Complex Array RSS++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$server->wsdl->addComplexType("rssArray","complexType","array","","SOAP-ENC:Array",
		array(),
		array(
			array(
				"ref"=>"SOAP-ENC:arrayType",
				"wsdl:arrayType"=>"tns:newsData[]"
			)
		),
		"newsData"
	);
	// End Complex Type RSS++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//GET RSS 
	$input_getrss = array('rssurl' => "xsd:string"); // get rss parameter
	$return_getrss = array("return" => "xsd:string");
	$server->register('getrss',
		$input_getrss,
		$return_getrss,
		$ns,
		"urn:".$ns."/getrss",
		"rpc",
		"encoded",
		"Fetch New Rss Data");
	//GET RSS END
	//Grab All RSS
	$input_readrss = array(); // parameter readrss
	$return_readrss = array("return" => "tns:rssArray");
	$server->register('readrss',
		$input_readrss,
		$return_readrss,
		$ns,
		"urn:".$ns."/readrss",
		"rpc",
		"encoded",
		"Grab ALL Rss Data");
	//Grab All RSS END
	################################RSS#######################################################
	###########################RSS FUNCTION###################################################
	function GetRss($rssurl){
		require_once 'classDb/ClassRSS.php';
		$getrss = new ClassRSS();
		if ($getrss->GetRss($rssurl)) {
			$response = "Pass";
		}else{
			$response = "Error";
		}
		return $response;
	}
	
	function readrss(){
		require_once 'classDb/ClassRSS.php';
		$getrss = new ClassRSS();
		$result = $getrss->readrss();
		$rssdata = array();
		while ($item = $result->fetch_assoc()) {
			array_push($rssdata, array('newsheadline'=>$item['newsheadline'],'newsurl'=>$item['newsurl']));
		}
		return $rssdata;
	}
	
	$server->service(file_get_contents("php://input"));
 ?>