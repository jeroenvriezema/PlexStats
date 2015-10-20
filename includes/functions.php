<?php

//For testing purposes
ini_set("display_errors", false);
error_reporting( E_ALL );


if (file_exists(dirname(__FILE__) . '/../config/config.php')) {
	require_once(dirname(__FILE__) . '/../config/config.php');
}
else {
	die("File config.php not found.");
}

if(basename($_SERVER['PHP_SELF']) != "setup.php") {
	if(empty($plexWatch['plexWatchDb'])) {
		echo '<meta http-equiv="refresh" content="0; url=setup.php">';
	}
}

class general {
	function requireFile($file) {
		if (file_exists($file)) {
			require_once(dirname(__FILE__) . $file);
		}
		else {
			die ("File ".$file." not found!");
		}
	}
}

$general = new general;
//Functions
class plexServer {
	
	function userLoggedIn() {
		if(isset($_COOKIE['auth'])) {
			return true;
		}
		else {
			return false;
		}
	}
	
	function auth($page = "index.php?") {
		$error = null;
		global $plexWatch;
		if(isset($plexWatch['enableAuth'])) {
			if($plexWatch['enableAuth'] == 'yes') {
				if(!isset($_COOKIE['auth'])) {
					echo "<div class='login forced'>";
					echo "<div class='login-form'>";
					echo "<p class='help-block'>".$this->lang()->user->loginHeader."</p>";
					echo "<div class='login-inner'>";
					echo "<form name='login' class='form-horizontal' role='form' method='POST'>";
					echo "<div class='form-group text-input username-input'>";
					echo "<label class='control-label col-sm-5 text' for='username'>".$this->lang()->user->username."</label>";
					echo "<div class='col-sm-7 username'>";
					echo "<input type='text' name='username' class='username' id='username' placeholder='".$this->lang()->user->usernamePlaceholder."' value='".$_POST['username']."' required></input>";
					echo "</div></div><div class='form-group text-input password-input'>";
					echo "<label class='control-label col-sm-5 text' for='password'>".$this->lang()->user->password."</label>";
					echo "<div class='col-sm-7 password'>";
					echo "<input type='password' class='password' name='password' id='password' placeholder='".$this->lang()->user->passwordPlaceholder."' value='".$_POST['password']."' required>";
					echo "</div></div><div class='form-group'><div class='col-sm-offset-5 col-sm-7'><div class='checkbox'>";
					echo "<label class='remember' for='remember'><input type='checkbox' name='remember' value='yes'>".$this->lang()->user->rememberMe."</label>";
					echo "</div></div></div><div class='form-group'><div class='col-sm-offset-5 col-sm-7 login-btn'>";
					echo "<button type='submit' class='btn btn-default' name='login-form'>".$this->lang()->user->logIn."</button>";
					echo "</div></div></form>";
					echo "</div></div></div>";
				}
				else if(isset($_COOKIE['auth'])) {
					if(isset($plexWatch['password'])) {
						if($plexWatch['password'] != $_COOKIE['auth']) {
							setcookie("auth", '', time() - 3600, '/');
							setcookie("user", '', time() - 3600, '/');
						}
					}
				}
			}
		}
		if(isset($_POST['login-form'])) {
			if(isset($plexWatch['username'])) {
				if($plexWatch['username'] == $_POST['username']) {
					if(isset($plexWatch['password'])) {
						if(password_verify($_POST['password'], $plexWatch['password'])) {
							if(isset($_POST['remeber'])) {
								if($_POST['remember'] == 'yes') {
									setcookie("auth", $plexWatch['password'], time() + (10 * 365 * 24 * 60 * 60), '/');
									setcookie("user", $plexWatch['username'], time() + (10 * 365 * 24 * 60 * 60), '/');
									echo '<meta http-equiv="refresh" content="0; url='.$page.'succes='.$this->lang()->user->loginSucces.'" />';
								}
								else {
									setcookie("auth", $plexWatch['password'], 0, '/');
									setcookie("user", $plexWatch['username'], 0, '/');
									echo '<meta http-equiv="refresh" content="0; url='.$page.'success='.$this->lang()->user->loginSucces.'" />';
								}
							}
							else {
								setcookie("auth", $plexWatch['password'], 0, '/');
								setcookie("user", $plexWatch['username'], 0, '/');
								echo '<meta http-equiv="refresh" content="0; url='.$page.'success='.$this->lang()->user->loginSucces.'" />';
							}
						}
						else {
							echo "<style>.login-inner div.password::after {content: '".$this->lang()->user->passwordError."'; color: red; display: block; text-align: center; margin-top: 20px;} .login-inner input.password{color: red!important;}</style>";
						}
					}
				}
				else {
					echo "<style>.login-inner div.password::after {content: '".$this->lang()->user->usernameError."'; color: red; display: block; text-align: center; margin-top: 20px;} .login-inner input.username{color: red!important;}</style>";
				}
			}
		}
	}
	
	function navUser() {
		global $plexWatch;
		if(isset($plexWatch['enableAuth'])) {
			if($plexWatch['enableAuth'] == 'yes') {
				if(isset($_COOKIE['auth'])) {
					echo '<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> '.$plexWatch['username'].'<span class="caret"></span> </a>
					  <ul class="dropdown-menu">
						<li><a href="logout.php"><i class="fa fa-sign-out"></i> '.$this->lang()->user->logOut.'</a></li>
					  </ul>
					</li>
				  </ul>';
				}
			}
		}
	}
	
	function alert($message, $type = "info", $class = null) {
		if(!empty($message)) {
			$output = '';
			$output .= '<div class="alert alert-'.$type.' '.$class.'">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						'.$message.'
						</div>';
		}
		return $output;
	}
	
	function plexURL($option = "default") {
		global $plexWatch;
		if($option == "default" or $option == "port") {
			if ($plexWatch['https'] == 'yes') {
				$plexWatchPmsUrl = "https://".$plexWatch['pmsIp'].":".$plexWatch['pmsHttpsPort'];
				$port = $plexWatch['pmsHttpsPort'];
			}if ($plexWatch['https'] == 'no') {
				$plexWatchPmsUrl = "http://".$plexWatch['pmsIp'].":".$plexWatch['pmsHttpPort'];
				$port = $plexWatch['pmsHttpPort'];
			}
		}
		if($option == "default") {
			return $plexWatchPmsUrl;
		}
		if($option == "port") {
			return $port;
		}
		if($option == "httpPort") {
			return $plexWatch['pmsHttpPort'];
		}
		if($option == "httpsPort") {
			return $plexWatch['pmsHttpsPort'];
		}
	}
	
	function themeLoad() {
		global $plexWatch;
		$themeFolder = dirname(__FILE__) . '/../themes/';
		$themeLoc = 'themes/'.$plexWatch['theme'].'.theme.css';
		if(isset($plexWatch['theme'])) {
			if(file_exists($themeFolder.$plexWatch['theme'].'.theme.css')) {
				return $themeLoc;
			}
		}
	}
	
	function loadPwConfig() {
  
		/* if (isset($_SESSION['pwc'])) {   unset($_SESSION['pwc']); } // for testing */
		if (!isset($_SESSION['pwc'])) {
			global $plexWatch;
			$db = $this->dbconnect();
			if ($result = $db->querySingle("SELECT json_pretty from config")) {
				if ($json = json_decode($result)) {
					$_SESSION['pwc'] = $this->keysToLower($json);
				}
			}
		}
		if (isset($_SESSION['pwc'])) {
			return $_SESSION['pwc'];
		}
	}

/* return friends name based on user/platform */
	function FriendlyName($user,$platform = NULL) {
		$user = strtolower($user);
		$platform = strtolower($platform);

		$config = $this->loadPwConfig();
		if (is_object($config)) {
			$fn = $config->{'user_display'};
			if (is_object($fn)) {
				if (isset($fn->{$user.'+'.$platform})) {
					//print "user+platform match";
					return $fn->{$user.'+'.$platform};
				}
				else if (isset($fn->{$user})) {
					//print "user match";
					return $fn->{$user};
				}
			}
		}
		return $user;
	}
	
	function plexAuthToken() {
		global $plexWatch;
		if (!empty($plexWatch['myPlexAuthToken'])) {
			$myPlexAuthToken = $plexWatch['myPlexAuthToken'];
		}
		else {
			$myPlexAuthToken = '';
		}
		return $myPlexAuthToken;
	}
	
	function statusSessions() {
		$fileContents = '';
		if ($fileContents = file_get_contents($this->plexURL()."/status/sessions?X-Plex-Token=".$this->plexAuthToken())) {
			$statusSessions = simplexml_load_string($fileContents);
		}
		return $statusSessions;
	}
	
	function sections() {
		if(!empty($this->plexAuthToken())) {
			$sections = simplexml_load_file($this->plexURL()."/library/sections?X-Plex-Token=".$this->plexAuthToken());
		}
		else {
			$sections = simplexml_load_file($this->plexURL()."/library/sections");
		}
		return $sections;
	}
	
	function sectionCount($countSection = "all") {
		//NOT FINISHED YET!
		$sectionCount = array();
		$testArr = array();
		$i = 0;
		$count = 0;
		foreach($this->sections()->children() as $section) {
			$sectionCount[$i]['type'] = $section['type'];
			$sectionCount[$i]['name'] = $section['title'];
			if(!empty($this->plexAuthToken())) {
					$tokenURL = "&X-Plex-Token=".$this->plexAuthToken();
			}
			else {
				$tokenURL = '';
			}
			if($countSection == "all") {
				if($section['type'] == "movie") {
					$sectionDetails = simplexml_load_file("".$this->plexURL()."/library/sections/".$section['key']."/all?type=1&sort=addedAt:desc&X-Plex-Container-Start=0&X-Plex-Container-Size=1".$tokenURL);
					$sectionCount[$i]['size'] = $sectionDetails['totalSize'];
				}
				if($section['type'] == "show") {
					$sectionDetails = simplexml_load_file($this->plexURL()."/library/sections/".$section['key']."/all?type=2&sort=addedAt:desc&X-Plex-Container-Start=0&X-Plex-Container-Size=1".$tokenURL);
					$tvEpisodeCount = simplexml_load_file($this->plexURL()."/library/sections/".$section['key']."/all?type=4&X-Plex-Container-Start=0&X-Plex-Container-Size=1".$tokenURL);
					$sectionCount[$i]['shows'] = $sectionDetails['totalSize'];
					$sectionCount[$i]['episodes'] = $tvEpisodeCount['totalSize'];
				}
			}
			if($countSection == "allFiles") {
				if($section['type'] == "movie") {
					$sectionDetails = simplexml_load_file("".$this->plexURL()."/library/sections/".$section['key']."/all?type=1&sort=addedAt:desc&X-Plex-Container-Start=0&X-Plex-Container-Size=1".$tokenURL);
					$count += $sectionDetails['totalSize'];
				}
				if($section['type'] == "show") {
					$tvEpisodeCount = simplexml_load_file($this->plexURL()."/library/sections/".$section['key']."/all?type=4&X-Plex-Container-Start=0&X-Plex-Container-Size=1".$tokenURL);
					$count += $tvEpisodeCount['totalSize'];
				}
			}
			if($countSection == "test") {
				if($section['type'] == "show") {
					$sectionDetails = simplexml_load_file("".$this->plexURL()."/library/sections/".$section['key']."/all?type=2&sort=addedAt:desc&X-Plex-Container-Start=0".$tokenURL);
					$ii = 0;
					foreach($sectionDetails as $row) {
						
						$sectionDetails1 = simplexml_load_file($this->plexURL().$row['key']."/all?type=2&sort=addedAt:desc".$tokenURL);
						$testArr[$ii]['size'] = $sectionDetails1['size'];
						$testArr[$ii]['title'] = $sectionDetails1['parentTitle'];
						$ii++;
					}
				}
			}
			$i++;
		}
		if($countSection == "all") {
			return $sectionCount;
		}
		if($countSection == "allFiles") {
			return $count;
		}
		if($countSection == "test") {
			$z = 0;
			$percentArr = array();
			foreach($testArr as $row=>$key) {
				$totalSize += $key['size'];
			}
			foreach($testArr as $row=>$key) {
				$math1 = $key['size'] / $totalSize;
				$math2 = round($math1 * 100);
				$math3 = number_format($math2, 2);
				$percentArr[$z]['name'] = $key['title'];
				$percentArr[$z]['percent'] = $math3;
				$z++;
				
				
			}
			
			return $percentArr;
		}
	}
	
	function dbconnect() {
		global $plexWatch;

		if(!class_exists('SQLite3'))
		die("<div class=\"alert alert-warning \">php5-sqlite is not installed. Please install this requirement and restart your webserver before continuing.</div>");

		$db = new SQLite3($plexWatch['plexWatchDb']);
		$db->busyTimeout(10*1000);
		return $db;
	}
	
	function userCount() {
		date_default_timezone_set(@date_default_timezone_get());
		$db = $this->dbconnect();
		$users = $db->querySingle("SELECT count(DISTINCT user) as users FROM processed") or die ("Failed to access plexWatch database. Please check your settings.");
		return $users;
	}
	
	function historyGrouping($return = "default") {
		global $plexWatch;
		$db = $this->dbconnect();
		if($plexWatch['globalHistoryGrouping'] == "yes") {
			$plexWatchDbTable = "grouped";
			$numRows = $db->querySingle("SELECT COUNT(*) as count FROM ".$plexWatchDbTable."");
			$results = $db->query("SELECT title, user, platform, time, stopped, ip_address, xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date FROM processed WHERE stopped IS NULL UNION ALL SELECT title, user, platform, time, stopped, ip_address, xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date FROM ".$plexWatchDbTable." ORDER BY time DESC");
		}
		if ($plexWatch['globalHistoryGrouping'] == "no") {
			$plexWatchDbTable = "processed";
			$numRows = $db->querySingle("SELECT COUNT(*) as count FROM ".$plexWatchDbTable."");
			$results = $db->query("SELECT title, user, platform, time, stopped, ip_address, xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date FROM ".$plexWatchDbTable." ORDER BY time DESC");
		}
		
		if ($plexWatch['userHistoryGrouping'] == "yes") {
		  $plexWatchDbTableUser = "grouped";
	  }
	  if ($plexWatch['userHistoryGrouping'] == "no") {
		  $plexWatchDbTableUser = "processed";
	  }
		if($return == "default") {
			return $plexWatch['globalHistoryGrouping'];
		}
		if($return == "countHistory") {
			return $numRows;
		}
		if($return == "results") {
			return $results;
		}
		
		if($return == "dbTable") {
			return $plexWatchDbTable;
		}
		if($return == "user") {
		  return $plexWatch['userHistoryGrouping'];
		}
		if($return == "dbTableUser") {
		  return $plexWatchDbTableUser;
		}
	}
	
	function sessions($option = "default") {
		$fileContents = '';
		if(!empty($this->plexAuthToken())) {
			$plexToken = "?X-Plex-Token=".$this->plexAuthToken();
			if ($fileContents = file_get_contents($this->plexURL()."/status/sessions".$plexToken)) {
				$statusSessions = simplexml_load_string($fileContents);
			}
		}
		else {
			$plexAuthToken = '';
			if ($fileContents = file_get_contents($this->plexURL()."/status/sessions")) {
				$statusSessions = simplexml_load_string($fileContents);
			}
		}
		if($option == "default") {
			return $statusSessions;
		}
		if($option == "count") {
			return $statusSessions['size'];
		}
	}
	
	function arrays($option = null) {
		if($option == "platformImage") {
			$platform = array();
			$path = "images/platforms/";
			$platform['Roku'] = $path."roku.png";
			$platform['Apple TV'] = $path."appletv.png";
			$platform['Firefox'] = $path."firefox.png";
			$platform['Chromecast'] = $path."chromecast.png";
			$platform['Chrome'] = $path."chrome.png";
			$platform['Android'] = $path."android.png";
			$platform['Nexus'] = $path."android.png";
			$platform['iPad'] = $path."ios.png";
			$platform['iPhone'] = $path."ios.png";
			$platform['iOS'] = $path."ios.png";
			$platform['Plex Home Theater'] = $path."pht.png";
			$platform['Linux/RPi-XBMC'] = $path."xbmc.png";
			$platform['Safari'] = $path."safari.png";
			$platform['Internet Explorer'] = $path."ie.png";
			$platform['Unknown Browser'] = $path."default.png";
			$platform['Windows-XBMC'] = $path."xbmc.png";
			
			return $platform;
		}
	}
	
	function TimeAgo($datefrom,$dateto=-1) {
		// Defaults and assume if 0 is passed in that
		// its an error rather than the epoch

		if($datefrom<=0) { return "A long time ago"; }
		if($dateto==-1) { $dateto = time(); }

		// Calculate the difference in seconds betweeen
		// the two timestamps

		$difference = $dateto - $datefrom;

		// If difference is less than 60 seconds,
		// seconds is a good interval of choice

		if($difference < 60) {
			$interval = "s";
		}

		// If difference is between 60 seconds and
		// 60 minutes, minutes is a good interval
		elseif($difference >= 60 && $difference<60*60) {
			$interval = "n";
		}

		// If difference is between 1 hour and 24 hours
		// hours is a good interval
		elseif($difference >= 60*60 && $difference<60*60*24) {
			$interval = "h";
		}

		// If difference is between 1 day and 7 days
		// days is a good interval
		elseif($difference >= 60*60*24 && $difference<60*60*24*7){
			$interval = "d";
		}

		// If difference is between 1 week and 30 days
		// weeks is a good interval
		elseif($difference >= 60*60*24*7 && $difference <60*60*24*30) {
			$interval = "ww";
		}

		// If difference is between 30 days and 365 days
		// months is a good interval, again, the same thing
		// applies, if the 29th February happens to exist
		// between your 2 dates, the function will return
		// the 'incorrect' value for a day
		elseif($difference >= 60*60*24*30 && $difference <60*60*24*365) {
			$interval = "m";
		}

		// If difference is greater than or equal to 365
		// days, return year. This will be incorrect if
		// for example, you call the function on the 28th April
		// 2008 passing in 29th April 2007. It will return
		// 1 year ago when in actual fact (yawn!) not quite
		// a year has gone by
		elseif($difference >= 60*60*24*365) {
			$interval = "y";
		}

		// Based on the interval, determine the
		// number of units between the two dates
		// From this point on, you would be hard
		// pushed telling the difference between
		// this function and DateDiff. If the $datediff
		// returned is 1, be sure to return the singular
		// of the unit, e.g. 'day' rather 'days'

		switch($interval) {
			case "m":
			$months_difference = floor($difference / 60 / 60 / 24 /29);
			while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
				$months_difference++;
			}
			$datediff = $months_difference;

			// We need this in here because it is possible
			// to have an 'm' interval and a months
			// difference of 12 because we are using 29 days
			// in a month

			if($datediff==12) {
				$datediff--;
			}

			$res = ($datediff==1) ? "$datediff ".$this->lang()->index->monthAgo : "$datediff ".$this->lang()->index->monthsAgo;
			break;

			case "y":
			$datediff = floor($difference / 60 / 60 / 24 / 365);
			$res = ($datediff==1) ? "$datediff ".$string->index->yearAgo : "$datediff ".$this->lang()->index->yearsAgo;
			break;

			case "d":
			$datediff = floor($difference / 60 / 60 / 24);
			$res = ($datediff==1) ? "$datediff ".$this->lang()->index->dayAgo : "$datediff ".$this->lang()->index->daysAgo;
			break;

			case "ww":
			$datediff = floor($difference / 60 / 60 / 24 / 7);
			$res = ($datediff==1) ? "$datediff ".$this->lang()->index->weekAgo : "$datediff ".$this->lang()->index->weeksAgo;
			break;

			case "h":
			$datediff = floor($difference / 60 / 60);
			$res = ($datediff==1) ? "$datediff ".$this->lang()->index->hourAgo : "$datediff ".$this->lang()->index->hoursAgo;
			break;

			case "n":
			$datediff = floor($difference / 60);
			$res = ($datediff==1) ? "$datediff ".$this->lang()->index->minuteAgo :"$datediff ".$this->lang()->index->minutesAgo;
			break;

			case "s":
			$datediff = $difference;
			$res = ($datediff==1) ? "$datediff ".$this->lang()->index->secondAgo :"$datediff ".$this->lang()->index->secondsAgo;
			break;
		}
		return $res;
	}
	
	function history($option = "default", $user = null, $item = null) {
		global $strings;
		global $plexWatch;
		$db = $this->dbconnect();
		$plexWatchDbTable = $this->historyGrouping("dbTable");
		if($option == "default") {
  		if($this->historyGrouping() == "yes") {
  			$query = $db->query("SELECT title, user, platform, time, stopped, orig_title, orig_title_ep, episode, season, ip_address, xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date FROM processed WHERE stopped IS NULL UNION ALL SELECT title, user, platform, time, stopped, orig_title, orig_title_ep, episode, season, ip_address, xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date FROM $plexWatchDbTable ORDER BY time DESC");
  		}
  		else {
  			$query = $db->query("SELECT title, type, user, platform, time, stopped, orig_title, orig_title_ep, episode, season, ip_address, xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date FROM $plexWatchDbTable ORDER BY time DESC");
  		}
		}
		if($option == "user") {
			if($this->historyGrouping() == "yes") {
				$query = $db->query("SELECT title, user, platform, time, stopped, orig_title, orig_title_ep, episode, season, ip_address, xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date FROM processed WHERE stopped IS NULL UNION ALL SELECT title, user, platform, time, stopped, orig_title, orig_title_ep, episode, season, ip_address, xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date FROM $plexWatchDbTable WHERE user = '".$user."' ORDER BY time DESC");
			}
			else {
				$query = $db->query("SELECT title, type, user, platform, time, stopped, orig_title, orig_title_ep, episode, season, ip_address, xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date FROM $plexWatchDbTable WHERE user = '".$user."' ORDER BY time DESC");
			}
		}
		if($option == "itemEpisode") {
			$query = $db->query("SELECT title, user, platform, time, stopped, ip_address, xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date FROM $plexWatchDbTable WHERE session_id LIKE '%/metadata/".$item."\_%' ESCAPE '\' ORDER BY time DESC");
		}
		if($option == "itemMovie") {
			$query = $db->query("SELECT title, user, platform, time, stopped, ip_address, xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date FROM $plexWatchDbTable WHERE title = '".$item."' ORDER BY time DESC");
		}
		if($option == "itemSeason") {
			$query = $db->query("SELECT * FROM $plexWatchDbTable WHERE season ='".$item."' ORDER BY time DESC");
		}
		if($option == "itemShow") {
			$query = $db->query("SELECT title, user, platform, time, stopped, ip_address, xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date FROM $plexWatchDbTable WHERE orig_title = '".$item."'");
		}
		$numRows = count($query);
		if($numRows < 1) {
			$output = $this->lang()->general->noResults;
		}
		else {
			$rowCount = 0;
			if($option == "default") {
			    $output = '<table class="table table-striped" id="globalHistory" class="display">';
			}
			if($option == "user") {
			  $output = '<table class="table table-striped" id="userHistory" class="display">';
			}
			if($option == "itemMovie" OR $option == "itemEpisode" OR $option == "itemShow" OR $option == "itemSeason") {
				$output = '<table class="table table-striped" id="infoHistory" class="display">';
			}
			$output .=	'<thead>
							<tr>
								<th class="hidden-xs">'.$this->lang()->theads->date.'</th>';
								if($option != "user") {
								 $output .= '<th>'.$this->lang()->theads->user.'</th>';
								}
								$output .= '<th>'.$this->lang()->theads->title.'</th>
								<th class="hidden-xs hidden-sm">'.$this->lang()->theads->platform.'</th>
								<th class="hidden-xs hidden-sm">'.$this->lang()->theads->IPAddr.'</th>
								<th class="hidden-xs">'.$this->lang()->theads->started.'</th>
								<th class="hidden-xs hidden-sm">'.$this->lang()->theads->paused.'</th>
								<th class="hidden-xs">'.$this->lang()->theads->stopped.'</th>
								<th>'.$this->lang()->theads->completed.'</th>
							</tr>
						</thead>
						<tbody>';
			while ($row = $query->fetchArray()) {
				
				$xml = simplexml_load_string($row['xml']);
				if (empty($row['stopped'])) {
				    if($option == "user" && $user == $row['user'] OR $option == "default") {
				    	$date = $this->lang()->user->currentlyWatching;
				    }
				}
				else{
					if (empty($plexWatch['dateFormat'])) {
						$date = date('m/d/y',$row['time']);
					}
					else {
						$date = date($plexWatch['dateFormat'],$row['time']);
					}
				}
				$user = $this->FriendlyName($row['user'],$row['platform']);
				$userLink = "user.php?user=".$row['user'];
				$platform = $xml->Player['platform'];
				
				foreach ($this->arrays("platformImage") as $platform => $image) {
					if($xml->Player['platform'] == $platform) {
						$platformImage = $image;
						break;
					}
					else if(preg_match("/TV [a-z][a-z]\d\d[a-z]/i", $xml->Player['platform'])) {
						$platformImage = "images/platforms/samsung.png";
						break;
					}
					else if($xml->Player['platform'] == "Samsung") {
						$platformImage = "images/platforms/samsung.png";
						break;
					}
					else {
						$platformImage = "images/platforms/default.png";
					}
				}
				
				if ($platform == "Chromecast") {
					$str_platform = $platform;
				}else{
					$str_platform = $row['platform'];
				}
				
				if (empty($row['ip_address'])) {
					$ipaddr = $this->lang()->general->notAvailable;

				}
				else{
					$ipaddr = $row['ip_address'];
				}
				
				$request_url = $row['xml'];
				$xmlfield = simplexml_load_string($request_url);
				$ratingKey = $xmlfield['ratingKey'];
				$type = $xmlfield['type'];
				$duration = $xmlfield['duration'];
				$viewOffset = $xmlfield['viewOffset'];
				if (!array_key_exists('',$type)) {
					$url = "info.php?id=".$ratingKey;
				}
				else {
					$url = "info.php?id=".$ratingKey;
				}
				if($xml['type'] == "episode") {
					$title = $xml['grandparentTitle']."<br />".$xml['title'];
				}
				else {
					$title = $row['title'];
				}
				$started = date($plexWatch['timeFormat'],$row['time']);
				$paused_duration = round(abs($row['paused_counter']) / 60,1);
				$paused = $paused_duration." ".$this->lang()->general->time->mins;
				if (empty($row['stopped'])) {
					$stopped = $this->lang()->general->notAvailable;
				}
				else {
					$stopped = date($plexWatch['timeFormat'],$row['stopped']);
				}
				$to_time = strtotime(date("m/d/Y g:i a",$row['stopped']));
				$from_time = strtotime(date("m/d/Y g:i a",$row['time']));
				$paused_time = strtotime(date("m/d/Y g:i a",$row['paused_counter']));
				$viewed_time = round(abs($to_time - $from_time - $paused_time) / 60,0);
				$viewed_time_length = strlen($viewed_time);
				$percentComplete = ($duration == 0 ? 0 : sprintf("%2d", ($viewOffset / $duration) * 100));
				if ($percentComplete >= 90) {
					$percentComplete = 100;
				}
				
				if (empty($row['stopped'])) {
  				if($percentComplete >= 90) {
  					$percent = "progress-bar progress-bar-striped active progress-bar-success";
  				}
  				else if ($percentComplete <= 90 && $percentComplete >= 51) {
  					$percent = "progress-bar progress-bar-info active progress-bar-striped";
  				}
  				else if ($percentComplete <= 50 && $percentComplete >= 26) {
  					$percent = "progress-bar progress-bar-warning progress-bar-striped active";
  				}
  				else if ($percentComplete <= 25) {
  					$percent = "progress-bar progress-bar-danger progress-bar-striped active";
  				}
				}
				else {
				  if($percentComplete >= 90) {
  					$percent = "progress-bar progress-bar-success";
  				}
  				else if ($percentComplete <= 90 && $percentComplete >= 51) {
  					$percent = "progress-bar progress-bar-info";
  				}
  				else if ($percentComplete <= 50 && $percentComplete >= 26) {
  					$percent = "progress-bar progress-bar-warning";
  				}
  				else if ($percentComplete <= 25) {
  					$percent = "progress-bar progress-bar-danger";
  				}
				}
				$output .= "<tr>
								<td class='hidden-xs' data-order='".$row['date']."'>".$date."</td>";
								if($option != "user") {
								  $output .= "<td><a href=".$userLink.">".$user."</a></td>";
								}
								$output .= "<td class='title'><a href='".$url."'>".$title."</a></td>
								<td class='hidden-xs hidden-sm'><img class='platformImage history' src='".$platformImage."' alt='".$platform."'></img></td>
								<td class='hidden-xs hidden-sm hidden-md'>".$ipaddr."</td>
								<td class='hidden-xs'>".$started."</td>
								<td class='hidden-xs hidden-sm'>".$paused."</td>
								<td class='hidden-xs'>".$stopped."</td>
								<td><div class='progress'>
										<div class='".$percent."' role='progressbar'
											aria-valuemin='0' aria-valuemax='100' style='width:".$percentComplete."%'>
											".$percentComplete."%
										</div>
									</div>
								</td>
							</tr>";
				$rowCount++;
			}
			$output .= "</table>";
		}
		return $output;
	}
	
	function stats($option = null, $user = null) {
		global $strings;
		global $plexWatch;
		$output = '';
		$i = 0;
		$db = $this->dbconnect();
		$results = $this->historyGrouping("results");
		$plexWatchDbTable = $this->historyGrouping("dbTable");
		if($option == "hourlyPlayFinal") {
			$query = $db->query("SELECT strftime('%Y-%m-%d %H', datetime(time, 'unixepoch', 'localtime')) as date, COUNT(title) as count FROM $plexWatchDbTable WHERE datetime(time, 'unixepoch', 'localtime') >= datetime('now', '-24 hours', 'localtime') GROUP BY strftime('%Y-%m-%d %H', datetime(time, 'unixepoch', 'localtime')) ORDER BY date ASC;");
			$selector = "date";
		}
		if($option == "maxhourlyPlayFinal") {
			$query = $db->query("SELECT strftime('%Y-%m-%d %H', datetime(time, 'unixepoch', 'localtime')) as date, COUNT(title) as count FROM $plexWatchDbTable GROUP BY strftime('%Y-%m-%d %H', datetime(time, 'unixepoch', 'localtime')) ORDER BY count(*) desc limit 25;");
			$selector = "date";
		}
		if($option == "dailyPlayFinal") {
			$query = $db->query("SELECT date(time, 'unixepoch','localtime') as date, count(title) as count FROM $plexWatchDbTable GROUP BY date ORDER BY time DESC LIMIT 30");
			$selector = "date";
		}
		if($option == "monthlyPlayFinal") {
			$query = $db->query("SELECT strftime('%Y-%m', datetime(time, 'unixepoch', 'localtime')) as date, COUNT(title) as count FROM $plexWatchDbTable WHERE datetime(time, 'unixepoch', 'localtime') >= datetime('now', '-12 months', 'localtime') GROUP BY strftime('%Y-%m', datetime(time, 'unixepoch', 'localtime'))  ORDER BY date DESC LIMIT 6;");
			$selector = "date";
		}
		if($option == "userPlays") {
			$query = $db->query("SELECT user, strftime('%Y-%m', datetime(time, 'unixepoch', 'localtime')) as date, COUNT(user) as count FROM $plexWatchDbTable WHERE datetime(time, 'unixepoch', 'localtime') >= datetime('now', '-12 months', 'localtime') GROUP BY user  ORDER BY count DESC LIMIT 6;");
			$selector = "user";
		}
		if($option == "platPlays") {
			$query = $db->query("SELECT platform, COUNT(platform) as count FROM $plexWatchDbTable GROUP BY platform ORDER BY count DESC LIMIT 6;");
			//$query = $db->query("SELECT * FROM $plexWatchDbTable GROUP BY year LIMIT 6;");
			$selector = "platform";
		}
		if($option == "user") {
		  $plexWatchDbTable = $this->historyGrouping("dbTableUser");
		  $query = $db->query("SELECT strftime('%Y-%m-%d', datetime(time, 'unixepoch', 'localtime')) as date, COUNT(title) as count FROM $plexWatchDbTable WHERE user = '".$user."' AND datetime(time, 'unixepoch', 'localtime') >= datetime('now', '-1 months', 'localtime') GROUP BY date");
		  $selector = "date";
		}
		if($option == "platUser") {
		  $plexWatchDbTable = $this->historyGrouping("dbTableUser");
		  $query = $db->query("SELECT platform, strftime('%Y-%m-%d', datetime(time, 'unixepoch', 'localtime')) as date, COUNT(title) as count FROM $plexWatchDbTable WHERE user = '".$user."' AND datetime(time, 'unixepoch', 'localtime') >= datetime('now', '-1 months', 'localtime') GROUP BY platform");
		  $selector = "platform";
		}
		if($option == "test") {
		  $plexWatchDbTable = $this->historyGrouping("dbTableUser");
		  $query = $db->query("SELECT xml, strftime('%Y-%m-%d', datetime(time, 'unixepoch', 'localtime')) as date, COUNT(title) as count FROM processed WHERE user = '$user' GROUP BY date");
		}
		if($option == "userIP") {
		  $plexWatchDbTable = $this->historyGrouping("dbTableUser");
		  $query = $db->query("SELECT ip_address, COUNT(ip_address) as count FROM processed WHERE user = '$user' GROUP BY ip_address");
		  $selector = "ip_address";
		}
		if($option == "userHistoryEpisode" OR $option == "userHistoryMovie") {
		  $plexWatchDbTable = $this->historyGrouping("dbTableUser");
		  $query = $db->query("SELECT strftime('%Y-%m-%d', datetime(time, 'unixepoch', 'localtime')) as date, xml FROM $plexWatchDbTable WHERE user = '".$user."'  AND datetime(time, 'unixepoch', 'localtime') >= datetime('now', '-3 months', 'localtime')");
		}
		if($option == "test") {
		  $output = array();
		}
		$dateCount = array();
		while($row = $query->fetchArray()) {
			$i++;
			if($option == "test") {
				$output[$i] = $row;
			}
			if($option == "userHistoryEpisode") {
				$xml = simplexml_load_string($row['xml']);
				if($xml['type'] == "episode") {
					$dateCount[$i] = $row['date'];
					$type = $this->lang()->user->episodes;
			  }
			}
			if($option == "userHistoryMovie") {
				$xml = simplexml_load_string($row['xml']);
			  if($xml['type'] == "movie") {
				$dateCount[$i] = $row['date'];
				$type = $this->lang()->user->movies;
			  }
			}
			else {
				if($option == "platPlays") {
					if(preg_match("/Plex Web/i", $row[$selector])) {
						$rowOption[$i] = "Plex Web";
					}
					else if(preg_match("/TV [a-z][a-z]\d\d[a-z]/i", $row[$selector])) {
						$rowOption[$i] = "Samsung TV";
					}
					else {
						$rowOption[$i] = $row[$selector];
					}
				}
				else {
					$rowOption[$i] = $row[$selector];
				}
				if($option != "userHistoryMovie" AND $option != "userHistoryEpisode") {
					$count[$i] = $row['count'];
					$rowTotal = "{ \"x\": \"".$rowOption[$i]."\", \"y\": ".$count[$i]." }, ";
					$output .= $rowTotal;
				}
			}
		}
		$ii = 0;
		if($option == "userHistoryEpisode" OR $option == "userHistoryMovie") {
		  foreach(array_count_values($dateCount) as $key=>$val) {
		    $date[$ii] = $key;
		    $count[$ii] = $val;
  			$rowTotal = "{ \"x\": \"".$date[$ii]."\", \"y\": ".$count[$ii].", \"type\": \"".$type."\" }, ";
  			$output .= $rowTotal;
  			$ii++;
		  }
		}
		return $output;
	}
	
	function users() {
		global $strings;
		$db = $this->dbconnect();
		$plexWatchDbTable = $this->historyGrouping("dbTable");
		$users = $db->query("SELECT COUNT(title) as plays, user, time, SUM(time) as timeTotal, SUM(stopped) as stoppedTotal, SUM(paused_counter) as paused_counterTotal, platform, ip_address, xml FROM ".$plexWatchDbTable." GROUP BY user ORDER BY user COLLATE NOCASE");
		$output = "<table class='table table-striped display' id='usersTable'>
					<thead>
						<tr>
							<th class='hidden-xs'>avatar</th>
							<th>".$this->lang()->users->user."</th>
							<th>".$this->lang()->users->lastSeen."</th>
							<th class='hidden-xs'>".$this->lang()->users->lastIP."</th>
							<th>".$this->lang()->users->totalPlays."</th>
						</tr>
					</thead>
					<tbody>";
		while ($user = $users->fetchArray()) {
			$userXml = simplexml_load_string($user['xml']) ;
			if (empty($userXml->User['thumb'])) {
				$thumb = 'images/gravatar-default-80x80.png';
			}
			else {
				$thumb = $userXml->User['thumb'];
			}
			$username = $user['user'];
			$userLink = "user.php?user=".$user['user'];
			$lastSeenTime = $user['time'];
			$friendlyTime = $this->TimeAgo($lastSeenTime);
			$userIP = $user['ip_address'];
			$userPlays = $user['plays'];
				$output .= "<tr>
								<td class='hidden-xs'><a href='".$userLink."'><img class='avatar' src='".$thumb."' /></a></td>
								<td><a href='".$userLink."'>".$username."</a></td>
								<td>".$friendlyTime."</td>
								<td class='hidden-xs'>".$userIP."</td>
								<td>".$userPlays."</td>
							</tr>";
			}
			$output .= "</table>";
			return $output;
	}
	
	function userAvatar($user = null) {
		$db = $this->dbconnect();
		$plexWatchDbTable = $this->historyGrouping("dbTable");
		$user = $db->querySingle("SELECT xml FROM ".$plexWatchDbTable." WHERE user = '".$user."' LIMIT 1");
		$userXml = simplexml_load_string($user);
		if (empty($userXml->User['thumb'])) {
				$thumb = 'images/gravatar-default-80x80.png';
			}
		else {
			$thumb = $userXml->User['thumb'];
		}
		
		return $thumb;
		

	}
	
	function tableScript($id = null, $sort = "default") {
		global $strings;
		if($sort == "default") {
		  $sorting = '0, "desc"';
		}
		else {
		  $sorting = $sort;
		}
		$output = '';
		$output .= '<script>
					$(document).ready(function() {
						$("#'.$id.'").DataTable( {
							"pagingType": "full_numbers",
							"language": {
								"decimal": ",",
								"thousands": ".",
								"lengthMenu": "'.$this->lang()->pagination->itemsToShow.'",
								"search": "'.$this->lang()->pagination->searchBar.'",
								"zeroRecords": "'.$this->lang()->pagination->noData.'",
								"info": "'.$this->lang()->pagination->entryInfo.'",
								"infoEmpty": "'.$this->lang()->pagination->emptyEntries.'",
								"scrollX": false,
								"scrollY": false,
								"paginate": {
									"next": ">",
									"previous": "<",
									"last": ">>",
									"first": "<<",
								}
							},
							';
								  $output .= '"order": [[ '.$sorting.' ]],
							  	';
				$output .='} );
					} );
				</script>';
		return $output;
	}
	
	function top10($option = null, $selector = null) {
		global $strings;
		$db = $this->dbconnect();
		$plexWatchDbTable = $this->historyGrouping("dbTable");
		if($option == "all") {
			$query = $db->query("SELECT title,time,user,orig_title,orig_title_ep,episode,season,xml,datetime(time, 'unixepoch') AS time, COUNT(*) AS play_count FROM ".$plexWatchDbTable." GROUP BY title HAVING play_count > 0 ORDER BY play_count DESC,time DESC LIMIT 10");
			$options = array("all", "movies", "tvShows", "episodes");
		}
		else if($option == "movies") {
			$query = $db->query("SELECT title,time,user,orig_title,orig_title_ep,episode,season,xml,datetime(time, 'unixepoch') AS time, COUNT(*) AS play_count FROM ".$plexWatchDbTable." GROUP BY title HAVING play_count > 0 ORDER BY play_count DESC,time DESC");
			$options = array("movies");
		}
		else if($option == "tvShows") {
			$query = $db->query("SELECT title,time,user,orig_title,orig_title_ep,episode,season,xml,datetime(time, 'unixepoch') AS time, COUNT(orig_title) AS play_count FROM ".$plexWatchDbTable." GROUP BY orig_title HAVING play_count > 0 ORDER BY play_count DESC,time DESC");
			$options = array("tvShows", "episodes");
		}
		else if($option == "episodes") {
			$query = $db->query("SELECT title,time,user,orig_title,orig_title_ep,episode,season,xml,datetime(time, 'unixepoch') AS time, COUNT(*) AS play_count FROM ".$plexWatchDbTable." GROUP BY title HAVING play_count > 0 ORDER BY play_count DESC,time DESC");
			$options = array("tvShows", "episodes");
		}
		$num_rows = 1;
		$output = "";
		$i = 1;
		while ($top10 = $query->fetchArray()) {
			
			$xml = simplexml_load_string($top10['xml']);
			$position = $num_rows;
			
			if($selector == "topShows") {
				$link = "info.php?id=".$xml['grandparentRatingKey'];
			}
			else {
				$link = "info.php?id=".$xml['ratingKey'];
			}
			if(in_array("movies", $options)) {
				if($xml['type'] == "movie") {
					$xmlMovieThumbUrl = $this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:32400".$xml['thumb']."&width=600&height=640";
					$posterUrl = $this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:32400".$xml['art']."&width=1920&height=1080";
					$poster = "includes/img.php?img=".urlencode($posterUrl);
					$title = $top10['title'];
					$title2 = $xml['year'];
					$thumb = "includes/img.php?img=".urlencode($xmlMovieThumbUrl);
					if($top10['play_count'] <= 1) {
						$views = "(".$top10['play_count']." ".$this->lang()->general->view.")";
					}
					else {
						$views = "(".$top10['play_count']." ".$this->lang()->general->views.")";
					}
					if($i == 1) {
						$output .= "<div class='item active' style=\"background-image: url('".$poster."')\">";
					}
					else {
						$output .= "<div class='item' style=\"background-image: url('".$poster."')\">";
					}
					$output .= "<h6 class='charts-number'>".$num_rows."</h6>
									<a href='".$link."'>
										<img class='chart-thumb' src='".$thumb."'>
									</a>
									<div class='chart-info'>
									<a href='".$link."'><h4 class='chart-title'>".$title."</h4></a>
									<h5>".$title2."</h5>
									<h6>".$views."</h6>
									</div></div>";
					$num_rows++;
				}
			}
			
			if(in_array("episodes", $options)) {
				if($xml['type'] == "episode") {
					$xmlEpisodeThumbUrl = $this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:32400".$xml['grandparentThumb']."&width=600&height=649";
					$posterUrl = $this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:32400".$xml['art']."&width=1920&height=1080";
					if($option == "tvShows") {
						$title = $top10['orig_title'];
						$title2 = '';
					}
					else {
						$title = $top10['orig_title'];
						$title2 = $this->lang()->general->shows->season." ".$top10['season'].", ".$this->lang()->general->shows->episode." ".$top10['episode'];
					}
					$thumb = "includes/img.php?img=".urlencode($xmlEpisodeThumbUrl);
					$poster = "includes/img.php?img=".urlencode($posterUrl);
					if($top10['play_count'] <= 1) {
						$views = "(".$top10['play_count']." ".$this->lang()->general->view.")";
					}
					else {
						$views = "(".$top10['play_count']." ".$this->lang()->general->views.")";
					}
					if($option != "episodes") {
						if($i == 1) {
							$output .= "<div class='item active' style=\"background-image: url('".$poster."')\">";
						}
						else {
							$output .= "<div class='item' style=\"background-image: url('".$poster."')\">";
						}
					}
					else {
						if($num_rows == 1) {
							$output .= "<div class='item active' style=\"background-image: url('".$poster."')\">";
						}
						else {
							$output .= "<div class='item' style=\"background-image: url('".$poster."')\">";
						}
					}
					$output .= "<h6 class='charts-number'>".$num_rows."</h6>
									
									<a class='chart-thumb' href='".$link."'>
										<img src='".$thumb."'>
									</a>
									<div class='chart-info'>
									<a class='chart-title' href='".$link."'><h4>".$title."</h4></a>
									<h5>".$title2."</h5>
									<h6>".$views."</h6>
									</div></div>";
					$num_rows++;
				}
			}
			if($num_rows == 11) {
				break;
			}
		$i++;
		}
		return $output;
	}
	
	function langDisplay() {
		global $plexWatch;
		$dir = dirname(__FILE__)."/../langs/";
		$scan = array_diff(scandir($dir), array('..', '.'));
		$arr = array();
		$i = 1;
		if(!empty($scan)) {
			foreach($scan as $row) {
				$i ++;
				$langFile = $dir.$row;
				$lang = simplexml_load_file($langFile);
				$file_ext = pathinfo($row);
				if($file_ext["extension"] == "xml") {
					if(isset($plexWatch['lang'])) {
						if($plexWatch['lang'] == $lang->langinfo->code) {
							$arr["1"]["first"] = "1";
							$arr["1"]["code"] = $lang->langinfo->code;
							$arr["1"]["name"] = $lang->langinfo->loc_name;
						}
						else {
							$arr[$i]["first"] = "0";
							$arr[$i]["code"] = $lang->langinfo->code;
							$arr[$i]["name"] = $lang->langinfo->int_name;
						}
					}
					else {
						$arr[$i]["code"] = $lang->langinfo->code;
						$arr[$i]["name"] = $lang->langinfo->int_name;
					}
				}
			}
		}
		$output = '';
		if(isset($arr)) {
			ksort($arr);
			foreach($arr as $row) {
				$output .= "<option value='".$row['code']."'>".$row['name']."</option>";
			}
		}
		return $output;
	}
	
	function themeSelect() {
		global $plexWatch;
		$dir = dirname(__FILE__)."/../themes/";
		$scan = array_diff(scandir($dir), array('..', '.'));
		$arr = array();
		$i = 1;
		if(!empty($scan)) {
			foreach($scan as $row) {
				$i ++;
				$themeFile = $dir.$row;
				$file_ext = pathinfo($row);
				$file_final = $file_ext['filename'];
				$themeCheck = str_replace('.theme', '', $file_final);
				if($file_ext["extension"] == "css") {
					if(strpos($file_ext['filename'], '.theme')) {
						$lines = file($themeFile);
						$name = str_replace('Name: ', '', $lines[3]);
						if(isset($plexWatch['theme'])) {
							if($plexWatch['theme'] == $themeCheck) {
								$arr["1"]["first"] = "1";
								$arr["1"]["code"] = $themeCheck;
								$arr["1"]["name"] = $name;
							}
							else {
								$arr[$i]["first"] = "0";
								$arr[$i]["code"] = $themeCheck;
								$arr[$i]["name"] = $name;
							}
						}
						else {
							$arr[$i]["code"] = $themeCheck;
							$arr[$i]["name"] = $name;
						}
					}
				}
			}
		}
		$output = '';
		if(isset($arr)) {
			ksort($arr);
			foreach($arr as $row) {
				$output .= "<option value='".$row['code']."'>".$row['name']."</option>";
			}
		}
		return $output;
	}
	
	/* function to lowercase all object keys. easier for matching */
	function &keysToLower(&$obj){
	  $type = (int) is_object($obj) - (int) is_array($obj);
	  if ($type === 0) return $obj;
  	  foreach ($obj as $key => &$val) {
    		$element = $this->keysToLower($val);
    		switch ($type) {
      		case 1:
      		  if (!is_int($key) && $key !== ($keyLowercase = strtolower($key))) {
          		unset($obj->{$key});
          		$key = $keyLowercase;
      		  }
      		  $obj->{$key} = $element;
      		  break;
      		  case -1:
      		  if (!is_int($key) && $key !== ($keyLowercase = strtolower($key))) {
      		    unset($obj[$key]);
      		    $key = $keyLowercase;
  		      }
  		      $obj[$key] = $element;
  		      break;
  		}
	  }
	  return $obj;
	}
	
	function lang($option = null) {
		global $plexWatch;
		if(isset($option)) {
			$plexWatch['lang'] = $option;
		}
		else {
			if(!isset($plexWatch['lang'])) {
				$plexWatch['lang'] = "en_EN";
			}
			if(empty($plexWatch['lang'])) {
				$plexWatch['lang'] = "en_EN";
			}
		}
		$langXMLFile = dirname(__FILE__)."/../langs/".$plexWatch['lang'].".xml";
		if (file_exists($langXMLFile)) {
			$strings = simplexml_load_file($langXMLFile);
		}
		else {
			$strings = "ERROR!";
		}
		return $strings;
	}
	
	function userStats($option = null, $user = null, $type= null) {
	  $plexWatchDbTable = $this->historyGrouping("dbTableUser");
	  $db = $this->dbconnect();
	  global $plexWatch;
	  
	  if ($option == "daily") {
	    $q = $db->query("SELECT xml, time,stopped,paused_counter FROM ".$plexWatchDbTable." WHERE datetime(stopped, 'unixepoch', 'localtime') >= date('now', 'localtime') AND user='$user' ");
	  }
	  
	  if($option == "weekly") {
	    $q = $db->query("SELECT xml, time,stopped,paused_counter FROM ".$plexWatchDbTable." WHERE datetime(stopped, 'unixepoch', 'localtime') >= datetime('now', '-7 days', 'localtime') AND user='$user' ");
	  }
	  
	  if($option == "monthly") {
	    $q = $db->query("SELECT xml, time,stopped,paused_counter FROM ".$plexWatchDbTable." WHERE datetime(stopped, 'unixepoch', 'localtime') >= datetime('now', '-30 days', 'localtime') AND user='$user' ");
	  }
	  
	  if($option == "allTime") {
	    $q = $db->query("SELECT xml, time,stopped,paused_counter FROM ".$plexWatchDbTable." WHERE user='$user' ");
	  }
		
		$timeViewedTime = 0;
		$arr = array();
		$i = 0;
		if($type == "time") {
			while ($TimeRow = $q->fetchArray()) {
				$TimeToTimeRow = strtotime(date("m/d/Y G:i",$TimeRow['stopped']));
				$TimeFromTimeRow = strtotime(date("m/d/Y G:i",$TimeRow['time']));
				$TimePausedTimeRow = round(abs($TimeRow['paused_counter']) ,1);
				$TimeViewedTimeRow = round(abs($TimeToTimeRow - $TimeFromTimeRow - $TimePausedTimeRow) ,0);
				$TimeViewedTime += $TimeViewedTimeRow;
				
			}
		}
		else {
			while ($row = $q->fetchArray()) {
				$xml = simplexml_load_string($row['xml']);
				$xml_type = $xml['type'];
				if($xml['type'] == "movie") {
					$timeToTimeRow = strtotime(date("m/d/Y G:i",$row['stopped']));
					$timeFromTimeRow = strtotime(date("m/d/Y G:i",$row['time']));
					$timePausedTimeRow = round(abs($row['paused_counter']) ,1);
					$minutes = round(abs($timeToTimeRow - $timeFromTimeRow - $timePausedTimeRow) /60,2);
					$totalMVMins += $minutes;
				}
				if($xml['type'] == "episode") {
					$timeToTimeRow = strtotime(date("m/d/Y G:i",$row['stopped']));
					$timeFromTimeRow = strtotime(date("m/d/Y G:i",$row['time']));
					$timePausedTimeRow = round(abs($row['paused_counter']) ,1);
					$minutes = round(abs($timeToTimeRow - $timeFromTimeRow - $timePausedTimeRow) /60,2);
					$totalEPMins += $minutes;
				}
			}
		}
	if($type == "time") {
		$timeHours = intval($TimeViewedTime/ 3600);
		$timeMinutes = ($TimeViewedTime % 3600) /60;
		$arr['minutes'] = round($timeMinutes);
		$arr['hours'] = $timeHours;
	}
	else {
		$totalAmount = $totalEPMins + $totalMVMins;
		$countEP = $totalEPMins / $totalAmount;
		$countMV = $totalMVMins / $totalAmount;
		$countPercentEP = round($countEP * 100);
		$countPercentMV = round($countMV * 100);
		$percentEP = number_format($countPercentEP, 2);
		$percentMV = number_format($countPercentMV, 2);
		//echo $percentEP;
		if($percentEP == '0.00' AND $percentMV == '0.00') {
			$arr['empty'] = $option;
		}
		else {
			$arr['movie']['minutes'] = $percentMV;
			$arr['episode']['minutes'] = $percentEP;
		}
	}
  	return $arr;
	  
	}
	
	
	function platformStats($user = null) {
	  $plexWatchDbTable = $this->historyGrouping("dbTableUser");
    $db = $this->dbconnect();
	  $platformResults = $db->query ("SELECT xml,platform, COUNT(platform) as platform_count FROM ".$plexWatchDbTable." WHERE user = '$user' GROUP BY platform ORDER BY platform ASC") or die ("Failed to access plexWatch database. Please check your settings.");
	  $i = 0;
	  $output = array();
	  while ($platformResultsRow = $platformResults->fetchArray()) {
	    $platformXml = $platformResultsRow['xml'];
			$platformXmlField = simplexml_load_string($platformXml);
			
			if($platformXmlField->Player['platform'] == "Samsung") {
				$output[$i]['platform'] = "Samsung TV";
			}
			else {
				$output[$i]['platform'] = $platformXmlField->Player['platform'];
			}
			$output[$i]['count'] = $platformResultsRow['platform_count'];
			
  	  foreach ($this->arrays("platformImage") as $platform => $image) {
    		if($platformXmlField->Player['platform'] == $platform) {
    			$output[$i]['platformImage'] = $image;
    			break;
    		}
    		else if(preg_match("/TV [a-z][a-z]\d\d[a-z]/i", $platformXmlField->Player['platform'])) {
    		  $output[$i]['platformImage'] = "images/platforms/samsung.png";
			  break;
    		}
			else if($platformXmlField->Player['platform'] == "Samsung") {
				$output[$i]['platformImage'] = "images/platforms/samsung.png";
			}
    		else {
    			$output[$i]['platformImage'] = "images/platforms/default.png";
    		}
  	  }
  	  $i++;
	  }
	  return $output;
	}
	
	function userRecents($user = null, $option =  "default") {
	  global $plexWatch;
	  $plexWatchDbTable = $this->historyGrouping("dbTableUser");
	  $db = $this->dbconnect();
	  
	  if($option == "default") {
		$recentlyWatchedResults = $db->query("SELECT title, user, platform, time, stopped, ip_address, xml, paused_counter FROM ".$plexWatchDbTable." WHERE user = '$user' ORDER BY time DESC LIMIT 6");
	  }
	  if($option == "allUsers") {
		$recentlyWatchedResults = $db->query("SELECT user, strftime('%Y-%m-%d', datetime(time, 'unixepoch', 'localtime')) as date FROM ".$plexWatchDbTable." WHERE datetime(time, 'unixepoch', 'localtime') >= datetime('now', '-3 months', 'localtime') GROUP BY user ORDER BY user DESC");
	  }
		// Run through each feed item
		$output = array();
		$i = 0;
		if($option == "default") {}
		while ($recentlyWatchedRow = $recentlyWatchedResults->fetchArray()) {
  		$request_url = $recentlyWatchedRow['xml'];
  		$recentXml = simplexml_load_string($request_url) ;
		if($option == "default") {
			if ($recentXml['type'] == "episode") {
			  $recentMetadata = "".$this->plexURL()."/library/metadata/".$recentXml['ratingKey']."?X-Plex-Token=".$this->plexAuthToken();
			  
			  if ($recentThumbUrlRequest = @simplexml_load_file ($recentMetadata)) {
				$recentThumbUrl = "".$this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort']."".$recentThumbUrlRequest->Video['parentThumb']."&width=136&height=280";
				
				$output[$i]['thumbUrl'] = "info.php?id=" .$recentXml['ratingKey'];
				if($recentThumbUrlRequest->Video['parentThumb']) {
				  $output[$i]['thumb'] = "includes/img.php?img=".urlencode($recentThumbUrl);
				}
				else {
				  $output[$i]['thumb'] = "images/poster.png";
				}
				$parentIndexPadded = sprintf("%01s", $recentXml['parentIndex']);
						$indexPadded = sprintf("%02s", $recentXml['index']);
						$output[$i]['title'] = $recentXml['grandparentTitle'];
						$output[$i]['subtitle'] = $this->lang()->general->shows->season." ".$parentIndexPadded." - ".$this->lang()->general->shows->episode." ".$indexPadded;
			  }
			}
			  else if ($recentXml['type'] == "movie") {
				$recentMetadata = "".$this->plexURL()."/library/metadata/".$recentXml['ratingKey']."?X-Plex-Token=".$this->plexAuthToken();
				
				if ($recentThumbUrlRequest = @simplexml_load_file ($recentMetadata)) {
				  $recentThumbUrl = "".$this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort']."".$recentThumbUrlRequest->Video['thumb']."&width=136&height=280";
				  
				  $output[$i]['thumbUrl'] = "info.php?id=" .$recentXml['ratingKey'];
				  if($recentThumbUrlRequest->Video['thumb']) {
					$output[$i]['thumb'] = "includes/img.php?img=".urlencode($recentThumbUrl);
				  }
				  else {
					$output[$i]['thumb'] = 'images/poster.png';
				  }
				  $parentIndexPadded = sprintf("%01s", $recentXml['parentIndex']);
				  $indexPadded = sprintf("%02s", $recentXml['index']);
				  $output[$i]['title'] = $recentXml['title'];
				  $output[$i]['subtitle'] = $recentXml['year'];
				  
				}
			  }
			  else if ($recentXml['type'] == "clip") {
				$recentMetadata = "".$this->plexURL()."/library/metadata/".$recentXml['ratingKey']."?X-Plex-Token=".$this->plexAuthToken();
				
				if ($recentThumbUrlRequest = @simplexml_load_file ($recentMetadata)) {
				  $recentThumbUrl = "".$this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort']."".$recentThumbUrlRequest->Video['thumb']."&width=136&height=280";
				  $output[$i]['thumbUrl'] = $recentXml['ratingKey'];
				  $output[$i]['thumb'] = 'images/poster.png';
				  $parentIndexPadded = sprintf("%01s", $recentXml['parentIndex']);
						$indexPadded = sprintf("%02s", $recentXml['index']);
						$output[$i]['title'] = $recentXml['title']." (".$recentXml['year'];
				}
			  }
			  else {}
			}
		$i++;
		}
		if($option == "allUsers") {
			$z = 0;
			while($overview = $recentlyWatchedResults->fetchArray()) {
				$z++;
				$q = $db->query("SELECT user, strftime('%Y-%m-%d', datetime(time, 'unixepoch', 'localtime')) as date, COUNT(title) as count FROM $plexWatchDbTable WHERE user = '".$overview['user']."' AND datetime(time, 'unixepoch', 'localtime') >= datetime('now', '-3 months', 'localtime') GROUP BY strftime('%Y-%m-%d', datetime(time, 'unixepoch', 'localtime')) ORDER BY date ASC;");
				while($row = $q->fetchArray()) {
						$userHistoryUser = $row['user'];
						$userHistoryData .= "{ \"x\": \"".$row['date']."\", \"y\": ".$row['count'].", \"user\": \"".$row['user']."\"},";
				}
	$data .= '{ "className": ".'.$userHistoryUser.'",
				"data": [
				'.$userHistoryData.']
						},
						';
				$userHistoryData = null;
			}
		}
		if($option != "allUsers"){
			return $output;
		}
		else {
			return $data;
		}
	}
	
	function userIPAddr($user = null) {
	  global $plexWatch;
	  $plexWatchDbTable = $this->historyGrouping("dbTableUser");
	  $db = $this->dbconnect();
	  $userIpAddressesQuery = $db->query("SELECT time,ip_address,platform,xml, COUNT(ip_address) as play_count, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date FROM processed WHERE user = '$user' GROUP BY ip_address ORDER BY time DESC");
	  $output = array();
	  $i = 0;
	  while ($userIpAddresses = $userIpAddressesQuery->fetchArray()) {
  		if (!empty($userIpAddresses['ip_address'])) {
				$output[$i]['dataOrder']['date'] = $userIpAddresses['date'];
				$output[$i]['date'] = date($plexWatch['dateFormat'],$userIpAddresses['time']);
				$output[$i]['ipAddress'] = $userIpAddresses['ip_address'];
			  $output[$i]['playCount'] = $userIpAddresses['play_count'];
	      $output[$i]['platform'] = $userIpAddresses['platform'];
  		}
  		$i++;
	  }
	  return $output;
											
	}
	
	function user($user = null) {
	  global $plexWatch;
	  $plexWatchDbTable = $this->historyGrouping("dbTable");
	  $db = $this->dbconnect();
	  
	  $dailyCount = $db->querySingle("SELECT COUNT(*) FROM ".$plexWatchDbTable." WHERE datetime(stopped, 'unixepoch', 'localtime') >= date('now', 'localtime') AND user='$user' ");
	  if($dailyCount == 1) {
	    
	  }
	  
	  $username = $this->FriendlyName($user);
		
		$userInfo = $db->query("SELECT user,xml FROM ".$plexWatchDbTable." WHERE user = '$user' ORDER BY time DESC LIMIT 1");
		while ($userInfoResults= $userInfo->fetchArray()) {
			$userInfoXml = $userInfoResults['xml'];
			$userInfoXmlField = simplexml_load_string($userInfoXml);
			if (empty($userInfoXmlField->User['thumb'])) {
				$avatar = 'images/gravatar-default-80x80.png';
			}
			else{
				$avatar = $userInfoXmlField->User['thumb'];
			}
		}
		
		$output = '';
		$output .= "<ul class='nav nav-tabs'><li class='active'><a data-toggle='tab' href='#globStats' class='userGlobStats'>".$this->lang()->user->globalStats."</a></li>";
		$output .= "<li><a data-toggle='tab' href='#platStats' class='userPlatStats'>".$this->lang()->user->platformStats."</a></li>";
		$output .= "<li><a data-toggle='tab' href='#recentlyWatched' class='userHistory'>".$this->lang()->user->recentlyWatched."</a></li>";
		$output .= "<li class='hidden-xs'><a data-toggle='tab' href='#pubIPAddr' class='userIPAddr'>".$this->lang()->user->pubIPAddr."</a></li></ul>";
		$output .= "<div class='tab-content'><div id='globStats' class='tab-pane fade in active'><div class='row'><h2>".$this->lang()->user->globalStats."</h2>";
		$output .= "<div class='history-charts-header'><figure class='history-charts-instance-chart chart' id='userStats'></figure></div>";
		
		$output .= "<div class='col col-xs-6 col-md-3 col-sm-3 col-lg-3'><h3>".$this->lang()->user->today."</h3>";
		$output .= "<div class='totalTime'>";
		
		$output .= "<h5>".$this->userStats("daily", $user, "time")['hours']."<strong>".$this->lang()->user->hour."</strong></h5>";
		$output .= "<h5>".$this->userStats("daily", $user, "time")['minutes']."<strong>".$this->lang()->user->minutes."</strong></h5>";
		$output .= "</div>";
		$output .= '<div id="doughnutChartDaily" class="doughnut"></div>';
		$output .= "</div>";
		
		$output .= "<div class='col col-xs-6 col-md-3 col-sm-3 col-lg-3'><h3>".$this->lang()->user->lastWeek."</h3>";
		$output .= "<div class='totalTime'>";
		$output .= "<h5>".$this->userStats("weekly", $user, "time")['hours']."<strong>".$this->lang()->user->hour."</strong></h5>";
		$output .= "<h5>".$this->userStats("weekly", $user, "time")['minutes']."<strong>".$this->lang()->user->minutes."</strong></h5>";
		$output .= "</div>";
		$output .= '<div id="doughnutChartWeekly" class="doughnut"></div>';
		$output .= "</div>";
		
		$output .= "<div class='col col-xs-6 col-md-3 col-sm-3 col-lg-3'><h3>".$this->lang()->user->lastMonth."</h3>";
		$output .= "<div class='totalTime'>";
		$output .= "<h5>".$this->userStats("monthly", $user, "time")['hours']."<strong>".$this->lang()->user->hour."</strong></h5>";
		$output .= "<h5>".$this->userStats("monthly", $user, "time")['minutes']."<strong>".$this->lang()->user->minutes."</strong></h5>";
		$output .= "</div>";
		$output .= '<div id="doughnutChartMonthly" class="doughnut"></div>';
		$output .= "</div>";
		
		$output .= "<div class='col col-xs-6 col-md-3 col-sm-3 col-lg-3'><h3>".$this->lang()->user->allTime."</h3>";
		$output .= "<div class='totalTime'>";
		$output .= "<h5>".$this->userStats("allTime", $user, "time")['hours']."<strong>".$this->lang()->user->hour."</strong></h5>";
		$output .= "<h5>".$this->userStats("allTime", $user, "time")['minutes']."<strong>".$this->lang()->user->minutes."</strong></h5>";
		$output .= "</div>";
		$output .= '<div id="doughnutChartAllTime" class="doughnut"></div>';
		$output .= "</div></div>";
		$output .= "<div class='legend'><ul class='legend empty list-unstyled'><li class='color-empty'><div class='legend-color legend'></div>".$this->lang()->user->noResults."</li><li class='color1'><div class='legend-color legend'></div>".$this->lang()->user->movies."</li><li class='color2'><div class='legend-color legend'></div>".$this->lang()->user->episodes."</li></ul></div>";
		$output .= "</div>";
		
		$output .="<div id='platStats' class='tab-pane fade'>";
		$output .= "<h2>".$this->lang()->user->platformStats."</h2>";
		$output .= "<div class='history-charts-header'><figure class='history-charts-instance-chart chart' id='userPlat'></figure></div>";
		$output .= "<div class='row'>";
		foreach($this->platformStats($user) as $row) {
  		$output .= "<div class='col-xs-12 col-md-6 col-sm-6 col-lg-3'><div class='media'><div class='media-left'>";
  		$output .= "<img class='platformImage media-object' src='".$row['platformImage']."' />";
  		$output .= "</div><div class='media-body'>";
  		$output .= "<h4 class='media-heading'>".$row['platform']."</h4>";
  		$output .= "<p><strong>".$row['count']."</strong> ".$this->lang()->general->plays."</p></div></div></div>";
		}
		$output .= "</div></div>";
		
		$output .= "<div id='recentlyWatched' class='tab-pane fade'>";
		$output .= "<h2>".$this->lang()->user->recentlyWatched."</h2>";
		$output .= "<div class='history-charts-header'><figure class='history-charts-instance-chart chart' id='userHistoryChart'></figure></div>";
		$output .= "<div class='row'>";
		foreach($this->userRecents($user) as $row) {
  		$output .= "<div class='col-xs-6 col-md-6 col-sm-6 col-lg-4'><div class='media'><div class='media-left users'>";
  		$output .= "<a href='".$row['thumbUrl']."'><img class='platformImage media-object' src='".$row['thumb']."'></a>";
  		$output .= "</div><div class='media-body'>";
  		$output .= "<h4 class='media-heading recentWatches'>".$row['title']."</h4>";
  		$output .= "<p class='help-block'>".$row['subtitle']."</p>";
  		$output .= "</div></div></div>";
		}
		$output .= "</div><div class='row'>";
		$output .= $this->history("user", $user);
		$output .= "</div></div>";
		
		
		$output .= "<div id='pubIPAddr' class='tab-pane fade'>";
		$output .= "<h2>".$this->lang()->user->pubIPAddr."</h2>";
		$output .= "<div class='history-charts-header'><figure class='history-charts-instance-chart chart' id='userIP'></figure></div>";
		$output .= '<table class="table table-striped" id="userIPAddr" class="display">
						<thead>
							<tr>
								<th class="hidden-xs">'.$this->lang()->theads->date.'</th>
								<th>'.$this->lang()->user->IPAddr.'</th>
								<th>'.$this->lang()->user->playCount.'</th>
								<th class="hidden-xs">'.$this->lang()->user->platLS.'</th>
							</tr>
						</thead>
						<tbody>';
		foreach($this->userIPAddr($user) as $row) {
		  $output .= "<tr>";
		  $output .= "<td data-order='".$row['dataOrder']['date']."'>".$row['date']."</td>";
		  $output .= "<td>".$row['ipAddress']."</td>";
		  $output .= "<td>".$row['playCount']."</td>";
		  $output .= "<td>".$row['platform']."</td>";
		  $output .= "</tr>";
		}
		$output .= "</table>";
		$output .= "</div>";
		
		$output .= "</div>";
		
		$output .= "</div></div>";
		
		return $output;
	}
	function info($item = null) {
		global $plexWatch;
		$plexWatchDbTable = $this->historyGrouping("dbTable");
		$db = $this->dbconnect();
		$infoUrl = $this->plexURL()."/library/metadata/".$item;
		$xml = simplexml_load_string(file_get_contents($infoUrl));
		$numRows = $db->querySingle("SELECT COUNT(*) as count FROM $plexWatchDbTable WHERE session_id LIKE '%/metadata/".$item."\_%' ESCAPE '\' ORDER BY time DESC");
		if(empty($xml)) {
			$output = "<div class='summary'><H1 style='position: absolute; bottom: 0;'>".$this->lang()->errors->notAvailable."</H1></div>";
		}
		else {
			$thumb = "images/poster.png";
			
			if ($xml->Video['type'] == "episode") {
				$xmlArtUrl = "".$this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort']."".$xml->Video['art']."&width=1920&height=1080";
				$xmlThumbUrl = "".$this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort']."".$xml->Video['parentThumb']."&width=256&height=352";
				if($xml->Video['art']) {
					$poster = "includes/img.php?img=".urlencode($xmlArtUrl);
				}
				else {
					$poster = '';
				}
				if($xml->Video['parentThumb']) {
					$thumb = "includes/img.php?img=".urlencode($xmlThumbUrl);
				}
				$title = $xml->Video['grandparentTitle']." (".$this->lang()->general->shows->season." ".$xml->Video['parentIndex'].", ".$this->lang()->general->shows->episode." ".$xml->Video['index'].") \"".$xml->Video['title']."\"";
				if(isset($xml->Video->Director['tag'])) {
					$director = $this->lang()->info->directedBy.": ".$xml->Video->Director['tag'];
				}
				else {
					$director = '';
				}
				$duration = $xml->Video['duration'];
				$rating = $this->lang()->info->rated.": ".$xml->Video['contentRating'];
				$summary = $xml->Video['summary'];
				$writerCount = 0;
				$watchHistoryTitle = $this->lang()->info->watchHistory.": ".$xml->Video['title']." (".$numRows." ".$this->lang()->general->views.")";
				$tableSelect = "itemEpisode";
				$tableInput = $item;
				//Hidden on phone & tablet
				if ($xml->Video->Writer['tag']) {
					foreach($xml->Video->Writer as $xmlWriters) {
						$writers[] = "" .$xmlWriters['tag']. "";
						if (++$writerCount == 5) break;
					}
				}
				else {
					$writers = $this->lang()->general->notAvailable;
				}
			}
			else if ($xml->Directory['type'] == "show") {
				$xmlArtUrl = "".$this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort']."".$xml->Directory['art']."&width=1920&height=1080";
				$xmlThumbUrl = "".$this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort']."".$xml->Directory['thumb']."&width=456&height=552";
				
				if($xml->Directory['art']) {
					$poster = "includes/img.php?img=".urlencode($xmlArtUrl);
				}
				else {
					$poster = '';
				}
				if($xml->Directory['thumb']) {
					$thumb = "includes/img.php?img=".urlencode($xmlThumbUrl);
				}
				$title = $xml->Directory['title'];
				if(isset($xml->Directory['studio'])) {
					$director = $this->lang()->info->studio.": ".$xml->Directory['studio'];
				}
				else {
					$director = '';
				}
				$tableSelect = "itemShow";
				$tableInput = $xml->Directory['title'];
				$duration = $xml->Directory['duration'];
				$rating = $this->lang()->info->rated.": ".$xml->Directory['contentRating'];
				$summary = $xml->Directory['summary'];
				$watchHistoryTitle = $this->lang()->info->watchHistory.": ".$xml->Directory['title']." ".$this->lang()->info->are;
				
			}
			
			
			else if ($xml->Directory['type'] == "season") {
				if (!empty($plexWatch['myPlexAuthToken'])) {
					$parentInfoUrl = "".$this->plexURL()."/library/metadata/".$xml->Directory['parentRatingKey']."?X-Plex-Token=".$myPlexAuthToken."";
				}else{
					$parentInfoUrl = "".$this->plexURL()."/library/metadata/".$xml->Directory['parentRatingKey']."";
				}
				$parentXml = simplexml_load_string(file_get_contents($parentInfoUrl));
				$xmlArtUrl = "".$this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort']."".$xml->Directory['art']."&width=1920&height=1080";
				$xmlThumbUrl = "".$this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort']."".$xml->Directory['thumb']."&width=256&height=352";
				if($xml->Directory['art']) {
					$poster = "includes/img.php?img=".urlencode($xmlArtUrl);
				}
				else {
					$poster = '';
				}
				if($xml->Directory['thumb']) {
					$thumb = "includes/img.php?img=".urlencode($xmlThumbUrl);
				}
				$title = $xml->Directory['parentTitle'];
				if(isset($parentXml->Directory['studio'])) {
					$director = $this->lang()->info->studio.": ".$parentXml->Directory['studio'];
				}
				else {
					$director = '';
				}
				$duration = $parentXml->Directory['duration'];
				$rating = $this->lang()->info->rated.": ".$parentXml->Directory['contentRating'];
				$summary = $parentXml->Directory['summary'];
				$watchHistoryTitle =  $this->lang()->info->watchHistory." ".$this->lang()->general->shows->season." ".$xml->Directory['index'];
				$tableSelect = "itemSeason";
				$tableInput = $xml->Directory['index'];
				if (!empty($plexWatch['myPlexAuthToken'])) {
					$seasonEpisodesUrl = "".$plexWatchPmsUrl."/library/metadata/".$id."/children?X-Plex-Token=".$myPlexAuthToken."";
				}else{
					$seasonEpisodesUrl = "".$plexWatchPmsUrl."/library/metadata/".$id."/children";
				}
				$seasonEpisodesXml = simplexml_load_string(file_get_contents($seasonEpisodesUrl));
				$sArray = array();
				$i = 0;
				foreach ($seasonEpisodesXml->Video as $seasonEpisodes) {
					$sArray[$i]['thumb'] = $this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort']."".$seasonEpisodes['thumb']."&width=205&height=115";
					$sArray[$i]['link'] = "info.php?id=" .$seasonEpisodes['ratingKey'];
					$sArray[$i]['eNum'] = "Episode ".$seasonEpisodes['index'];
					$sArray[$i]['title'] = $seasonEpisodes['title'];
					$i++;
				}
			}
			else if ($xml->Video['type'] == "movie") {
				$xmlArtUrl = "".$this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort']."".$xml->Video['art']."&width=1920&height=1080";
				$xmlThumbUrl = "".$this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort']."".$xml->Video['thumb']."&width=256&height=352";
				if($xml->Video['art']) {
					$poster = "includes/img.php?img=".urlencode($xmlArtUrl);
				}
				else {
					$poster = '';
				}
				if($xml->Video['thumb']) {
					$thumb = "includes/img.php?img=".urlencode($xmlThumbUrl);
				}
				$title = $xml->Video['title']." (".$xml->Video['year'].")";
				$starRating = ceil ($xml->Video['rating'] / 2);
				if(isset($xml->Video->Director['tag'])) {
					$director = $this->lang()->info->directedBy.": ".$xml->Video->Director['tag'];
				}
				else {
					$director = '';
				}
				$duration = $xml->Video['duration'];
				$rating = $this->lang()->info->rated.": ".$xml->Video['contentRating'];
				$summary = $xml->Video['summary'];
				$writerCount = 0;
				$watchHistoryTitle = $this->lang()->info->watchHistory.": ".$xml->Video['title']." (".$numRows." ".$this->lang()->general->views.")";
				$tableSelect = "itemMovie";
				$tableInput = $xml->Video['title'];
				//Hidden on phone & tablet
				$genreCount = 0;
				if ($xml->Video->Genre['tag']) {
					foreach($xml->Video->Genre as $xmlGenres) {
						$genres[] = "" .$xmlGenres['tag']. "";
						if (++$genreCount == 5) break;
					}
				}
				else {
					$genres = $this->lang()->general->notAvailable;
				}
				$roleCount = 0;
				if ($xml->Video->Role['tag']) {
					foreach($xml->Video->Role as $Roles) {
						$actors[] = "" .$Roles['tag']. "";
						if (++$roleCount == 5) break;
					}
				}
				else {
					$actors = $this->lang()->general->notAvailable;
				}
				
			}
			$durationMinutes = $duration / 1000 / 60;
			$durationRounded = floor($durationMinutes);
			$durationTitle = $this->lang()->info->runtime.": ".$durationRounded." ".$this->lang()->general->time->mins;
			$output = '';
			$output .= "<div class='info-posterparallax-window' data-parallax='scroll' data-image-src='".$poster."')'>";
			$output .= "<div class='summary'>";
			$output .= "<div class='media'><div class='media-left media-bottom summary-thumb'><img src='".$thumb."'></div>";
			
			$output .= "</div></div><div class='summary-summary summary-content'><h2>".$title."</h2>".$summary."</div></div>";
			
			$output .= "<div class='well info'>";
			if ($xml->Directory['type'] == "season") {
				$output .= "<ul class='list-unstyled'>";
				$seasonEpisodesXml = simplexml_load_string(file_get_contents($seasonEpisodesUrl));
				foreach ($seasonEpisodesXml->Video as $seasonEpisodes) {
					$seasonEpisodesThumbUrl = "".$plexWatchPmsUrl."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort']."".$seasonEpisodes['thumb']."&width=205&height=115";
					$output .= "<li>";
					$output .= "<div class='season-episodes-poster'>";
						$output .= "<div class='season-episodes-poster-face'><a href='info.php?id=" .$seasonEpisodes['ratingKey']. "'><img src='includes/img.php?img=".urlencode($seasonEpisodesThumbUrl)."' class='season-episodes-poster-face'></img></a></div>";
						$output .= "<div class='season-episodes-card-overlay'><div class='season-episodes-season'>Episode ".$seasonEpisodes['index']."</div></div>";
					$output .= "</div>";
					$output .= "<div class='season-episodes-instance-text-wrapper'>";
						$output .= "<div class='season-episodes-title'><a href='info.php?id=".$seasonEpisodes['ratingKey']."'>\"".$seasonEpisodes['title']." \"</a></div>";
					$output .= "</div>";
					$output .= "</li>";
				}
				$output .= "</ul>";
			}
			if ($xml->Directory['type'] == "show") {
				$topWatchedResults = $db->query("SELECT title,time,user,orig_title,orig_title_ep,episode,season,xml,datetime(time, 'unixepoch') AS time, COUNT(*) AS play_count FROM $plexWatchDbTable WHERE orig_title LIKE \"".$xml->Directory['title']."\" GROUP BY title HAVING play_count > 0 ORDER BY play_count DESC,time DESC LIMIT 4");
				$numRows = 0;
				$output .= "<div class='row info-charts'>";
				while ($topWatchedResultsRow = $topWatchedResults->fetchArray()) {
					$topWatchedXmlUrl = $topWatchedResultsRow['xml'];
					$topWatchedXmlfield = simplexml_load_string($topWatchedXmlUrl);
					$topWatchedThumbUrl = $this->plexURL()."/photo/:/transcode?url=http://127.0.0.1:".$plexWatch['pmsHttpPort'].$topWatchedXmlfield['thumb']."&width=205&height=115";
					$numRows++;
						$output .= "<div class='col col-xs-12 col-md-6 col-sm-4 col-lg-3 charts-instance'>";
						$output .= "<div class='info-top-watched-poster'>";
						$output .= "<div class='charts-instance-position-circle'><h6>".$numRows."</h6></div>";
							$output .= "<div class='info-top-watched-poster-face'><a href='info.php?id=" .$topWatchedXmlfield['ratingKey']. "'><img src='includes/img.php?img=".urlencode($topWatchedThumbUrl)."' class='info-top-watched-poster-face'></img></a></div>";
						$output .= "<div class='info-top-watch-card-overlay'><div class='info-top-watched-season'>".$this->lang()->general->shows->season." ".$topWatchedResultsRow['season'].", ".$this->lang()->general->shows->episode." ".$topWatchedResultsRow['episode']."</div><div class='info-top-watched-playcount'><strong>".$topWatchedResultsRow['play_count']."</strong> ".$this->lang()->general->views."</div></div>";
					$output .= "</div>";
					$output .= "<div class='info-top-watched-instance-text-wrapper'>";
						$output .= "<div class='info-top-watched-title'><a href='info.php?id=".$topWatchedXmlfield['ratingKey']."'> \" ".$topWatchedResultsRow['orig_title_ep']." \"</a></div>";
						$output .= "</div></div>";
				}
				$output .= "</div>";
			}
			
			$output .= "<h2 class='title-history infoHistory'>".$watchHistoryTitle."</h2>";
			$output .= $this->history($tableSelect, "", $tableInput);
			
			$output .= "</div>";
		}
		return $output;
		
	}
}

$plexServer = new plexServer;

class plexStats {
	function dbconnect() {
		$dbFile = "plexstats.db";
		if(!class_exists('SQLite3'))
		die("<div class=\"alert alert-warning \">php5-sqlite is not installed. Please install this requirement and restart your webserver before continuing.</div>");

		$db = new SQLite3($dbFile);
		$db->busyTimeout(10*1000);
		return $db;
	}
	
	function tableCheck($table = null) {
		$db = $this->dbconnect();
		$numRows = $db->query("SELECT * FROM '".$table."'");
		$result = $numRows->fetchArray();
		$rows = $result['count'];
		echo "<pre>";
		print_r($rows);
		echo "</pre>";
		if($rows <= 0) {
			return false;
		}
		else {
			return true;
		}
	}
	
	function privilege($user) {
		if(isset($user)) {
			$db = $this->dbconnect();
			$q = $db->query("SELECT privileges FROM profiles WHERE username = '".$user."'")or die("Fout: <strong>".$db->lastErrorMsg()."</strong>");
			$row = $q->fetchArray();
			return $row['privileges'];
		}
	}
}

$plexStats = new plexStats;