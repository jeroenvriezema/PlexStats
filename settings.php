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
    <title><?php echo $plexServer->lang()->langinfo->siteTitle." - ".$plexServer->lang()->menu->settings; ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link href="css/plexstats.core.css" rel="stylesheet">
	<link href="<?php echo $plexServer->themeLoad(); ?>" rel="stylesheet">
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
	<?php echo $plexServer->auth("settings.php?"); ?>
	<div class="container">
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only"><?php echo $plexServer->lang()->menu->toggleNav; ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.php"><img class='logo' src='images/logo.png' alt='<?php echo $plexServer->lang()->langinfo->siteTitle; ?>' /></a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li><a href="index.php"><?php echo $plexServer->lang()->menu->home; ?></a>
						<li><a href="history.php"><?php echo $plexServer->lang()->menu->history; ?></a></li>
						<li><a href="stats.php"><?php echo $plexServer->lang()->menu->stats; ?></a></li>
						<li><a href="users.php"><?php echo $plexServer->lang()->menu->users; ?></a></li>
						<li><a href="charts.php"><?php echo $plexServer->lang()->menu->charts; ?></a></li>
						<li class="active"><a href="settings.php"><?php echo $plexServer->lang()->menu->settings; ?></a></li>
					</ul>
					<?php $plexServer->navUser(); ?>
				</div>
			</div>
		</nav>
		<?php echo $plexServer->alert($_GET['success'], "success", "page-top"); ?>
		<?php
			// check for a successful form post
			if (isset($_GET['s'])) {
				echo "<div class=\"alert alert-success alert-dismissible\" role=\"alert\">";
				echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
				echo $_GET['s']."</div>";
			// check for a form error
			}elseif (isset($_GET['e'])) {
				echo "<div class=\"alert alert-danger alert-dismissible\" role=\"alert\">";
				echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
				echo $_GET['e']."</div>";
			}
		?>
		<div class="page-header"><h1><?php echo $plexServer->lang()->menu->settings; ?></h1></div>

		<div class="well">
			<h3><?php echo $plexServer->lang()->settings->versionInfo; ?></h3>
			<ul class='list-unstyled indent'>
				<li>
					<?php echo $plexServer->lang()->settings->watchWebVersion; ?>:
					<strong>v1.0.0 (master)</strong>
				</li>
			
				<?php
					$db = $plexServer->dbconnect();
					$plexWatchVersion = $db->querySingle("SELECT version FROM config ");
				?>
				<li>
					<?php echo $plexServer->lang()->settings->watchVersion; ?>:
					<strong>v<?php echo $plexWatchVersion ?></strong>
				</li>
				
				<li>
					<?php echo $plexServer->lang()->settings->transVersion; ?>:
					<strong>v<?php echo $plexServer->lang()->transInfo->version; ?></strong>
				</li>
			</ul>
		</div>
		<form class='form-horizontal' role='form' action="includes/process_settings.php" method="POST">
			<div class='well'>
			
				<h3><?php echo $plexServer->lang()->settings->menu->general; ?></h3>
				
				<div class="form-group">
					<label for='dateFormat' class='col-sm-2 control-label'><?php echo $plexServer->lang()->settings->dateFormat; ?></label>
					<div class="col-sm-10">
						<input type='text' class='form-control' name='dateFormat' id='dateFormat' placeholder='d/m/Y' value='<?php echo $plexWatch['dateFormat'] ?>' />
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->dateFormat; ?></p>
					</div>
				</div>
				<div class="form-group">
					<label for="timeFormat" class='col-sm-2 control-label'><?php echo $plexServer->lang()->settings->timeFormat; ?></label>
					<div class="col-sm-10">
						<input type='text' name='timeFormat' class='form-control' id='timeFormat' placeholder='G:i' value='<?php echo $plexWatch['timeFormat'] ?>' />
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->timeFormat; ?></p>
					</div>
				</div>
				<div class='form-group'>
					<label for='language' class='col-sm-2 control-label'><?php echo $plexServer->lang()->settings->langOpt; ?></label>
					<div class="col-sm-10">
						<select id="language" name="language" class='form-control'>
							<?php echo $plexServer->langDisplay(); ?>
						</select>
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->lang; ?></p>
					</div>
				</div>
				<div class='form-group'>
					<label for='theme' class='col-sm-2 control-label'><?php echo $plexServer->lang()->settings->themeOpt; ?></label>
					<div class="col-sm-10">
						<select id="theme" name="theme" class='form-control'>
							<?php echo $plexServer->themeSelect(); ?>
						</select>
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->theme; ?></p>
					</div>
				</div>
			</div>
			
			<div class='well'>
				<h3><?php echo $plexServer->lang()->settings->headerPmsDb; ?></h3>
				<div class='form-group'>
					<label for='pmsIp' class='col-sm-2 control-label'><?php echo $plexServer->lang()->settings->pmsIP; ?></label>
					<div class='col-sm-10'>
						<input type='text' name='pmsIp' class='form-control' id='pmsIp' placeholder='<?php echo $plexWatch['pmsIp'] ?>' value='<?php echo $plexWatch['pmsIp'] ?>' required>
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->pmsIP; ?></p>
					</div>
				</div>
				<div class='form-group'>
					 <label class="control-label col-sm-2" for="pmsHttpPort"><?php echo $plexServer->lang()->settings->pmsWebPort; ?></label>
					 <div class='col-sm-10'>
						<input type='text' name='pmsHttpPort' class='form-control' id='pmsHttpPort' placeholder='<?php echo $plexWatch['pmsHttpPort']; ?>' value='<?php echo $plexWatch['pmsHttpPort']; ?>'>
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->pmsWebPort; ?></p>
					</div>
				</div>
				<div class='form-group'>
					<label class="control-label col-sm-2" for="pmsHttpsPort"><?php echo $plexServer->lang()->settings->pmsSecureWebPort; ?></label>
					<div class='col-sm-10'>
						<input id="pmsHttpsPort" name="pmsHttpsPort" type="text" placeholder="32443" class="form-control" required="" value="<?php echo $plexWatch['pmsHttpsPort'] ?>">
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->pmsSecureWebPort; ?></p>
					</div>
				</div>
				
				<?php
				if ($plexWatch['https'] == "no" ) {
					$https = '';
				}else if ($plexWatch['https'] == "yes" ) {
					$https = "checked='yes'";
				}
				?>
				
				<div class='form-group'>
					 <label class="control-label col-sm-2" for="https"><?php echo $plexServer->lang()->settings->pmsHTTPSCheck; ?></label>
					 <div class='col-sm-10'>
						<input type="checkbox" name="https" id="https-0" value="yes" <?php echo $https ?>>
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->pmsHTTPSCheck; ?></p>
					</div>
				</div>
				
				<div class='form-group'>
					<label class="control-label col-sm-2" for="plexWatchDb"><?php echo $plexServer->lang()->settings->plexWatchDB; ?></label>
					<div class='col-sm-10'>
						<input id="plexWatchDb" name="plexWatchDb" type="text" class='form-control' placeholder="<?php echo $plexWatch['plexWatchDb'] ?>" class="input-xlarge" required="" value="<?php echo $plexWatch['plexWatchDb'] ?>">
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->plexWatchDB; ?></p>
					</div>
				</div>
			</div>
			
			<div class='well'>
				<h3><?php echo $plexServer->lang()->settings->headerAuth; ?></h3>
				<div class='form-group'>
					<label class="control-label col-sm-2" for="myPlexUser"><?php echo $plexServer->lang()->settings->authUsername; ?></label>
					<div class='col-sm-10'>
						<input id="myPlexUser" name="myPlexUser" type="text" placeholder="" class="form-control" value="<?php echo $plexWatch['myPlexUser'] ?>">
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->authHelp1; ?> <a href="https://plex.tv/users/sign_in">Plex.tv</a> <?php echo $plexServer->lang()->settings->help->authHelp2; ?></p>
					</div>
				</div>
				
				<div class='form-group'>
					<label class="control-label col-sm-2" for="myPlexPass"><?php echo $plexServer->lang()->settings->authPassword; ?></label>
					<div class='col-sm-10'>
						<input id="myPlexPass" name="myPlexPass" type="password" placeholder="" class="form-control" value="<?php echo $plexWatch['myPlexPass'] ?>">
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->authHelp1; ?> <a href="https://plex.tv/users/sign_in">Plex.tv</a> <?php echo $plexServer->lang()->settings->help->authHelp2; ?></p>
					</div>
				</div>
			</div>
			
			
			<div class='well'>
				<h3><?php echo $plexServer->lang()->settings->headerGrouping; ?></h3>
				
				<?php
							
				if ($plexWatch['globalHistoryGrouping'] == "no" ) {
					$globalHistoryGrouping = '';
				}else if ($plexWatch['globalHistoryGrouping'] == "yes" ) {
					$globalHistoryGrouping = "checked='yes'";
				}
				
				
				if ($plexWatch['userHistoryGrouping'] == "no" ) {
					$userHistoryGrouping = '';
				}else if ($plexWatch['userHistoryGrouping'] == "yes" ) {
					$userHistoryGrouping = "checked='yes'";
				}
				
				
				if ($plexWatch['chartsGrouping'] == "no" ) {
					$chartsGrouping = '';
				}else if ($plexWatch['chartsGrouping'] == "yes" ) {
					$chartsGrouping = "checked='yes'";
				}

				?>
				
				<div class='form-group'>
					<label class="control-label col-sm-2" for="globalHistoryGrouping"><?php echo $plexServer->lang()->settings->globalHistory; ?></label>
					<div class='col-sm-10'>
						<input type="checkbox" name="globalHistoryGrouping" id="globalHistoryGrouping-0" value="yes" <?php echo $globalHistoryGrouping; ?>>
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->globalHistory; ?></p>
					</div>
				</div>
				
				<div class='form-group'>
					<label class="control-label col-sm-2" for="userHistoryGrouping"><?php echo $plexServer->lang()->settings->userHistory; ?></label>
					<div class='col-sm-10'>
						<input type="checkbox" name="userHistoryGrouping" id="userHistoryGrouping-0" value="yes" <?php echo $userHistoryGrouping; ?>>
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->userHistory; ?></p>
					</div>
				</div>
				
				<div class='form-group'>
					<label class="control-label col-sm-2" for="chartsGrouping"><?php echo $plexServer->lang()->settings->charts; ?></label>
					<div class='col-sm-10'>
						<input type="checkbox" name="chartsGrouping" id="chartsGrouping-0" value="yes" <?php echo $chartsGrouping; ?>>
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->charts; ?></p>
					</div>
				</div>
			</div>
			
			<div class='well'>
				<?php
				if ($plexWatch['enableAuth'] == "no" ) {
					$enableAuth = "";
				}else if ($plexWatch['enableAuth'] == "yes" ) {
					$enableAuth = "checked='yes'";
				}
				?>
				<h3><?php echo $plexServer->lang()->settings->headerAuthPlexStats; ?></h3>
				<div class='form-group'>
				<label class="control-label col-sm-2" for="enableAuth"><?php echo $plexServer->lang()->settings->authCheckbox; ?></label>
					<div class='col-sm-10'>
						<input type="checkbox" name="enableAuth" id="enableAuth" value="yes" class='authEnable' <?php echo $enableAuth; ?>>
						<p class="help-block"><?php echo $plexServer->lang()->settings->help->enableAuth; ?></p>
					</div>
				</div>
				<div class='authForm'>
					<div class='form-group'>
						<label class="control-label col-sm-2" for="plexStatsUser"><?php echo $plexServer->lang()->settings->authUsername; ?></label>
						<div class='col-sm-10'>
							<input id="plexStatsUser" name="plexStatsUser" type="text" placeholder="" class="form-control" value="<?php echo $plexWatch['username'] ?>">
							<p class="help-block"><?php echo $plexServer->lang()->settings->help->plexStatsUser; ?></p>
						</div>
					</div>
					
					<div class='form-group'>
						<label class="control-label col-sm-2" for="plexStatsPass"><?php echo $plexServer->lang()->settings->authPassword; ?></label>
						<div class='col-sm-10'>
							<input id="plexStatsPass" name="plexStatsPass" type="password" placeholder="" class="form-control">
							<p class="help-block"><?php echo $plexServer->lang()->settings->help->plexStatsPass; ?></p>
						</div>
					</div>
				</div>
			</div>
			
			<div class='well'>
					<label class="control-label" for="submit"></label>
					<button id="submit" name="submit" class="btn btn-success" value="save"><?php echo $plexServer->lang()->settings->btnSave; ?></button>
					<a href="index.php"><button type="button" class="btn btn-danger"><?php echo $plexServer->lang()->settings->btnCancel; ?></button></a>
			</div>
			
		</form>
			
	</div>
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	
	<script>
		$(document).ready(function() {
			if($("input[type=checkbox].authEnable").is(":checked"))
				$(".authForm").show();
		});
		$(".authEnable").click(function() {
			if($(this).is(":checked"))
				$(".authForm").fadeIn();
			else
				$(".authForm").fadeOut();
		});
	</script>
	<script>
		if( $('.forced')[0]) {
			$('.container').addClass('blur');
			$('body').addClass('noscroll');
		}
	</script>
	
  </body>
</html>