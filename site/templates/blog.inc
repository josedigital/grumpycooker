<?php

/**
 * Blog Site Profile Functions
 *
 * Copyright 2012 by Ryan Cramer
 *
 * VARIABLES:
 * ==========
 * $content 	- Center column content
 * $headline 	- Page headline	
 * $subnav	- Subnavigation content (left sidebar) 
 * 
 * FUNCTIONS: 
 * ==========
 * findComments($selector); 
 * findRecentComments($limit, $start, $admin); 
 * formatDate($date); 
 * renderPosts($posts, $small);
 * renderComments($comments, $limit);
 * renderRSS($items, $title, $description); 
 * renderNav($headline, $items, $currentURL); 
 * 
 *
 */


/**
 * Initialize some variables that template files will populate and main.inc will output.
 * 
 */
$content = ''; 	// center column content
$headline = ''; // main headline
$subnav = ''; 	// subnav, as it appears in the left sidebar


/**
 * Find comments from the given selector string
 *
 * @param string $selector
 * @return CommentArray
 *
 */
function findComments($selector) {
	$comments = FieldtypeComments::findComments('comments', $selector); 
	foreach($comments as $comment) {
		if(!$comment->page->viewable()) $comments->remove($comment);
	}
	return $comments; 
}

/**
 * Find $limit recent comments
 *
 * @param int $limit Number of recent comments to find
 * @param int $start Where to start, like 0 (default: null = automatic, based on page number)
 * @param bool $admin Include non-approved and spam comments? (default: null = determine automatically)
 * @return CommentArray
 *
 */
function findRecentComments($limit = 3, $start = null, $admin = null) {

	$limit = (int) $limit; 
	$_limit = is_null($start) ? $limit : $limit+1;
	$out = '';
	$pageNum = wire('input')->pageNum; 

	// auto-determine $start if not specified
	if(is_null($start)) {
		if($pageNum > 1) $start = $pageNum * $limit; 
			else $start = 0;
	}

	// we show pending and spam comments when page is editable
	if(is_null($admin)) $admin = wire('page')->editable();

	// build selector to locate comments
	$selector = "limit=$_limit, start=$start, sort=-created, ";

	if($admin) $selector .= "status>=" . Comment::statusSpam . ", ";
		else $selector .= "status>=" . Comment::statusApproved . ", ";

	// find the comments we want to output
	$comments = findComments($selector);

	return $comments; 
}

/**
 * Given a PageArray of blog entries generate and return the output.
 *
 * @param PageArray|Page $posts The entries to generate output for
 * @param bool $small Set to true if you want summarized versions (default = false)
 * @return string The generated output
 *
 */
function renderPosts($posts, $small = false) {

	if(!$posts instanceof PageArray) {

		if($posts instanceof Page) {
			// single page
			$post = $posts; 
			$posts = new PageArray();
			$posts->add($post); 

		} else if(is_string($posts)) {
			// selector string
			$selector = $posts; 
			$posts = wire('pages')->find("template=post, sort=-date, $selector"); 

		} else {
			throw new WireException('renderPosts requires a PageArray, Page or selector string'); 
		}
	}

	foreach($posts as $page) {

		if(empty($page->summary)) {
			// summary is blank so we auto-generate a summary from the body
			$summary = strip_tags(substr($page->body, 0, 1450));
			// $page->summary = substr($summary, 0, strrpos($summary, ' '));
			$page->summary = $summary;
		}

		// set a couple new fields that our output will use
		$page->set('authorName', $page->createdUser->get('title|name')); 
		$page->set('authorURL', wire('config')->urls->root . 'authors/' . $page->createdUser->name . '/'); 
	}

	$t = new TemplateFile(wire('config')->paths->templates . '/markup/posts.php'); 
	$t->set('posts', $posts); 
	$t->set('small', $small); 
	$out = $t->render();

	// if there are more posts than the specified limit, then output pagination
	if($posts->getLimit() < $posts->getTotal()) 
		$out .= '<div class="pagination container"><div class="small-12 columns">';
		$out .= $posts->renderPager();
		$out .= '</div></div>';
	
	return $out;	
}

/**
 * Render a list of comments
 *
 * If page is editable, then non-approved comments will be included (and identified) in the list.
 *
 * @param CommentArray $comments
 * @param int $limit Optional limit of max comments to show
 * @return string
 *
 */
function renderComments(CommentArray $comments, $limit = 0) {

	$out = '';
	$page = wire('page');
	$admin = $page->editable(); 
	$cnt = 0;
	$pageNum = wire('input')->pageNum;
	$commentArray = new CommentArray();
	$prevURL = '';
	$nextURL = '';

	// check if we should introduce pagination
	if($limit && (count($comments) > $limit || $pageNum > 1)) {

		if($pageNum > 2) $prevURL = $page->url . 'page' . ($pageNum-1);
			else if($pageNum > 1) $prevURL = $page->url;
			else $prevURL = '';

		if(count($comments) > $limit) $nextURL = $page->url . 'page' . ($pageNum+1);
			else $nextURL = '';
	}

	// setup comments for output, copying all comments we will display into $commentArray
	foreach($comments as $comment) { 
		if(!$admin && $comment->status != Comment::statusApproved) continue; 
		$comment->date = formatDate($comment->created); 
		$commentArray->add($comment); 
		$cnt++;
		if($limit && $cnt >= $limit) break;
	} 

	$t = new TemplateFile(wire('config')->paths->templates . 'markup/comments.php'); 
	$t->set('page', $page); 
	$t->set('admin', $admin);
	$t->set('comments', $commentArray);
	$t->set('nextURL', $nextURL);
	$t->set('prevURL', $prevURL); 

	return $t->render();
}

/**
 * Return a date formatted as specified in the blog 'date' field
 *
 * @param int|string $date If given a timestamp, it will be automatically formatted according to the 'date' field in PW
 *	If given a string, then whatever format it is in will be kept. 
 * @return string
 *
 */
function formatDate($date) {

	if(is_int($date)) {
		// get date format from our 'date' field, for consistency
		$dateFormat = wire('fields')->get('date')->dateOutputFormat; 
		$date = FieldtypeDatetime::formatDate($date, $dateFormat);
	}

	return $date; 
}


/**
 * Render an RSS feed
 *
 * Note that you should not output anything further after calling this, as it outputs the RSS directly.
 *
 * @param PageArray $items Pages to include in the feed
 * @param string $title Title of the feed. If not provided, pulled from current page.
 * @param string $description Description of the feed. If not provided, pulled from current page.
 *
 */
function renderRSS(PageArray $items, $title = '', $description = '') {

	$page = wire('page');
	if(empty($title)) $title = $page->get('headline|title') . ' - ' . wire('pages')->get('/')->headline;
	if(empty($description)) $description = $page->get('summary|meta_description');

	$rss = wire('modules')->get('MarkupRSS');
	$rss->title = $title;
	$rss->description = $description;
	$rss->itemDescriptionField = 'body';
	$rss->itemDescriptionLength = 0; // no maxlength
	$rss->itemDateField = 'date';
	$rss->render($items);
}


/**
 * Render secondary navigation 
 *
 * @param string $headline Headline to display above nav
 * @param array|PageArray $items May be an array of Page objects or array of ($url => $label)
 * @param Page|string $currentURL Current item that should be highlighted, may be Page or $url
 * @param bool $mobileSelect Replace the nav with a <select> when at mobile width?
 * @return string
 *
 */
function renderNav($headline, $items, $currentURL = '') {

	if(!count($items)) return '';

	if($currentURL instanceof Page) {
		$currentPage = $currentURL;
		$currentURL = $currentPage->url;
	} else {
		$currentPage = null;
	}

	$nav = array();

	foreach($items as $url => $title) {

		if($title instanceof Page) {
			$page = $title;
			$title = $page->title;
			$url = $page->url;
			$cls = $page->name;

			// if nav item is root-level and $currentPage shares this root parent, then 
			// make it the currentURL so that it is still highlighted in the nav
			if($page->parent_id == 1 && $currentPage && $currentPage->rootParent === $page) 
				$currentURL = $currentPage->rootParent->url;
		} 

		$nav[] = ["url"=>$url, "title"=>$title, "cls"=>$cls];
	}

	$t = new TemplateFile(wire('config')->paths->templates . 'markup/nav.php'); 
	$t->set('items', $nav); 
	$t->set('currentURL', $currentURL); 
	$t->set('headline', $headline); 

	return $t->render();
}






function getbgcolor($page) {

	switch ($page->bgcolor) {
		case '1':
			return $color = 'dark-purple';
			break;
		
		case '2':
			return $color = 'light-purple';
			break;

		case '3':
			return $color = 'blue';
			break;

		case '4':
			return $color = 'dark-green';
			break;

		case '5':
			return $color = 'light-green';
			break;

		case '6':
			return $color = 'yellow';
			break;

		case '7':
			return $color = 'orange';
			break;

		case '8':
			return $color = 'pink';
			break;

		default:
			return $color = 'red';
			break;
	}

}






function listchildren($page) {
	$out = '<ul>';
	foreach ($page->children as $child) {
		$out .= "<li><p><a href='{$child->url}'>{$child->title}</a><br>";
		if($child->headline) $out .= "{$child->headline}</p></li>";
	}
	$out .= '</ul>';
	return $out;
}