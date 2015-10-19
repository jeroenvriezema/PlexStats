<?php
	$configFileLoc = '../config/config.php';
	if( file_exists($configFileLoc) ) {
		require_once(dirname(__FILE__).'/'.$configFileLoc);
	}
	else {
		$plexWatch['lang'] = "en_EN";
	}
	$langXMLFile = dirname(__FILE__)."/../langs/".$plexWatch['lang'].".xml";
	if (file_exists($langXMLFile)) {
		$strings = simplexml_load_file($langXMLFile);
	}
?>