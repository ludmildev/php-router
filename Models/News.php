<?php
namespace Models;

class News {

	public static function get($newsId = 0)
	{
		$db = new \Lib\Db\Db();
		$result = ['success' => 1, 'message' => ''];

		if (empty($newsId))
		{
			$news = $db->prepare('
				SELECT
                    `id`, `title`, `date`, `text`
                FROM news WHERE 1
			')->execute()->fetchAllAssoc();
		}
		else
		{
			$news = $db->prepare('
				SELECT
                    `id`, `title`, `date`, `text`
                FROM news WHERE id = ?
			', [(int)$newsId]
            )->execute()->fetchAllAssoc();
		}

		if (empty($news)) {
			$result['success'] = 0;
			$result['message'] = 'News Not Found';
            return $result;
		}

        $result['news'] = $news;

		return $result;
	}

	public static function create()
    {
        $db     = new \Lib\Db\Db();
        $title  = \Lib\Input::get('post', 'title', '', 'trim');
        $text   = \Lib\Input::get('post', 'text', '', 'trim');
        $date   = \Lib\Input::get('post', 'date', '');

        $result = ['success' => 1, 'message' => ''];

        $validate = \Models\News::validateNewsInput($title, $text, $date);

        if ($validate['success'] == 0) {
            $result['success'] = 0;
            $result['message'] = $validate['message'];
            return $result;
        }
        
        $lastInsertedId = $db->prepare('
            INSERT INTO `news` (
                `title`, `date`, `text`
            ) VALUES (
                ?, ?, ?
            )
        ', [$title, $date, $text])->execute()->getLastInsertId();

        $result['id']       = $lastInsertedId;
        $result['title']    = $title;
        $result['text']     = $text;
        $result['date']     = $date;

        return $result;
	}

	public static function update($id = 0)
    {
        
        $db     = new \Lib\Db\Db();
        $title  = \Lib\Input::get('put', 'title', '', 'trim');
        $text   = \Lib\Input::get('put', 'text', '', 'trim');
        $date   = \Lib\Input::get('put', 'date', '');
        
        $result = ['success' => 1, 'message' => ''];
        
        if (empty($id)) {
            $result['success'] = 0;
            $result['message'] = 'Invalid news id';
            return $result;
        }
        
        $validate = \Models\News::validateNewsInput($title, $text, $date);

        if ($validate['success'] == 0) {
            $result['success'] = 0;
            $result['message'] = $validate['message'];
            return $result;
        }
        
        $db->prepare('
            UPDATE `news` SET
                `title` = ?,
                `date` = ?,
                `text` = ?
            WHERE `id` = ?
        ', [$title, $date, $text, (int)$id])->execute();
        
        return $result;
	}
    
    public static function delete($id = 0)
    {
        $db     = new \Lib\Db\Db();
        $result = ['success' => 1, 'message' => ''];
        
        if (empty($id)) {
            $result['success'] = 0;
            $result['message'] = 'Invalid news id';
            return $result;
        }
        
        $db->prepare('
            DELETE FROM `news` WHERE `id` = ?
        ', [(int)$id])->execute();
        
        return $result;
    }
    
    private static function validateNewsInput($title, $text, $date)
    {
        $result = ['success' => 1, 'message' => ''];

        if (empty($title)) {
            $result['success'] = 0;
			$result['message'] = 'Title cannot be empty';
            return $result;
        }
        if (empty($text)) {
            $result['success'] = 0;
			$result['message'] = 'Text cannot be empty';
            return $result;
        }
        if (empty($date)) {
            $result['success'] = 0;
			$result['message'] = 'Date cannot be empty';
            return $result;
        }
        if (!\Lib\Input::validateDatetime($date)) {
            $result['success'] = 0;
			$result['message'] = 'Invalid date';
            return $result;
        }
        
        return $result;
    }
}