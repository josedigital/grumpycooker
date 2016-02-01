<?php

/**
 * Output a single blog post 
 *
 * Used by /site/templates/markup/posts.php (via include)
 *
 * VARIABLES:
 * ==========
 * $page - the blog post to render (Page)
 * $small - whether to display it in a small/summarized format (boolean)
 *
 */

$numComments = $page->comments->count();
if($numComments > 0) $numCommentsStr = sprintf(_n('%d Comment', '%d Comments', $numComments), $numComments);
	else $numCommentsStr = __('No comments yet'); 

switch ($page->bgcolor) {
	case '1':
		$color = 'orange';
		break;
	
	case '2':
		$color = 'light-purple';
		break;

	case '3':
		$color = 'dark-purple';
		break;

	case '4':
		$color = 'light-green';
		break;

	case '5':
		$color = 'dark-green';
		break;

	case '6':
		$color = 'pink';
		break;

	default:
		$color = 'pink';
		break;
}



if($small == true) :
?>
<div class="small-12 med-6 columns">
    <div class="<?php echo $color;?> recipe" id='post-<?php echo $page->id; ?>'>
        <h3 class="font-bold uppercase"> <?php echo "<a href='{$page->url}'>{$page->title}</a>"; ?></h3>
        <?php
        $out = '';
			if(count($page->categories)) {
				$out = "<p class='categories'><span>" . __('Level:') . '</span> ';
				foreach($page->categories as $category) {
					$out .= "<span class='font-bold'><a href='{$category->url}'>{$category->title}</a></span>, ";	
				}
				echo rtrim($out, ", ");
			}
			echo "<br>";
			echo "Time: <span class='font-bold'>15 min</span>";
			echo "<br>";
			if(count($page->tags)) {
				$out = "<span>" . __('Tags:') . '</span> '; 
				foreach($page->tags as $tag) {
					$out .= "<span class='font-bold'><a href='{$tag->url}'>{$tag->title}</a></span>, ";
				}
				echo rtrim($out, ", ") . "</p> ";
			}
		?>
		<?php 
		if($small) {
			echo "<p>" . $page->summary . "&hellip; <a class='more' href='{$page->url}'>" . __('View More') . "</a></p>";
		} else {
			echo $page->body; 
			// if the post has images and no <img> tags in the body, then make it a gallery
			if(count($page->images) && strpos($page->body, '<img ') === false) include("./gallery.php"); 
		}
		?>
    </div>
</div>

<?php else : ?>


		<section class="recipe-page">
            <div class="container">
                <div class="small-12 med-8 med-push-2 columns">
                    <h2 class="font-bold uppercase"><?php echo $page->title; ?></h2>
                    <p><?php echo $page->summary; ?></p>

                    <?php if (count($page->images)) echo '<img src="'. $page->images->url . $page->images .'" alt="">'; ?>
                    <h3 class="font-bold uppercase">Ingredients</h3>
                    <?php echo $page->ingredients; ?>
                    <h3 class="font-bold uppercase">Process</h3>
                    <?php echo $page->directions; ?>
                </div>
            </div>
        </section>


<?php endif; ?>