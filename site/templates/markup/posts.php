<?php

/**
 * Output multiple blog posts
 *
 * Used by the /site/templates/blog.inc (renderPosts function)
 *
 * VARIABLES:
 * ==========
 * $posts - all the blog posts to render (PageArray)
 * $small - whether to display them in a small/summarized format (boolean)
 *
 */
switch ($page->template) {
	case 'category':
		$modifier = ' under category ' . $page->title;
		break;

	case 'tag':
		$modifier = ' tagged with ' . $page->title;
		break;
	
	default:
		$modifier = ''; 
		break;
}
// if($page->template == 'category') echo '<h3>'.$page->title.'</h3>'; 



if($small) {
	// display a headline indicating quantities
	$start = $posts->getStart()+1;
	$end = $start + count($posts)-1;
	$total = $posts->getTotal();
	$icon = ($session->gridview) ? $session->gridview : 'fa fa-th fa-2x';

	if($total) echo "
		<div id='recipes' class='small-12 columns'>
			<h3 class='left'>" . sprintf(__('Recipes %1$d to %2$d of %3$d'), $start, $end, $total) . "{$modifier}</h3>
			<div class='right m-t-1 show-med hide-small text-center'><a href='#' class='make-grid'><i class='".$icon."'></i><span class='block'>Grid View</span></a></div>
		</div>";
}

?>

<div class='posts<?php if($small) echo " posts-small"; ?>'>

	<?php 
	foreach($posts as $page) {
		include('./post.php'); 
	}
	?>

	<?php if(!count($posts)) echo "<div class='small-12 med-8 med-push-2 columns'><h3>" . __("No posts found.") . "</h3></div>"; ?>

</div><!--/.posts-->

