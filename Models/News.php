<?php
namespace Models;

class News {
	
	public static function get($newsId = 0) {
	
		$news = [
			'1va',
			'2ra',
			'3ta',
		];
		
		if (!empty($newsId)) {
			return ['success' => 1, 'news' => $news[$newsId]];
		}
	
		return ['success' => 1, 'news' => $news];
	}
	public static function create() {
		return ['success' => 0, 'message' => 'ala bala'];
	}
	public static function update($id) {
		return ['success' => 0, 'message' => 'ala bala', $id];
	}
}