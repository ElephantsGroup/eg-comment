<?php

namespace elephantsGroup\comment\components;

use Yii;
use elephantsGroup\comment\models\Comment;
use elephantsGroup\comment\models\CommentSearch;
use yii\base\Widget;
use yii\helpers\Html;

class LastComments extends Widget
{
	public $language;
    public $number = 3;
    public $item;
    public $service;
    public $enabled_name = true;
    public $enabled_subject = true;
    public $enabled_description = true;
    public $view_file = 'last-comments';

    protected $_comments = [];
	public function init()
	{
		if(!isset($this->language) || !$this->language)
			$this->language = Yii::$app->language;
        if(!isset($this->view_file) || !$this->view_file)
            $this->view_file = Yii::t('comment', 'View File');
	}

    public function run()
	{
	    $last_comment = Comment::find()->where(['status' => Comment::$_STATUS_ENABLED, 'item_id' => $this->item, 'service_id' => $this->service ])->all();

        $this->_comments = [
            'items' => []
        ];
        $i = 1;
        foreach ($last_comment as $item)
        {
            if ($item->level == 0)
            {
                $i++;
                $this->_comments['items'][$item->id] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'subject' => $item->subject,
                    'description' => $item->description,
                    'childs' => []
                ];
                if ($i > $this->number)
                    break;
            }
            else
            {
                $this->_comments['items'][$item->comment_id]['childs'][$item->id] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'subject' => $item->subject,
                    'description' => $item->description,
                ];
            }

        }
        //var_dump($this->_comments['items']); die;
        return $this->render($this->view_file, [
            'last_comment' => $this->_comments,
            'item' => $this->item,
            'service' => $this->service,
            'enabled_name' => $this->enabled_name,
            'enabled_subject' => $this->enabled_subject,
            'enabled_description' => $this->enabled_description,
        ]);
	}
}