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
    <title><?php echo $plexServer->lang()->langinfo->siteTitle." - ".$plexServer->lang()->menu->users; ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link href="css/plexstats.core.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.9,fh-3.0.0,r-1.0.7/datatables.min.css"/>
	<link href="css/xcharts.min.css" rel="stylesheet">
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
	<?php echo $plexServer->auth("users.php?"); ?>
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
						<li><a href="index.php"><?php echo $plexServer->lang()->menu->home; ?></a></li>
						<li><a href="history.php"><?php echo $plexServer->lang()->menu->history; ?></a></li>
						<li><a href="stats.php"><?php echo $plexServer->lang()->menu->stats; ?></a></li>
						<li class="active"><a href="users.php"><?php echo $plexServer->lang()->menu->users; ?></a></li>
						<li><a href="charts.php"><?php echo $plexServer->lang()->menu->charts; ?></a></li>
						<li><a href="settings.php"><?php echo $plexServer->lang()->menu->settings; ?></a></li>
					</ul>
					<?php $plexServer->navUser(); ?>
				</div>
			</div>
		</nav>
		<?php echo $plexServer->alert($_GET['success'], "success", "page-top"); ?>
		<div class="page-header"><h1><?php echo $plexServer->lang()->menu->users; ?></h1></div>
		
		<div class="well users">
			<figure class='chart' id='userHistoryChart'></figure>
			<?php echo $plexServer->users(); ?>
		</div>
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.9,fh-3.0.0,r-1.0.7/datatables.min.js"></script>
	<?php echo $plexServer->tableScript("usersTable"); ?>
	<script src="js/xcharts.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
	
	<script>
		if( $('.forced')[0]) {
			$('.container').addClass('blur');
			$('body').addClass('noscroll');
		}
	</script>
	
	<script>
			var tt = document.createElement('div'),
			  leftOffset = -(~~$('html').css('padding-left').replace('px', '') + ~~$('body').css('margin-left').replace('px', '')),
			  topOffset = -35;
			tt.className = 'ex-tooltip';
			document.body.appendChild(tt);

			var data = {
			  "xScale": "ordinal",
			  "yScale": "linear",
			  "type"  : "line-dotted",
			  "main": [
				<?php echo $plexServer->userRecents("", "allUsers"); ?>
			  ]
			};
			var opts = {
			  "dataFormatX": function (x) { return d3.time.format('%Y-%m-%d').parse(x); },
			  "tickFormatX": function (x) { return d3.time.format('<?php echo $plexServer->lang()->user->dayDisplay; ?>')(x); },
			  "paddingLeft": ('35'),
			  "paddingRight": ('35'),
			  "paddingTop": ('10'),
			  "tickHintY": ('5'),
			  "mouseover": function (d, i) {
				var pos = $(this).offset();
				$(tt).text((d.user) + ': ' + d3.time.format('<?php echo $plexServer->lang()->user->dateDisplay; ?>')(d.x) + ': ' +  d.y + ' <?php echo $plexServer->lang()->general->plays; ?>')
				  .css({top: topOffset + pos.top, left: pos.left + leftOffset})
				  .show();
			  },
			  "mouseout": function (x) {
				$(tt).hide();
			  }
			};
			var myChart = new xChart('line-dotted', data, '#userHistoryChart', opts);
	</script>
	
  </body>
</html>