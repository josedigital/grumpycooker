<?php

/**
 * The main HTML/markup wrapper file for the blog templates
 *
 */

// We pull some data from the homepage in a few places in this main template, so we keep it ready here
$homepage = $pages->get('/');

if(empty($content)) $content = '<section class="page-body"><div class="container"><div class="small-12 med-8 med-push-2 columns"><h3 class="font-bold uppercase">'.$headline.'</h3>'.$page->body.'</div></div></section>';

// determine what the ever important browser <title> tag should be
if($page->browser_title) $browserTitle = $page->browser_title; 
	else if($page === $homepage) $browserTitle = $page->headline; 
	else $browserTitle = $page->title . ' &bull; ' . $homepage->headline; 

// add our stylesheets
$config->styles->add($config->urls->templates . 'css/index.css'); 

// add our javascripts
$config->scripts->prepend('http://code.jquery.com/jquery-1.7.2.min.js');
$config->scripts->add($config->urls->templates . 'js/main.js'); 

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <meta name="keywords" content="plant-based, mexican, recipes, vegan, easy, cauliflower, tacos, rice, beans, tortillas, guisados, healthy, no-oil, low-oil">
    <?php if($homepage->summary) echo '<meta name="summary" content="'.$homepage->summary.'">'; ?>
    
    <title><?php echo $browserTitle; ?></title>
    <?php if($page->summary) echo "<meta name='description' content='{$page->summary} And mostly Mexican influenced.' />"; ?>
	<?php foreach($config->styles as $key => $file) echo "\n\t<link rel='stylesheet' type='text/css' href='$file'>"; ?>
</head>


	

	

	
<body class='<?php echo $page->template.' '.$page->name;?>'>
    <nav>
    	<?php echo renderNav('', $homepage->children->prepend($homepage), $page); ?>
    	<a href="<?php echo $config->urls->root; ?>">
    	<img src="<?php echo $config->urls->templates; ?>img/grumpy-skull.png" alt="the grumpy cooker" class="nav-logo hide hide-small show-large">
    	</a>
    </nav>


    <div class="main-content">


    	<div class="container masthead">
    		<div class="small-12 columns text-center">
    			<?php echo "<a href='{$config->urls->root}'>"; ?>
    			<img src="<?php echo $config->urls->templates; ?>img/grumpy.png" alt="the grumpy cooker" width="309">
    			<?php echo "<h3 class='font-bold tagline'>{$homepage->summary}</h3>
    			</a>"; ?>
    		</div>
    	</div><!-- /.masthead -->


		<section class="search">
			<div class="container">
				<div class="small-12 med-8 med-push-2 columns">
					<?php
					$searchPage = wire('pages')->get('template=search');
					$searchQuery = htmlentities(wire('input')->whitelist('q'), ENT_QUOTES, "UTF-8");
					?>

					<form id='site-search-form' class='site-search-form' action='<?php echo $searchPage->url; ?>' method='get'>
						<input type='text' name='q' value='<?php echo $searchQuery; ?>' placeholder='<?php echo $searchPage->title; ?>'>
					</form>	
				</div>
			</div>
			
		</section>

    	<?php echo $content; ?>
            


	<?php
	// if($page->editable()) include("./markup/admin.php"); // show an admin widget with edit+new links
	// echo $subnav; 
	?>
	
		<section class="widgets">
			<div class="container">
				<div class="small-12 columns">
					<?php
					// render widgets
					// $widgets = $homepage->widgets; 
					// if(!$widgets || !count($widgets)) $widgets = $homepage->widgets; 
					// foreach($widgets as $widget) {
					// 	echo "<div class='widget'>" . $widget->name . "</div>";
					// }
					// just get 'recept-posts' widget
					// echo $homepage->widgets->get('recent-posts')->render();
					?>
				</div>
			</div>
		</section>



    </div><!-- /.main-content -->





	<footer id="wrap-footer">
		<div class="container">
			<div id="footer" class="small-12 columns">
				<small id='footer_note'><?php echo $homepage->note; ?></small>
				<small id='processwire'>Powered by <a target='_blank' href='http://processwire.com'>ProcessWire Open Source CMS</a></small>
			</div><!--/#footer-->
		</div><!--/.container-->
	</footer><!--/#footer-container-->

<?php foreach($config->scripts as $file) echo "<script src='$file'></script>"; ?>
</body>
</html>
