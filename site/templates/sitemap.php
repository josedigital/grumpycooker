<?php 

/**
 * Site map template
 *
 */

include_once("./blog.inc"); 

$content = '';
function sitemapListPage($page) {

	$content .= "<li><a href='{$page->url}'>{$page->title}</a> ";	

	if($page->numChildren) {
		$content .= "<ul>";
		foreach($page->children as $child) sitemapListPage($child); 
		$content .= "</ul>";
	}

	$content .= "</li>";
}

$content .= "<ul class='sitemap'>";
sitemapListPage($pages->get("/")); 
$content .= "</ul>";

include("./main.inc"); 

