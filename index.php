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
    <title><?php echo $plexServer->lang()->langinfo->siteTitle." - ".$plexServer->lang()->menu->home; ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link href="css/plexstats.core.css" rel="stylesheet">
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
	<?php echo $plexServer->auth(); ?>
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
						<li class="active"><a href="#"><?php echo $plexServer->lang()->menu->home; ?></a>
						<li><a href="history.php"><?php echo $plexServer->lang()->menu->history; ?></a></li>
						<li><a href="stats.php"><?php echo $plexServer->lang()->menu->stats; ?></a></li>
						<li><a href="users.php"><?php echo $plexServer->lang()->menu->users; ?></a></li>
						<li><a href="charts.php"><?php echo $plexServer->lang()->menu->charts; ?></a></li>
						<li><a href="settings.php"><?php echo $plexServer->lang()->menu->settings; ?></a></li>
					</ul>
					<?php $plexServer->navUser(); ?>
				</div>
			</div>
		</nav>
		<?php echo $plexServer->alert($_GET['success'], "success", "page-top"); ?>
		<div class="page-header"><h1><?php echo $plexServer->lang()->menu->home; ?></h1></div>
		<div class="well">
			<h2><?php echo $plexServer->lang()->index->statistics; ?></h2>
			<div class='row'>
				<div class='history-charts-header col-xs-12 col-sm-12 col-md-6 col-lg-6'>
					<h3><?php echo $plexServer->lang()->stats->dailyPlaysTitle; ?></h3>
					<figure class='history-charts-instance-chart chart'  id='playChartDaily'></figure>
				</div>
				<div class='history-charts-header col-xs-12 col-sm-12 col-md-6 col-lg-6'>
					<h3><?php echo $plexServer->lang()->stats->chartsHourly; ?> (<?php echo $plexServer->lang()->stats->last24Hours; ?>)</h3>
					<figure class='history-charts-instance-chart chart' id='playChartHourly'></figure>
				</div>
			</div>
		</div>
		
		<div class="well currentActivity">
			<h2><?php echo $plexServer->lang()->activity->activityHeader; ?></h2>
			<div id="loadActivity">
			
			</div>
		</div>
		
		<div class="well recentlyAdded">
		  <h2><?php echo $plexServer->lang()->index->recentlyAdded; ?></h2>
		  <div id="recentlyAdded">
			
		  </div>
		</div>
		
	</div>

	
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script src="js/xcharts.min.js"></script>
	<script src="js/d3.v3.js"></script>
	
	<script>
		if( $('.forced')[0]) {
			$('.container').addClass('blur');
			$('body').addClass('noscroll');
		}
	</script>
	<script>
		$(document).ready(loadXML());
		window.setInterval(loadXML, 15000); //will execute the function every 15 seconds
		function loadXML() {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (xhttp.readyState == 4 && xhttp.status == 200) {
					displayData(xhttp);
				}
			}
			xhttp.open("GET", "includes/activity.php?xml=sessions", true);
			xhttp.send();
		}
		
		function displayData(xml) {
			var i;
			var xmlDoc = xml.responseXML;
			var div="";
			var x = xmlDoc.getElementsByTagName("session");
			var check = xmlDoc.getElementsByTagName("noUsers");
			if(check.length > 0) {
				div += "<div class='alert alert-info' role='alert'>" + xmlDoc.getElementsByTagName("string1")[0].childNodes[0].nodeValue + "</div>";
			}
			else {
					div += "<div class='alert alert-success' role='alert'>" + xmlDoc.getElementsByTagName("string1")[0].childNodes[0].nodeValue + " " + xmlDoc.getElementsByTagName("count")[0].childNodes[0].nodeValue + " " + xmlDoc.getElementsByTagName("string2")[0].childNodes[0].nodeValue + "</div>";
				div += "<div class='row'>";
				for (i = 0; i <x.length; i++) {
					div += "<div class='" +
					x[i].getElementsByTagName("colums")[0].childNodes[0].nodeValue +
					"'>" +
					"<div class='session-item'><div class='session-thumbnail'><img class='thumbnail' src='" +
					x[i].getElementsByTagName("platformImage")[0].childNodes[0].nodeValue +
					"'><div class='session-title-wrap'><a href='" +
					x[i].getElementsByTagName("url")[0].childNodes[0].nodeValue +
					"'><h4>" +
					x[i].getElementsByTagName("title")[0].childNodes[0].nodeValue +
					"</h4></a></div><h5>" +
					x[i].getElementsByTagName("user")[0].childNodes[0].nodeValue +
					"</h5><a href='" +
					x[i].getElementsByTagName("url")[0].childNodes[0].nodeValue +
					"'><img class='poster' src='" +
					x[i].getElementsByTagName("img")[0].childNodes[0].nodeValue +
					"'></a></div><div class='session-info'><h5>" +
					x[i].getElementsByTagName("playStatus")[0].childNodes[0].nodeValue +
					"</h5><div class='dashboard-activity-metadata'><div class='progress'><div class='" +
					x[i].getElementsByTagName("bar")[0].childNodes[0].nodeValue +
					"' role='progressbar' aria-valuenow='" +
					x[i].getElementsByTagName("percent")[0].childNodes[0].nodeValue +
					"' aria-valuemin='0' aria-valuemax='100' style='width: " +
					x[i].getElementsByTagName("percent")[0].childNodes[0].nodeValue +
					"%'><span class='sr-only'>" +
					x[i].getElementsByTagName("percent")[0].childNodes[0].nodeValue +
					"% watched</span>" +
					x[i].getElementsByTagName("percent")[0].childNodes[0].nodeValue +
					"%</div></div></div><button class='btn-primary btn-block' data-toggle='collapse' data-target='#streamInfo" +
					x[i].getElementsByTagName("streamID")[0].childNodes[0].nodeValue +
					"'>Info</button><div id='streamInfo" +
					x[i].getElementsByTagName("streamID")[0].childNodes[0].nodeValue +
					"' class='collapse'><ul class='list-unstyled'><li>" +
					x[i].getElementsByTagName("streamType")[0].childNodes[0].nodeValue +
					"</li><li>" +
					x[i].getElementsByTagName("videoCodec")[0].childNodes[0].nodeValue +
					"</li><li>" +
					x[i].getElementsByTagName("audioCodec")[0].childNodes[0].nodeValue +
					"</li></ul></div></div></div></div>";
				}
				div += "</div>";
			}
			document.getElementById("loadActivity").innerHTML = div;
		}
	</script>
	
	<script>
		function recentlyAdded() {
			var widthVal= $('body').find(".recentlyAdded").width();
			$('#recentlyAdded').load('includes/recently_added.php?width=' + widthVal);
		}

		$(document).ready(function () {
			recentlyAdded()
			$(window).resize(function() {
				recentlyAdded()
			});
		});
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
		  "main": [
			{
			  "className": ".playcount",
			  "data": [
				<?php echo $plexServer->stats("dailyPlayFinal"); ?>
			  ]
			}
		  ]
		};
		var opts = {
		  "dataFormatX": function (x) { return d3.time.format('%Y-%m-%d').parse(x); },
		  "tickFormatX": function (x) { return d3.time.format('<?php echo $plexServer->lang()->stats->dateFormat; ?>')(x); },
		  "paddingLeft": ('35'),
		  "paddingRight": ('35'),
		  "paddingTop": ('10'),
		  "tickHintY": ('5'),
		  "mouseover": function (d, i) {
			var pos = $(this).offset();
			$(tt).text(d3.time.format('<?php echo $plexServer->lang()->stats->dateFormat; ?>')(d.x) + ': ' + d.y + ' <?php echo $plexServer->lang()->general->plays; ?>')
			  .css({top: topOffset + pos.top, left: pos.left + leftOffset})
			  .show();
		  },
		  "mouseout": function (x) {
			$(tt).hide();
		  }
		};
		var myChart = new xChart('bar', data, '#playChartDaily', opts);
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
		  
		  "main": [
			{
			  "className": ".playChartHourly",
			  "data": [
				<?php echo $plexServer->stats("hourlyPlayFinal"); ?>
			  ]
			}
		  ]
		};
		var opts = {
		  "dataFormatX": function (x) { return d3.time.format('%Y-%m-%d %H').parse(x); },
		  "tickFormatX": function (x) { return d3.time.format('<?php echo $plexServer->lang()->stats->timeFormat; ?>')(x); },
		  "paddingLeft": ('50'),
		  "paddingRight": ('10'),
		  "paddingTop": ('20'),
		  "tickHintY": ('5'),
		  "mouseover": function (d, i) {
			var pos = $(this).offset();
			$(tt).text(d3.time.format('<?php echo $plexServer->lang()->stats->timeFormat; ?>')(d.x) + ': ' + d.y + ' <?php echo $plexServer->lang()->general->plays; ?>')
			  .css({top: topOffset + pos.top, left: pos.left + leftOffset})
			  .show();
		  },
		  "mouseout": function (x) {
			$(tt).hide();
		  }
		};
		var myChart = new xChart('line-dotted', data, '#playChartHourly', opts);
	</script>
	
  </body>
</html>