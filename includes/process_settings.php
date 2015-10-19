<?php
    session_start();

        $dateFormat = "\$plexWatch['dateFormat'] = '".$_POST['dateFormat']."';"; 
        $timeFormat = "\$plexWatch['timeFormat'] = '".$_POST['timeFormat']."';";
		$language	= "\$plexWatch['lang'] = '".$_POST['language']."';";
		$theme	= "\$plexWatch['theme'] = '".$_POST['theme']."';";
        
        $pmsIp = "\$plexWatch['pmsIp'] = '".$_POST['pmsIp']."';";        
        $pmsHttpPort = "\$plexWatch['pmsHttpPort'] = '".$_POST['pmsHttpPort']."';";
        $pmsHttpsPort = "\$plexWatch['pmsHttpsPort'] = '".$_POST['pmsHttpsPort']."';";
		
		if (!isset($_POST['enableAuth'])) {
			$enableAuth = "\$plexWatch['enableAuth'] = 'no';";
		}
		else if ($_POST['enableAuth'] == "yes") {
			$enableAuth = "\$plexWatch['enableAuth'] = '".$_POST['enableAuth']."';";
		}
		$statsUser = "\$plexWatch['username'] = '".$_POST['plexStatsUser']."';";
		if ($_POST['enableAuth'] == "yes") {
			if(!isset($_POST['plexStatsPass']) OR empty($_POST['plexStatsPass'])) {
				$statsPass = "\$plexWatch['password'] = '".$plexWatch['password']."';";
			}
			else {
				if(password_verify($_POST['plexStatsPass'], $plexWatch['password'])) {
					$statsPass = "\$plexWatch['password'] = '".$plexWatch['password']."';";
				}
				else {
					$statsPass = "\$plexWatch['password'] = '".password_hash($_POST['plexStatsPass'], PASSWORD_DEFAULT, ['cost' => 12])."';";
				}
			}
		}
		
        if (!isset($_POST['https'])) {
                $https = "\$plexWatch['https'] = 'no';";
        }else if ($_POST['https'] == "yes") {
                $https = "\$plexWatch['https'] = '".$_POST['https']."';";
        }
        
        $plexWatchDb = "\$plexWatch['plexWatchDb'] = '".$_POST['plexWatchDb']."';";
        
        $myPlexUser = "\$plexWatch['myPlexUser'] = '".$_POST['myPlexUser']."';";        
        $myPlexPass = "\$plexWatch['myPlexPass'] = '".$_POST['myPlexPass']."';"; 
        
        
        if (!isset($_POST['globalHistoryGrouping'])) {
                $globalHistoryGrping = "\$plexWatch['globalHistoryGrouping'] = 'no';";
        }else if ($_POST['globalHistoryGrouping'] == "yes") {
                $globalHistoryGrping = "\$plexWatch['globalHistoryGrouping'] = 'yes';";
        }
        
        
        if (!isset($_POST['userHistoryGrouping'])){
                $userHistoryGrping = "\$plexWatch['userHistoryGrouping'] = 'no';";
        }else if ($_POST['userHistoryGrouping'] == "yes") {
                $userHistoryGrping = "\$plexWatch['userHistoryGrouping'] = 'yes';";
        }
        
        
        if (!isset($_POST['chartsGrouping'])){
                $chartsGrping = "\$plexWatch['chartsGrouping'] = 'no';";
        }else if ($_POST['chartsGrouping'] == "yes") {
                $chartsGrping = "\$plexWatch['chartsGrouping'] = 'yes';";
        }
        
        //combine all data into one variable
        $data = "$dateFormat\r$timeFormat\r$enableAuth\r$statsUser\r$statsPass\r$language\r$theme\r$pmsIp\r$pmsHttpPort\r$pmsHttpsPort\r$https\r$plexWatchDb\r$myPlexUser\r$myPlexPass\r$globalHistoryGrping\r$userHistoryGrping\r$chartsGrping";
        
        $file = "../config/config.php";
        $func_file = dirname(dirname(__FILE__)) . '/includes/functions.php';
        
        //write data to config.php file
        $fp = fopen($file, "w+") or die("Cannot open file $file.");
        fwrite($fp, "<?php\r\r") or die("Cannot write to file $file.");
        fwrite($fp, "\nrequire_once '$func_file';\n") or die("Cannot write to file $file.");
        fwrite($fp, $data) or die("Cannot write to file $file.");
        fwrite($fp, "\r\r?>") or die("Cannot write to file $file.");
        fclose($fp);
        
        sleep(1);
        
        //grab myPlex authentication token
        require_once(dirname(__FILE__) . '/myplex.php');
        $myPlexToken = "\$plexWatch['myPlexAuthToken'] = '".$myPlexAuthToken."';";
        
        //include authentication code in saved data
        $data = "$dateFormat\r$timeFormat\r$enableAuth\r$statsUser\r$statsPass\r$language\r$theme\r$pmsIp\r$pmsHttpPort\r$pmsHttpsPort\r$https\r$plexWatchDb\r$myPlexUser\r$myPlexPass\r$myPlexToken\r$globalHistoryGrping\r$userHistoryGrping\r$chartsGrping";
        
        //rewrite data to config.php
        $fp = fopen($file, "w+") or die("Cannot open file $file.");
        fwrite($fp, "<?php\r\r") or die("Cannot write to file $file.");
        fwrite($fp, $data) or die("Cannot write to file $file.");
        fwrite($fp, "\r\r?>") or die("Cannot write to file $file.");
        fclose($fp);
		
		if(isset($_POST['refferal'])) {
			if($_POST['refferal'] == "setup.php") {
				header("Location: ../setup.php?page=4&s=".urlencode($plexServer->lang()->settings->saved)); exit;
			}
		}
		else {
		
			// check if an error was found - if there was, send the user back to the form  
			if (!empty($errorCode)) {  
					header('Location: ../settings.php?e='.urlencode($errorCode)); exit;
			} 
			
			// send the user back to the form  
			header("Location: ../settings.php?s=".urlencode($plexServer->lang()->settings->saved)); exit;
		}
        
?>