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





if($small == true) :
	if($config->ajax) {
    	$session->gridview = $input->post['gridview'];
    	// $session->gridview = 'fa fa-2x fa-th';
	}
	$gridview = ( $session->gridview == 'fa fa-2x fa-square' ) ? ' med-4' : '';
	// if( $session->gridview == 'fa fa-2x fa-square' ) {
	// 	$gridview = ' med-4';
	// } elseif ( $session->gridview == 'fa fa-2x fa-th' ) {
	// 	$gridview = '';
	// }

?>
<div class="small-12 columns gridv <?php echo getbgcolor($page); echo $gridview; ?>">
    <div class="recipe text-center" id='post-<?php echo $page->id; ?>'>
        <h3 class="font-bold uppercase"> <?php echo "<a href='{$page->url}'>{$page->title}</a>"; ?></h3>
		<?php 
		if($small) {
			echo "<p>" . $page->summary . "</p><p><a class='view inverse button font-bold uppercase' href='{$page->url}'>" . __('View Recipe') . "</a></p>";
		} else {
			echo $page->body; 
			// if the post has images and no <img> tags in the body, then make it a gallery
			if(count($page->images) && strpos($page->body, '<img ') === false) include("./gallery.php"); 
		}
		?>

		<?php if (count($page->images)) echo '<a href="'.$page->url.'"><img src="'. $page->images->url . '" alt="'.$page->images->description.'" class="'.getbgcolor($page).' recipe-image"></a>'; ?>
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
			echo "Time: <span class='font-bold'>{$page->time->title}</span>";
			echo "<br>";
			if(count($page->tags)) {
				$out = "<span>" . __('Tags:') . '</span> '; 
				foreach($page->tags as $tag) {
					$out .= "<span class='font-bold'><a href='{$tag->url}'>{$tag->title}</a></span>, ";
				}
				echo rtrim($out, ", ") . "</p> ";
			}
		?>
    </div>
</div><!-- /.12 .6 -->

<?php else : ?>


		<section class="recipe-page">
            <div class="container">
                <div class="small-12 med-8 med-push-2 columns">
                    <h2 class="font-bold uppercase"><?php echo $page->title; ?></h2>
                    <p><?php echo $page->body; ?></p>

                    <?php if (count($page->images)) echo '<img src="'. $page->images->url . '" alt="'.$page->images->description.'" class="'.getbgcolor($page).' recipe-image">'; ?>
                    
                    <h3 class="font-bold uppercase">Ingredients</h3>
                    <?php echo $page->ingredients; ?>

                    <h3 class="font-bold uppercase">Process</h3>
                    <?php echo $page->directions; ?>
                    
                </div>
            </div>
        </section>


		<section class="recommended">
            <div class="container">

            	<div class="small-12 columns">
					<h3 class="uppercase">you might also dig one of these...</h3>
				</div>
                
			        <?php
			        // get 3 similar posts
			        $tags = $page->tags->getRandom(3);
			        foreach ($tags as $tag) :
			        	$random = $pages->find("template=post,tags={$tag}"); 
			        	$rand = $random->getRandom();
			        ?>
			        	

						
					<div class="small-12 med-4 columns">
					    <div class="<?php echo getbgcolor($rand);?>  recipe" id='post-<?php echo $rand->id; ?>'>
					        <h3 class="font-bold uppercase"> <?php echo "<a href='{$rand->url}'>{$rand->title}</a>"; ?></h3>
					        <?php
					        $out = '';
								if(count($rand->categories)) {
									$out = "<p class='categories'><span>" . __('Level:') . '</span> ';
									foreach($rand->categories as $category) {
										$out .= "<span class='font-bold'><a href='{$category->url}'>{$category->title}</a></span>, ";	
									}
									echo rtrim($out, ", ");
								}
								echo "<br>";
								echo "Time: <span class='font-bold'>{$page->time->title}</span>";
								echo "<br>";
								if(count($rand->tags)) {
									$out = "<span>" . __('Tags:') . '</span> '; 
									foreach($rand->tags as $tag) {
										$out .= "<span class='font-bold'><a href='{$tag->url}'>{$tag->title}</a></span>, ";
									}
									echo rtrim($out, ", ") . "</p> ";
								}

								echo "<p>" . $rand->body . "</p><p><a class='view inverse button font-bold' href='{$rand->url}'>" . __('View Recipe') . "</a></p>";
							?>
							
					    </div>
					</div><!-- /.12 .4 -->


						



					<?php
			        endforeach;
			        ?>
				
			</div><!-- /.container -->
		</section>

<?php endif; ?>