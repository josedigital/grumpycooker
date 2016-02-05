<?php

/**
 * Search template
 *
 */

include_once("./blog.inc");

$out = '';

if($q = $sanitizer->selectorValue($input->get->q)) {

	// Send our sanitized query 'q' variable to the whitelist where it will be
	// picked up and echoed in the search box by the ./blog/topnav.php file.
	$input->whitelist('q', $q); 

	// Search the title, body fields for our query text.
	$posts = $pages->find("template=post, title|ingredients|process|body%=$q, limit=10"); 
	$total = $posts->getTotal();
	$query = htmlentities($q, ENT_QUOTES, "UTF-8");

	$headline = sprintf(__('You searched for: %s'), $query); 
	$content = '<section class="recipes-list">
	            <div class="container">';
	$content .= renderPosts($posts, true); 
	$content .= '</div>
	        </section>';

} else {
	$headline = __('Please enter a search term in the search box');
}

include("./main.inc");
