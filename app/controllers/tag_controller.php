<?php

class TagController extends BaseController{

	public static function index() {
		$tags = Tag::fetchAll();
		View::make('tags/tag_index.html', array('tags' => $tags));
	}

	public static function tag_show($tag_id) {
		$algorithms = Algorithm::fetchByTag($tag_id);
		$tagName = Tag::fetchName($tag_id);
		View::make('tags/tag_show.html', array('algorithms' => $algorithms, 'tag_id' => $tag_id, 'tagName' => $tagName));
	}

	public static function delete($tag_id){
		$tag = new Tag(array('id' => $tag_id));
		$tag->delete();
		Redirect::to('/tags/index', array('message' => 'Tag deleted successfully!'));
	}
}

