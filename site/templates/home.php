<?php 

/**
 * Home template
 *
 */

include_once("./blog.inc"); 

$categories = $pages->get('/categories/'); 

$content = '<section class="recipes-list">
            <div class="container">';
$content .= renderPosts("limit={$page->quantity}", $small= true);
$content .= '</div>
        </section>';
$subnav = renderNav($categories->title, $categories->children); 

include("./main.inc"); 
