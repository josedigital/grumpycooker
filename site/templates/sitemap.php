<?php 

/**
 * Site map template
 *
 */
ob_start();

include_once("./blog.inc"); 

function sitemapListPage($page) {

	echo "<li><a href='{$page->url}'>{$page->title}</a> ";	

	if($page->numChildren) {
		echo "<ul>";
		foreach($page->children as $child) sitemapListPage($child); 
		echo "</ul>";
	}

	echo "</li>";
}

echo "<div class='container'><div class='small-12 med-8 med-push-2 columns'>";
echo "<ul class='sitemap'>";
sitemapListPage($pages->get("/")); 
echo "</ul>";
echo "</div></div>";


$content = ob_get_clean();
include("./main.inc"); 

