<?php 

/**
 * Basic Page template
 *
 */

include_once("./blog.inc");
$headline = $page->get('headline|title'); 
include("./_post.inc"); 
