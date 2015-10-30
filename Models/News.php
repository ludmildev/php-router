<?php
namespace Models;

class News {
	
	public static function get($newsId = 0)
	{
		$db = new \Lib\Db\Db();
		$success = 1;
		$message = '';

		if (empty($newsId))
		{
			$news = $db->prepare('
				SELECT * FROM news WHERE 1
			')->execute()->fetchAllAssoc();
		}
		else
		{
			$news = $db->prepare('
				SELECT * FROM news WHERE id = ?
			', [$newsId])->execute()->fetchAllAssoc();
		}
		
		if (empty($news)) {
			$success = 0;
			$message = 'News Not Found';
		}
	
		return ['success' => $success, 'news' => $news, 'message' => $message];
	}

	public static function create() {
		return ['success' => 0, 'message' => 'ala bala'];
	}
	public static function update($id) {
		return ['success' => 0, 'message' => 'ala bala', $id];
	}
}