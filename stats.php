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
    <title><?php echo $plexServer->lang()->langinfo->siteTitle." - ".$plexServer->lang()->menu->stats; ?></title>

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/animateNumbers.js"></script>
  </head>
  <body>
	<?php echo $plexServer->auth("stats.php?"); ?>
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
						<li class="active"><a href="stats.php"><?php echo $plexServer->lang()->menu->stats; ?></a></li>
						<li><a href="users.php"><?php echo $plexServer->lang()->menu->users; ?></a></li>
						<li><a href="charts.php"><?php echo $plexServer->lang()->menu->charts; ?></a></li>
						<li><a href="settings.php"><?php echo $plexServer->lang()->menu->settings; ?></a></li>
					</ul>
					<?php $plexServer->navUser(); ?>
				</div>
			</div>
		</nav>
		<?php echo $plexServer->alert($_GET['success'], "success", "page-top"); ?>
		<div class="page-header"><h1><?php echo $plexServer->lang()->menu->stats; ?></h1></div>
		
		<div class="well">
		  <h2><?php echo $plexServer->lang()->stats->files; ?></h2>
			
			
		  <div class='row fileStats'>
			<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
				<h3><?php echo $plexServer->lang()->user->movies; ?></h3>
				<h5 class='help-block'><?php echo $plexServer->lang()->stats->fileDist; ?></h5>
				<div id="sizeChart" class="doughnut"></div>
			</div>
			
			<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
				<h3><?php echo $plexServer->lang()->user->episodes; ?></h3>
				<h5 class='help-block'><?php echo $plexServer->lang()->stats->fileDist; ?></h5>
				<div id="sizeChartSeries" class="doughnut"></div>
			</div>
		      <?php
		      $i = 0;
			  $sectionsPercent = array();
		      foreach($plexServer->sectionCount() as $section) {
		        $i++;
		        if($section['type'] == "movie") {
				  $sectionsTotal += $section['size'];
		        }
		      }
			  $section = null;
			  $i = 0;
			  foreach($plexServer->sectionCount() as $section) {
				  $i++;
				if($section['type'] == "movie") {
					$sectionsPercentage = $section['size'] / $sectionsTotal;
					$countPercent = round($sectionsPercentage * 100);
					$percent = number_format($countPercent, 2);
					$sectionsPercent[$i]['name'] = $section['name'];
					$sectionsPercent[$i]['percent'] = $percent;
				}
			  }
			  
			  
		      ?>
		  </div>
		</div>
		<div class='well'>
		  <h2><?php echo $plexServer->lang()->stats->graphs; ?></h2>
		  
			<div class='row'>
				<div class='history-charts-header col-xs-12 col-sm-12 col-md-6 col-lg-6'><h5><strong><?php echo $plexServer->lang()->stats->chartsHourly; ?></strong> (<?php echo $plexServer->lang()->stats->last24Hours; ?>)</h5><figure class='history-charts-instance-chart chart' id='playChartHourly'></figure></div>
				<div class='history-charts-header col-xs-12 col-sm-12 col-md-6 col-lg-6'><h5><strong><?php echo $plexServer->lang()->stats->maxPlaysTitle; ?></strong></h5><figure class='history-charts-instance-chart chart' id='playChartMaxHourly'></figure></div>
				<div class='history-charts-header col-xs-12 col-sm-12 col-md-6 col-lg-6'><h5><strong><?php echo $plexServer->lang()->stats->dailyPlaysTitle; ?></strong></h5><figure class='history-charts-instance-chart chart'  id='playChartDaily'></figure></div>
				<div class='history-charts-header col-xs-12 col-sm-12 col-md-6 col-lg-6'><h5><strong><?php echo $plexServer->lang()->stats->monthlyPlaysTitle; ?></strong></h5><figure class='history-charts-instance-chart chart' id='playChartMonthly'></figure></div>
				<div class='history-charts-header col-xs-12 col-sm-12 col-md-6 col-lg-6'><h5><strong><?php echo $plexServer->lang()->stats->userPlaysTitle; ?></strong></h5><figure class='history-charts-instance-chart chart' id='playChartUsers'></figure></div>
				<div class='history-charts-header col-xs-12 col-sm-12 col-md-6 col-lg-6'><h5><strong><?php echo $plexServer->lang()->stats->platformPlays; ?></strong></h5><figure class='history-charts-instance-chart chart' id='platPlays'></figure></div>
			</div>
		</div>
		<?php //echo $plexServer->stats("platPlays"); ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script src="js/xcharts.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
	<script src="js/doughnut.js"></script>
	<script>
		if( $('.forced')[0]) {
			$('.container').addClass('blur');
			$('body').addClass('noscroll');
		}
	</script>
	
	<script>
	
		$(document).ready(function() {
			$(function(){
			  $("#sizeChart").drawDoughnutChart([
				<?php foreach($sectionsPercent as $section) {
					echo '{ title: "'.$section["name"].'",	value : '.$section["percent"].', color: "'.sprintf("#%06x",rand(0,16777215)).'"},';
				}
				?>
			  ]);
			});
			});
		  </script>
		  
		  <script>
		  $(document).ready(function() {
			$(function(){
			  $("#sizeChartSeries").drawDoughnutChart([
				<?php foreach($plexServer->sectionCount("test") as $row) {
					echo '{ title: "'.$row["name"].'",	value : '.$row["percent"].', color: "'.sprintf("#%06x",rand(0,16777215)).'"},';
				}
				?>
			  ]);
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
			  "className": ".maxplayChartHourly",
			  "data": [
				<?php echo $plexServer->stats("maxhourlyPlayFinal"); ?>
			  ]
			}
		  ]
		};
		var opts = {
		  "dataFormatX": function (x) { return d3.time.format('%Y-%m-%d %H').parse(x); },
		  "tickFormatX": function (x) { return d3.time.format('<?php echo $plexServer->lang()->stats->dateFormat; ?>')(x); },
		  "paddingLeft": ('35'),
		  "paddingRight": ('35'),
		  "paddingTop": ('10'),
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
		var myChart = new xChart('bar', data, '#playChartMaxHourly', opts);
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
				<?php echo $plexServer->stats("userPlays"); ?>
			  ]
			}
		  ]
		};
		var opts = {
		  "paddingLeft": ('35'),
		  "paddingRight": ('35'),
		  "paddingTop": ('10'),
		  "tickHintY": ('5'),
		  "mouseover": function (d, i) {
			var pos = $(this).offset();
			$(tt).text(d.x + ": " + d.y + ' <?php echo $plexServer->lang()->general->plays; ?>')
			  .css({top: topOffset + pos.top, left: pos.left + leftOffset})
			  .show();
		  },
		  "mouseout": function (x) {
			$(tt).hide();
		  }
		};
		var myChart = new xChart('bar', data, '#playChartUsers', opts);
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
				<?php echo $plexServer->stats("platPlays"); ?>
			  ]
			}
		  ]
		};
		var opts = {
		  "paddingLeft": ('35'),
		  "paddingRight": ('35'),
		  "paddingTop": ('10'),
		  "tickHintY": ('5'),
		  "mouseover": function (d, i) {
			var pos = $(this).offset();
			$(tt).text(d.x + ": " + d.y + ' <?php echo $plexServer->lang()->general->plays; ?>')
			  .css({top: topOffset + pos.top, left: pos.left + leftOffset})
			  .show();
		  },
		  "mouseout": function (x) {
			$(tt).hide();
		  }
		};
		var myChart = new xChart('bar', data, '#platPlays', opts);
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
		  "type"  : "line-dotted",
		  "main": [
			{
			  "className": ".playcount",
			  "data": [
				<?php echo $plexServer->stats("monthlyPlayFinal"); ?>
			  ]
			}
		  ]
		};
		var opts = {
		  "dataFormatX": function (x) { return d3.time.format('%Y-%m').parse(x); },
		  "tickFormatX": function (x) { return d3.time.format('%b')(x); },
		  "paddingLeft": ('35'),
		  "paddingRight": ('35'),
		  "paddingTop": ('10'),
		  "tickHintY": ('5'),
		  "mouseover": function (d, i) {
			var pos = $(this).offset();
			$(tt).text(d3.time.format('%b')(d.x) + ': ' + d.y + ' <?php echo $plexServer->lang()->general->plays; ?>')
			  .css({top: topOffset + pos.top, left: pos.left + leftOffset})
			  .show();
		  },
		  "mouseout": function (x) {
			$(tt).hide();
		  }
		};
		var myChart = new xChart('line-dotted', data, '#playChartMonthly', opts);
	</script>
	
  </body>
</html>