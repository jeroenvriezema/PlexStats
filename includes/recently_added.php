<?php
if (file_exists(dirname(__FILE__) . '/lang.php')) {
	require_once(dirname(__FILE__) . '/lang.php');
}
if (file_exists(dirname(__FILE__) . '/functions.php')) {
	require_once(dirname(__FILE__) . '/functions.php');
}
	global $plexServer;
	if(isset($_GET['width'])) {
		$containerSize = 1; // min size?
		$tmp = $_GET['width']/182;
		if ($tmp > 0) { 
			$containerSize = $tmp; 
			if (!isset($singlerow)) {  
				$containerSize = $containerSize*1;    
			}  
		}
		$containerSize = floor($containerSize);
	}
	$myPlexAuthToken = $plexServer->plexAuthToken();
	
	if ($fileContents = file_get_contents($plexServer->plexURL()."/library/recentlyAdded?X-Plex-Container-Start=0&X-Plex-Container-Size=".$containerSize."&X-Plex-Token=".$myPlexAuthToken)) {
		$recentRequest = simplexml_load_string($fileContents) or die ("<div class='alert alert-warning'>Failed to access Plex Media Server. Please check your settings.</div>");
	}
	$output = '';
	$i = 1;
	foreach ($recentRequest->children() as $recentXml) {
		$recentTime = $recentXml['addedAt'];
		$timeNow = time();
		$age = time() - strtotime($recentTime);
		$subTitle = $plexServer->lang()->index->added." ".$plexServer->TimeAgo($recentTime)." ".$plexServer->lang()->index->added2;
		$recentArtUrl = $plexServer->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexServer->plexURL("httpPort").$recentXml['art']."&width=320&height=160";
		$recentThumbUrl = $plexServer->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexServer->plexURL("httpPort").$recentXml['thumb']."&width=153&height=225";
		$posterURL = "info.php?id=" .$recentXml['ratingKey'];
		if($recentXml['thumb']) {
			$poster = "includes/img.php?img=".urlencode($recentThumbUrl);
		}
		else {
			$poster = "images/poster.png";
		}
		if ($recentXml['type'] == "season") {
			$title =  "S".$recentXml['index'];
			$title2 = $recentXml['parentTitle'];
			$epString = simplexml_load_string(file_get_contents($plexServer->plexURL()."/library/metadata/".$recentXml['ratingKey']."/children/".$recentXml['ratingKey']."?X-Plex-Token=".$myPlexAuthToken)) or die ("FOUT!!1");
			foreach($epString as $epMatch) {
				$match = strcmp($recentXml['addedAt'], $epMatch['addedAt']);
				if($match == 0) {
				$episode = "E".$epMatch['index'];
				$ratingKey = $epMatch['ratingKey'];
				}
			}
				
				
		}
		else if($recentXml['type'] == "movie") {
			$title = $recentXml['title'];
			$title2 = $recentXml['year'];
		}
		if ($i == 1) {
			$htmlStart = "<div class='row'>";
			$htmlEnd = "";
		}
		if ($i >= 2) {
			$htmlStart = '';
		}
		if($i == $containerSize) {
			$htmlEnd = "</div>";
		}
		$col = floor(10 / $containerSize);
		if ($col < 1) {
			if($i == 10) {
				$htmlEnd = "</div>";
				$i = 0;
			}
			$col = 1;
		}
		if($recentXml['type'] == "movie") {
			$output .= $htmlStart."<div class='col-xs-6 col-sm-".$col." col-md-".$col." col-lg-".$col."'><a href='info.php?id=" .$recentXml['ratingKey']. "'><img src='".$poster."'><h3>".$title."</h3></a><a href='info.php?id=" .$recentXml['ratingKey']. "'><h4 class='help-block'>".$title2."</h4></a><h5 class='help-block'>".$subTitle."</h5></div>".$htmlEnd;
		}
		else if ($recentXml['type'] == "season") {
			$output .= $htmlStart."<div class='col-xs-6 col-sm-".$col." col-md-".$col." col-lg-".$col."'><a href='info.php?id=".$ratingKey."'><img src='".$poster."'></a><a href='info.php?id=".$recentXml['parentRatingKey']."'><h3>".$title2."</h3></a><a href='info.php?id=".$ratingKey."'><h4 class='help-block'>".$title." ".$episode."</h4></a><h5 class='help-block'>".$subTitle."</h5></div>".$htmlEnd;
		}
		$i++;
	}
	echo $output;
?>