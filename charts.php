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
    <title><?php echo $plexServer->lang()->langinfo->siteTitle." - ".$plexServer->lang()->menu->charts; ?></title>

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
	<?php echo $plexServer->auth("charts.php?"); ?>
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
						<li class="active"><a href="charts.php"><?php echo $plexServer->lang()->menu->charts; ?></a></li>
						<li><a href="settings.php"><?php echo $plexServer->lang()->menu->settings; ?></a></li>
					</ul>
					<?php $plexServer->navUser(); ?>
				</div>
			</div>
		</nav>
		<?php echo $plexServer->alert($_GET['success'], "success", "page-top"); ?>
		<div class="page-header"><h1><?php echo $plexServer->lang()->menu->charts; ?></h1></div>
		
		<div class="well charts">
			<div class='row'>
			<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
				<h3><?php echo $plexServer->lang()->charts->top10All; ?></h3>
				<div id="top10all" class="carousel slide" data-ride="carousel">
				<!-- Indicators -->
				  <ol class="carousel-indicators">
					<li data-target="#top10all" data-slide-to="0" class="active"></li>
					<li data-target="#top10all" data-slide-to="1"></li>
					<li data-target="#top10all" data-slide-to="2"></li>
					<li data-target="#top10all" data-slide-to="3"></li>
					<li data-target="#top10all" data-slide-to="4"></li>
					<li data-target="#top10all" data-slide-to="5"></li>
					<li data-target="#top10all" data-slide-to="6"></li>
					<li data-target="#top10all" data-slide-to="7"></li>
					<li data-target="#top10all" data-slide-to="8"></li>
					<li data-target="#top10all" data-slide-to="9"></li>
				  </ol>
				  <div class="carousel-inner" role="listbox">
					<?php echo $plexServer->top10("all"); ?>
				  </div>
				  <!-- Left and right controls -->
				  <a class="left carousel-control" href="#top10all" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				  </a>
				  <a class="right carousel-control" href="#top10all" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				  </a>
				</div>
			</div>
			<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>	 
				 <h3><?php echo $plexServer->lang()->charts->top10Films; ?></h3>
				 <div id="top10movies" class="carousel slide" data-ride="carousel">
				<!-- Indicators -->
				  <ol class="carousel-indicators">
					<li data-target="#top10movies" data-slide-to="0" class="active"></li>
					<li data-target="#top10movies" data-slide-to="1"></li>
					<li data-target="#top10movies" data-slide-to="2"></li>
					<li data-target="#top10movies" data-slide-to="3"></li>
					<li data-target="#top10movies" data-slide-to="4"></li>
					<li data-target="#top10movies" data-slide-to="5"></li>
					<li data-target="#top10movies" data-slide-to="6"></li>
					<li data-target="#top10movies" data-slide-to="7"></li>
					<li data-target="#top10movies" data-slide-to="8"></li>
					<li data-target="#top10movies" data-slide-to="9"></li>
				  </ol>
				  <div class="carousel-inner" role="listbox">
					<?php echo $plexServer->top10("movies"); ?>
				  </div>
				  <!-- Left and right controls -->
				  <a class="left carousel-control" href="#top10movies" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				  </a>
				  <a class="right carousel-control" href="#top10movies" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				  </a>
				</div>
			</div>
			<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
				 <h3><?php echo $plexServer->lang()->charts->top10Series; ?></h3>
				 <div id="top10shows" class="carousel slide" data-ride="carousel">
				<!-- Indicators -->
				  <ol class="carousel-indicators">
					<li data-target="#top10shows" data-slide-to="0" class="active"></li>
					<li data-target="#top10shows" data-slide-to="1"></li>
					<li data-target="#top10shows" data-slide-to="2"></li>
					<li data-target="#top10shows" data-slide-to="3"></li>
					<li data-target="#top10shows" data-slide-to="4"></li>
					<li data-target="#top10shows" data-slide-to="5"></li>
					<li data-target="#top10shows" data-slide-to="6"></li>
					<li data-target="#top10shows" data-slide-to="7"></li>
					<li data-target="#top10shows" data-slide-to="8"></li>
					<li data-target="#top10shows" data-slide-to="9"></li>
				  </ol>
				  <div class="carousel-inner" role="listbox">
					<?php echo $plexServer->top10("tvShows", "topShows"); ?>
				  </div>
				  <!-- Left and right controls -->
				  <a class="left carousel-control" href="#top10shows" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				  </a>
				  <a class="right carousel-control" href="#top10shows" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				  </a>
				</div>
			</div>
			<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
				<h3><?php echo $plexServer->lang()->charts->top10Series; ?></h3>
				 <div id="top10episodes" class="carousel slide" data-ride="carousel">
				<!-- Indicators -->
				  <ol class="carousel-indicators">
					<li data-target="#top10episodes" data-slide-to="0" class="active"></li>
					<li data-target="#top10episodes" data-slide-to="1"></li>
					<li data-target="#top10episodes" data-slide-to="2"></li>
					<li data-target="#top10episodes" data-slide-to="3"></li>
					<li data-target="#top10episodes" data-slide-to="4"></li>
					<li data-target="#top10episodes" data-slide-to="5"></li>
					<li data-target="#top10episodes" data-slide-to="6"></li>
					<li data-target="#top10episodes" data-slide-to="7"></li>
					<li data-target="#top10episodes" data-slide-to="8"></li>
					<li data-target="#top10episodes" data-slide-to="9"></li>
				  </ol>
				  <div class="carousel-inner" role="listbox">
					<?php echo $plexServer->top10("episodes"); ?>
				  </div>
				  <!-- Left and right controls -->
				  <a class="left carousel-control" href="#top10episodes" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				  </a>
				  <a class="right carousel-control" href="#top10episodes" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				  </a>
				</div>
			</div>
		</div>
	</div>
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script>
		if( $('.forced')[0]) {
			$('.container').addClass('blur');
			$('body').addClass('noscroll');
		}
	</script>
	
  </body>
</html>