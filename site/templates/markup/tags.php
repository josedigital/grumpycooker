<?php 

/**
 * Generate a list of tags A-Z
 * 
 * Used by the /site/templats/tags.php template file.
 *
 * VARIABLES: 
 * ==========
 * $tags PageArray of tags
 * 
 * Each of the tags has a numPosts property containing the number of posts used by the tag.
 *
 */

$lastLetter = '';
$out = '';
$letters = array();

foreach($tags as $tag) {
	$letter = strtoupper(substr($tag->title, 0, 1)); 
	if($letter != $lastLetter) {
		if($lastLetter) $out .= "</ul>";
		$out .= "<h3 id='letter_$letter'>$letter</h3>"; 
		$out .= "<ul class='tags posts-group'>";
		$letters[] = $letter; 
	}
	$lastLetter = $letter; 

	$numPosts = sprintf(_n('%d post', '%d posts', $tag->numPosts), $tag->numPosts);

	$out .= "<li><a href='{$tag->url}'>{$tag->title}</a> <span class='num-posts'>$numPosts</span></li>";
}

$out .= "</ul>";

echo "<section class='tags'>";
echo "<div class='container'><div class='small-12 med-8 med-push-2 columns'>";
echo "<p class='jumplinks'>";
foreach($letters as $letter) {
	echo "<a href='./#letter_$letter'>$letter</a> ";
}
echo "</p>";
echo $out; 
echo '</div></div></section><!-- /.tags -->';

