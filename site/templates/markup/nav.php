<?php

/**
 * Nav markup
 *
 * Used by /site/templates/blog.inc (renderNav function)
 *
 * VARIABLES:
 * ==========
 * $headline - Optional headline to display above the navigation.
 * $items - Navigation items to display (array of $url => $title)
 * $currentURL - URL of current item (for highlighting active nav)
 * $mobile - Whether to include alternate <select> nav for mobile width.
 *
 * When the $mobile option is set, make the ul.nav disappear when at mobile width 
 * and instead show only the form <select> navigation instead. 
 * This happens because the css media query recognizes the 'no-mobile' class 
 * and hides any thing carrying that class. Likewise, anything with the 'mobile'
 * class is only shown when at mobile width. 
 *
 */

if($headline) echo "<h4 class='nav-headline'>$headline</h4>"; 

$class = 'list-unstyled font-bold uppercase';

echo "<ul class='$class'>"; 

foreach($items as $item) {
	if($item['url'] == $currentURL) {
		echo "<li class='on'><a class='on ".$item["cls"]."' href='".$item['url']."'>".$item['title']."</a></li>";
	} else {
		echo "<li><a class='".$item['cls']."' href='".$item['url']."'>".$item['title']."</a></li>";
	}
}

echo "</ul>";