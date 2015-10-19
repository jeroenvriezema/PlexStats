<?php
if (file_exists(dirname(__FILE__) . '/../config/config.php')) {
	require_once(dirname(__FILE__) . '/../config/config.php');
}
else {
	die("File config.php not found.");
}

if (file_exists(dirname(__FILE__) . '/functions.php')) {
	require_once(dirname(__FILE__) . '/functions.php');
}
else {
	die("File functions.php not found.");
}



class activity {
	
	function sessionProgressView($option = "default") {
		global $plexServer;
		if (file_exists(dirname(__FILE__) . '/lang.php')) {
			require_once(dirname(__FILE__) . '/lang.php');
		}
		else {
			die("File lang.php not found.");
		}
		$i = 1;
		$iTotal =1;
		$output = '';
		if($plexServer->sessions("count") == "0") {
			$string1 = $plexServer->lang()->activity->noCurrentWatch;
			$output .= "<div class='alert alert-info' role='alert'>".$string1."</div>";
			if(isset($_GET['xml'])) {
				if($_GET['xml'] == "sessions") {
					header('Content-Type: application/xml');
						echo "<noUsers>
								<string1>".$string1."</string1>
							</noUsers>";
				}
			}
		}
		else {
			if($plexServer->sessions("count") == "1") {
				$string2 = $plexServer->lang()->index->userCountPre1User;
				$string3 = $plexServer->lang()->index->userCount1User;
				$output .= "<div class='alert alert-success' role='alert'>".$string2." <strong>".$plexServer->sessions("count")."</strong> ".$string3."</div>";
			}
			if($plexServer->sessions("count") >= "2") {
				$output .= "<div class='alert alert-success' role='alert'>".$plexServer->lang()->index->userCountPreUsers." <strong>".$plexServer->sessions("count")."</strong> ".$plexServer->lang()->index->userCountUsers."</div>";
			}
			foreach ($plexServer->sessions()->Video as $sessions) {
				$l = 0;
				foreach ($plexServer->arrays("platformImage") as $platform => $image) {
					if($sessions->Player['platform'] == $platform) {
						$platformImage = $image;
						break;
					}
					else {
						$platformImage = "images/platforms/default.png";
					}
					$l++;
				}
				if(isset($sessions['librarySectionID'])) {
					if($sessions['type'] == "episode") {
						$sessionsThumbUrl = $plexServer->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexServer->plexURL("httpPort").$sessions['thumb']."&width=300&height=169";
					}
					else if($sessions['type'] == "movie") {
						$sessionsThumbUrl = $plexServer->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexServer->plexURL("httpPort").$sessions['art']."&width=300&height=169";
					}
						$img = "includes/img.php?img=".urlencode($sessionsThumbUrl);
				}
				else {
					$img = "includes/img.php?img=".urlencode($sessions['art']);
				}
				if($sessions['type'] == "episode") {
					$title = $sessions['grandparentTitle']." - ".$sessions['title'];
				}
				else {
					$title = $sessions['title'];
				}
				$url = "info.php?id=".$sessions['ratingKey'];
				$machineID = $sessions->Player['machineIdentifier'];
				$username = $plexServer->FriendlyName($sessions->User['title'],$sessions->Player['title']);
				
				//grid system columns..
				$colums = "12";
				if($plexServer->sessions("count") == "1") {
					$cl = "12";
				}
				else if ($plexServer->sessions("count") >= "2") {
					if($plexServer->sessions("count") >= "13") {
						$cl = "1";
					}
					else {
						$calc = $colums / $plexServer->sessions("count");
						$cl = round($calc);
						
					}
				}
				$xmlCl = "col-md-".$cl." col-xs-12 col-sm-".$cl." col-lg-".$cl;
				
				if($plexServer->sessions("count") >= "13") {
					if($i == 1) {
						$htmlStart = "<div class='row'>";
						$htmlEnd = '';
					}
					if($i == 12) {
						$htmlStart = '';
						$htmlEnd = "</div><div class='row'>";
						$i = 0;
					}
					if($plexServer->sessions("count") == $iTotal) {
						$htmlStart = '';
						$htmlEnd = "</div>";
					}
					$i++;
				}
				else {
					if($i == '1') {
						$htmlStart = "<div class='row'>";
						$htmlEnd = '</div>';
					}
					else if($plexServer->sessions("count") == $iTotal) {
						$htmlStart = '';
						$htmlEnd = "</div>";
					}
					$i++;
				}
				
				$percentComplete = ($sessions['duration'] == 0 ? 0 : sprintf("%2d", ($sessions['viewOffset'] / $sessions['duration']) * 100));
				
				if(empty($sessions->User['title'])) {
					$user = "Local";
				}
				else {
					$user = $sessions->User['title'];
				}
				
				if($sessions->Player['state'] == "playing") {
					$progressBar = "progress-bar progress-bar-success progress-bar-striped active";
					$playStatus = $plexServer->lang()->activity->playing;
				}
				else if($sessions->Player['state'] == "paused") {
					$progressBar = "progress-bar progress-bar-warning progress-bar-striped";
					$playStatus = $plexServer->lang()->activity->paused;
				}
				else if($sessions->Player['state'] == "buffering") {
					$progressBar = "progress-bar progress-bar-danger progress-bar-striped active";
					$playStatus = $plexServer->lang()->activity->buffering;
				}
				if(!array_key_exists('TranscodeSession',$sessions)) {
					$streamType = $plexServer->lang()->activity->directPlay;
					$videoCodec = $sessions->Media['videoCodec']." (".$sessions->Media['width']."x".$sessions->Media['height']."P)";
					if ($sessions->Media['audioCodec'] == "dca") {
						$audioCodec = "DTS (".$sessions->Media['audioChannels']."ch)";
					}
					else if ($sessions->Media['audioCodec'] == "ac3") {
						$audioCodec = $plexServer->lang()->activity->dolby." (".$sessions->Media['audioChannels']."ch)";
					}
					else {
						$audioCodec = $sessions->Media['audioCodec']." (".$sessions->Media['audioChannels']."ch)";
					}
				}
				else if ($sessions->TranscodeSession['audioDecision'] == "transcode") {
					$audioCodec = $plexServer->lang()->activity->transcode." (".$sessions->TranscodeSession['audioCodec'].") (".$sessions->TranscodeSession['audioChannels']."ch)";
				}
				else if ($sessions->TranscodeSession['audioDecision'] == "copy") {
				  
					if ($sessions->TranscodeSession['audioCodec'] == "dca") {
						$audioCodec = $plexServer->lang()->activity->directStream." (DTS) (".$sessions->TranscodeSession['audioChannels']."ch)";
					}
					else if ($sessions->Media['audioCodec'] == "ac3") {
						$audioCodec = $plexServer->lang()->activity->directStream." (AC3) (".$sessions->TranscodeSession['audioChannels']."ch)";
					}
					else {
						$audioCodec = $plexServer->lang()->activity->directStream." (".$sessions->TranscodeSession['audioCodec'].") (".$sessions->TranscodeSession['audioChannels']."ch)";
					}
					
				}
				if(array_key_exists('TranscodeSession',$sessions)) {
					$streamType = $plexServer->lang()->activity->transcoding;
					if($sessions->TranscodeSession['videoDecision'] == "transcode") {
						$videoCodec = $plexServer->lang()->activity->transcode." (".$sessions->TranscodeSession['videoCodec'].") (".$sessions->TranscodeSession['width']."x".$sessions->TranscodeSession['height']."P)";
						$audioCodec = $sessions->TranscodeSession['audioDecision']." (".$sessions->TranscodeSession['audioCodec'].")";
					}
					if($sessions->TranscodeSession['videoDecision'] == "copy") {
						$videoCodec = $plexServer->lang()->activity->directStream." (".$sessions->TranscodeSession['videoCodec'].") (".$sessions->TranscodeSession['width']."x".$sessions->TranscodeSession['height']."P)";
						$audioCodec = $sessions->TranscodeSession['audioDecision']." (".$sessions->TranscodeSession['audioCodec'].")";
						
					}
				}
				
				if ($option == "xml") {
					$string_streamType = $plexServer->lang()->activity->stream.": ".$streamType;
					$string_videoCodec = $plexServer->lang()->activity->video.": ".$videoCodec;
					$string_audioCodec = $plexServer->lang()->activity->audio.": ".$audioCodec;

					$array[$iTotal]["colums"] = $xmlCl;
					$array[$iTotal]["url"] = $url;
					$array[$iTotal]["img"] = $img;
					$array[$iTotal]["title"] = $title;
					$array[$iTotal]["user"] = $user;
					$array[$iTotal]["playStatus"] = $playStatus;
					$array[$iTotal]["platformImage"] = $platformImage;
					$array[$iTotal]["streamID"] = $iTotal;
					$array[$iTotal]["bar"] = $progressBar;
					$array[$iTotal]["percent"] = $percentComplete;
					$array[$iTotal]["streamType"] = $string_streamType;
					$array[$iTotal]["videoCodec"] = $string_videoCodec;
					$array[$iTotal]["audioCodec"] = $string_audioCodec;
					
					$output = $array;
									
				}
				
				$iTotal++;
			}
		}
		return $output;
	}

	
}
$activity = new activity;

if(isset($_GET['xml'])) {
	if($_GET['xml'] == "sessions") {
		header('Content-Type: application/xml');
		echo "<sessions>";
		foreach ($activity->sessionProgressView("xml") as $val) {
			echo "<session>
					<alertBar>";
					if($plexServer->sessions("count") == 1) {
					  echo  "<string1>".$plexServer->lang()->index->userCountPre1User."</string1>";
				  	echo  "<string2>".$plexServer->lang()->index->userCount1User."</string2>";
					}
					else {
					  echo  "<string1>".$plexServer->lang()->index->userCountPreUsers."</string1>";
					  echo  "<string2>".$plexServer->lang()->index->userCountUsers."</string2>";
					}
		echo	"<count>".$plexServer->sessions("count")."</count>
					</alertBar>
					<colums>".$val['colums']."</colums>
					<url>".$val['url']."</url>
					<img>".$val['img']."</img>
					<title>".$val['title']."</title>
					<user>".$val['user']."</user>
					<playStatus>".$val['playStatus']."</playStatus>
					<platformImage>".$val['platformImage']."</platformImage>
					<streamID>".$val['streamID']."</streamID>
					<bar>".$val['bar']."</bar>
					<percent>".$val['percent']."</percent>
					<streamType>".$val['streamType']."</streamType>
					<videoCodec>".$val['videoCodec']."</videoCodec>
					<audioCodec>".$val['audioCodec']."</audioCodec>
				</session>";
		}
		echo "</sessions>";
	}
	if($_GET['xml'] == "test") {
		echo "<pre>";
		print_r($activity->sessionProgressView("xml"));
		echo "</pre>";
	}
}
else {
	echo $activity->sessionProgressView();
}