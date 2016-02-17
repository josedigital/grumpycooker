<?php 

/**
 * Home template
 *
 */

include_once("./blog.inc"); 

$categories = $pages->get('/categories/'); 

$content = '<section class="recipes-list">';
$content .= renderPosts("limit={$page->quantity}", $small= true);
$content .= '</section>';
$subnav = renderNav($categories->title, $categories->children); 

include("./main.inc"); 
