<?php

class TagController extends BaseController{

	public static function index() {
		$tags = Tag::fetchAll();
		Kint::dump($tags);
		View::make('tags/tag_index.html', array('tags' => $tags));
	}

	public static function tag_show($tag_id) {
		$algorithms = Algorithm::fetchByTag($tag_id);
		$tagName = Tag::fetchName($tag_id);
		View::make('tags/tag_show.html', array('algorithms' => $algorithms, 'tagName' => $tagName));
	}
}

