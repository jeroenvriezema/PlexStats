<?php
$langFile = "includes/lang.php";
if (file_exists($langFile)) {
	require_once(dirname(__FILE__) . "/includes/lang.php");
}
if (file_exists('includes/functions.php')) {
	require_once(dirname(__FILE__) . "/includes/functions.php");
}
else {
	die ("file not found");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link href="css/plexstats.core.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	<div class='container-fluid setup-page'>
		<?php if(isset($_GET['page'])) {
			echo "<h1 class='welcome-header'>".$plexServer->lang($_GET['lang'])->start->welcome."</h1>";
		}
		else {
			echo "<h1 class='welcome-header'>Welcome to PlexStats</h1>";
		}
		?>
		<img class='devices-showcase' src="images/start-image.png"></img>
	</div>
	
	<div class='container setup-content'>
	<div class='well'>
		
		
		<?php
			if(!isset($_GET['page'])) {
				echo "<h4 class='text-primary setup-first-page'>Please select your prefered language</h4>";
				echo "<form method='POST'>";
				echo "<div class='setup-first-page center'>";
				echo "<select class='setup-first-page' name='language'>";
				echo $plexServer->langDisplay();
				echo "</select>";
				echo "<input type='submit' class='btn btn-primary setup-first-page' name='chooselang' value='Submit'>";
				echo "</div>";
				echo "</form>";
				
			}
			else {
				if($_GET['page'] == 2) {
					echo "<p>".$plexServer->lang($_GET['lang'])->start->intro."</p>";
					$sqliteVer = SQLite3::version();
					echo "<ul class='list-unstyled'>";
					if (isset($_SERVER['SERVER_SOFTWARE'])) {
						echo "<li><i class='fa fa-check'></i> ".$plexServer->lang($_GET['lang'])->start->webServer.": <strong><span class='bg-success'>".$_SERVER['SERVER_SOFTWARE']."</strong></span></li>";
					}else{
						echo "<li><i class='fa fa-exclamation'></i> ".$plexServer->lang($_GET['lang'])->start->webServer.": <strong><span class='label label-important'>No information available</strong></span></li>";
					}
					$phpVersion = phpversion();
					if (!empty($phpVersion)) {
						echo "<li><i class='fa fa-check'></i> ".$plexServer->lang($_GET['lang'])->start->phpVersion.": <strong><span class='bg-success'>v".phpversion()."</strong></span></li>";
					}else{
						echo "<li><i class='fa fa-exclamation'></i> ".$plexServer->lang($_GET['lang'])->start->phpVersion.": <strong><span class='bg-warning'>No information available</strong></span></li>";
					}
					$sqliteVersion = SQLite3::version();
					if (!empty($sqliteVersion)) {
						echo "<li><i class='fa fa-check'></i> ".$plexServer->lang($_GET['lang'])->start->sqlite.": <strong><span class='bg-success'>v".$sqliteVersion['versionString']."</strong></span></li>";
					}else{
						echo "<li><i class='fa fa-exclamation'></i> ".$plexServer->lang($_GET['lang'])->start->sqlite.": <strong><span class='bg-warning'>No information available</strong></span></li>";
					}
					
					$curlVersion = curl_version();
					echo "<li><i class='fa fa-check'></i> ".$plexServer->lang($_GET['lang'])->start->phpCurl.": <strong><span class='bg-success'>" .$curlVersion['version']. "</span></strong>  / ".$plexServer->lang($_GET['lang'])->start->ssl.": <strong><span class='bg-success'>" .$curlVersion['ssl_version']."</strong></span></li>";	
					
					
					$json[] = '{"Yes":""}';
					foreach ($json as $string) {
						
						json_decode($string);

						switch (json_last_error()) {
							case JSON_ERROR_NONE:
								echo "<li><i class='fa fa-check'></i> ".$plexServer->lang($_GET['lang'])->start->json.": <strong><span class='bg-success'>".$plexServer->lang($_GET['lang'])->start->yes."</span></strong></li>";	
								break;
							case JSON_ERROR_DEPTH:
								echo "<li><i class='fa fa-exclamation''></i> ".$plexServer->lang($_GET['lang'])->start->json."t: <strong><span class='bg-warning'>Maximum stack depth exceeded</span></strong></li>";
								break;
							case JSON_ERROR_STATE_MISMATCH:
								echo "<li><i class='fa fa-exclamation''></i> ".$plexServer->lang($_GET['lang'])->start->json.": <strong><span class='bg-warning'>Underflow or the modes mismatch</span></strong></li>";
								break;
							case JSON_ERROR_CTRL_CHAR:
								echo "<li><i class='fa fa-exclamation''></i> ".$plexServer->lang($_GET['lang'])->start->json.": <strong><span class='bg-warning'>Unexpected control character found</span></strong></li>";
								break;
							case JSON_ERROR_SYNTAX:
								echo "<li><i class='fa fa-exclamation''></i> ".$plexServer->lang($_GET['lang'])->start->json.": <strong><span class='bg-warning'>Syntax error, malformed JSON</span></strong></li>";
								break;
							case JSON_ERROR_UTF8:
								echo "<li><i class='fa fa-exclamation''></i> ".$plexServer->lang($_GET['lang'])->start->json.": <strong><span class='bg-warning'>Malformed UTF-8 characters, possibly incorrectly encoded</span></strong></li>";
								break;
							default:
								echo "<li><i class='fa fa-exclamation''></i> ".$plexServer->lang($_GET['lang'])->start->json.": <strong><span class='bg-warning'>".$plexServer->lang($_GET['lang'])->start->no."</span></strong></li>";
								break;
						}
					}
					echo "<li><i class='fa fa-check'></i> ".$plexServer->lang($_GET['lang'])->start->timezone.": <strong><span class='bg-primary'>".@date_default_timezone_get()."</strong></span></li>";	
					echo "<a href='setup.php?page=3&lang=".$_GET['lang']."'><button class='btn btn-primary'>".$plexServer->lang($_GET['lang'])->start->ready."</button></a>";
					echo "<p><h4>".$plexServer->lang($_GET['lang'])->start->note.": </h4>".$plexServer->lang($_GET['lang'])->start->watchTest1." <a href='https://github.com/ljunkie/plexWatch'>plexWatch v0.1.6</a> ".$plexServer->lang($_GET['lang'])->start->watchTest2."</p>";
					
					
				}
			}
		?>
		
		<?php if(isset($_GET['page']) && $_GET['page'] == 3): ?>
		<form class='form-horizontal' role='form' action="includes/process_settings.php" method="POST">
			<div class='well'>
			
				<h3 class='text-primary setup-page-header'><?php echo $plexServer->lang($_GET['lang'])->settings->menu->general; ?></h3>
				
				<div class="form-group">
					<label for='dateFormat' class='col-sm-2 control-label'><?php echo $plexServer->lang($_GET['lang'])->settings->dateFormat; ?></label>
					<div class="col-sm-10">
						<input type='text' class='form-control' name='dateFormat' id='dateFormat' placeholder='d/m/Y' value='<?php echo $plexWatch['dateFormat'] ?>' />
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->dateFormat; ?></p>
					</div>
				</div>
				<div class="form-group">
					<label for="timeFormat" class='col-sm-2 control-label'><?php echo $plexServer->lang($_GET['lang'])->settings->timeFormat; ?></label>
					<div class="col-sm-10">
						<input type='text' name='timeFormat' class='form-control' id='timeFormat' placeholder='G:i' value='<?php echo $plexWatch['timeFormat'] ?>' />
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->timeFormat; ?></p>
					</div>
				</div>
				<div class='form-group'>
					<label for='theme' class='col-sm-2 control-label'><?php echo $plexServer->lang($_GET['lang'])->settings->themeOpt; ?></label>
					<div class="col-sm-10">
						<select id="theme" name="theme" class='form-control'>
							<?php echo $plexServer->themeSelect(); ?>
						</select>
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->theme; ?></p>
					</div>
				</div>
			</div>
			
			<div class='well'>
				<h3 class='text-primary setup-page-header'><?php echo $plexServer->lang($_GET['lang'])->settings->headerPmsDb; ?></h3>
				<div class='form-group'>
					<label for='pmsIp' class='col-sm-2 control-label'><?php echo $plexServer->lang($_GET['lang'])->settings->pmsIP; ?></label>
					<div class='col-sm-10'>
						<input type='text' name='pmsIp' class='form-control' id='pmsIp' placeholder='<?php echo $plexWatch['pmsIp'] ?>' required>
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->pmsIP; ?></p>
					</div>
				</div>
				<div class='form-group'>
					 <label class="control-label col-sm-2" for="pmsHttpPort"><?php echo $plexServer->lang($_GET['lang'])->settings->pmsWebPort; ?></label>
					 <div class='col-sm-10'>
						<input type='text' name='pmsHttpPort' class='form-control' id='pmsHttpPort' placeholder='32440'>
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->pmsWebPort; ?></p>
					</div>
				</div>
				<div class='form-group'>
					<label class="control-label col-sm-2" for="pmsHttpsPort"><?php echo $plexServer->lang($_GET['lang'])->settings->pmsSecureWebPort; ?></label>
					<div class='col-sm-10'>
						<input id="pmsHttpsPort" name="pmsHttpsPort" type="text" placeholder="32443" class="form-control">
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->pmsSecureWebPort; ?></p>
					</div>
				</div>
				
				
				<div class='form-group'>
					 <label class="control-label col-sm-2" for="https"><?php echo $plexServer->lang($_GET['lang'])->settings->pmsHTTPSCheck; ?></label>
					 <div class='col-sm-10'>
						<input type="checkbox" name="https" id="https-0" value="yes">
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->pmsHTTPSCheck; ?></p>
					</div>
				</div>
				
				<div class='form-group'>
					<label class="control-label col-sm-2" for="plexWatchDb"><?php echo $plexServer->lang($_GET['lang'])->settings->plexWatchDB; ?></label>
					<div class='col-sm-10'>
						<input id="plexWatchDb" name="plexWatchDb" type="text" class='form-control' placeholder="/opt/plexWatch/plexWatch.db" class="input-xlarge" required="" value="<?php echo $plexWatch['plexWatchDb'] ?>">
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->plexWatchDB; ?></p>
					</div>
				</div>
			</div>
			
			<div class='well'>
				<h3 class='text-primary setup-page-header'><?php echo $plexServer->lang($_GET['lang'])->settings->headerAuth; ?></h3>
				<div class='form-group'>
					<label class="control-label col-sm-2" for="myPlexUser"><?php echo $plexServer->lang($_GET['lang'])->settings->authUsername; ?></label>
					<div class='col-sm-10'>
						<input id="myPlexUser" name="myPlexUser" type="text" placeholder="" class="form-control" value="<?php echo $plexWatch['myPlexUser'] ?>">
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->authHelp1; ?> <a href="https://plex.tv/users/sign_in">Plex.tv</a> <?php echo $plexServer->lang($_GET['lang'])->settings->help->authHelp2; ?></p>
					</div>
				</div>
				
				<div class='form-group'>
					<label class="control-label col-sm-2" for="myPlexPass"><?php echo $plexServer->lang($_GET['lang'])->settings->authPassword; ?></label>
					<div class='col-sm-10'>
						<input id="myPlexPass" name="myPlexPass" type="password" placeholder="" class="form-control" value="<?php echo $plexWatch['myPlexPass'] ?>">
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->authHelp1; ?> <a href="https://plex.tv/users/sign_in">Plex.tv</a> <?php echo $plexServer->lang($_GET['lang'])->settings->help->authHelp2; ?></p>
					</div>
				</div>
			</div>
			
			
			<div class='well'>
				<h3 class='text-primary setup-page-header'><?php echo $plexServer->lang($_GET['lang'])->settings->headerGrouping; ?></h3>
				
				
				<div class='form-group'>
					<label class="control-label col-sm-2" for="globalHistoryGrouping"><?php echo $plexServer->lang($_GET['lang'])->settings->globalHistory; ?></label>
					<div class='col-sm-10'>
						<input type="checkbox" name="globalHistoryGrouping" id="globalHistoryGrouping-0" value="yes">
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->globalHistory; ?></p>
					</div>
				</div>
				
				<div class='form-group'>
					<label class="control-label col-sm-2" for="userHistoryGrouping"><?php echo $plexServer->lang($_GET['lang'])->settings->userHistory; ?></label>
					<div class='col-sm-10'>
						<input type="checkbox" name="userHistoryGrouping" id="userHistoryGrouping-0" value="yes">
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->userHistory; ?></p>
					</div>
				</div>
				
				<div class='form-group'>
					<label class="control-label col-sm-2" for="chartsGrouping"><?php echo $plexServer->lang($_GET['lang'])->settings->charts; ?></label>
					<div class='col-sm-10'>
						<input type="checkbox" name="chartsGrouping" id="chartsGrouping-0" value="yes">
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->charts; ?></p>
					</div>
				</div>
			</div>
			
			<div class='well'>
				<h3 class='text-primary setup-page-header'><?php echo $plexServer->lang($_GET['lang'])->settings->headerAuthPlexStats; ?></h3>
				<div class='form-group'>
				<label class="control-label col-sm-2" for="enableAuth"><?php echo $plexServer->lang($_GET['lang'])->settings->authCheckbox; ?></label>
					<div class='col-sm-10'>
						<input type="checkbox" name="enableAuth" id="enableAuth" value="yes" class='authEnable'>
						<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->enableAuth; ?></p>
					</div>
				</div>
				<div class='authForm'>
					<div class='form-group'>
						<label class="control-label col-sm-2" for="plexStatsUser"><?php echo $plexServer->lang($_GET['lang'])->settings->authUsername; ?></label>
						<div class='col-sm-10'>
							<input id="plexStatsUser" name="plexStatsUser" type="text" placeholder="" class="form-control">
							<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->plexStatsUser; ?></p>
						</div>
					</div>
					
					<div class='form-group'>
						<label class="control-label col-sm-2" for="plexStatsPass"><?php echo $plexServer->lang($_GET['lang'])->settings->authPassword; ?></label>
						<div class='col-sm-10'>
							<input id="plexStatsPass" name="plexStatsPass" type="password" placeholder="" class="form-control">
							<p class="help-block"><?php echo $plexServer->lang($_GET['lang'])->settings->help->plexStatsPass; ?></p>
						</div>
					</div>
				</div>
			</div>
			
			<div class='well'>
					<label class="control-label" for="submit"></label>
					<button id="submit" name="submit" class="btn btn-primary" value="save"><?php echo $plexServer->lang($_GET['lang'])->settings->btnSave; ?></button>
			</div>
			<input type='hidden' name='language' value='<?php echo $_GET['lang']; ?>'>
			<input type='hidden' name='refferal' value='setup.php'>
		</form>
			
	</div>
	<?php endif; ?>
	
	<?php if($_GET['page'] == 4) {
		if(isset($_GET['s'])) {
			echo '<div class="setup-success"><i class="setup-success fa fa-check fa-5x"></i><h3 class="setup-success-text">'.$plexServer->lang()->start->completed.'</h3><a href="index.php"><button class="btn btn-primary">'.$plexServer->lang()->start->completedButton.'</button></a></div>';
		}
	}
	?>
	
		
		<?php
		if(isset($_POST['chooselang'])) {
			$file = "config/config.php";
			$func_file = 'includes/functions.php';
			$language	= "\$plexWatch['lang'] = '".$_POST['language']."';";
			$fp = fopen($file, "w+") or die("Cannot open file $file.");
			fwrite($fp, "<?php\r\r") or die("Cannot write to file $file.");
			fwrite($fp, $language) or die("Cannot write to file $file.");
			fwrite($fp, "\r\r?>") or die("Cannot write to file $file.");
			fclose($fp);
			echo '<meta http-equiv="refresh" content="0; url=setup.php?page=2&lang='.$_POST['language'].'"/>';
		}
		?>
			<br>
			
			<br>
	
	  </div>
	</div>
  
		
  </div>
</div>