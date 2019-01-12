<?php

namespace elephantsGroup\comment\components;

use Yii;
use elephantsGroup\comment\models\Comment;
use elephantsGroup\comment\models\CommentSearch;
use yii\base\Widget;
use yii\helpers\Html;

class Comments extends Widget
{
	public $language;
    public $service;
    public $item;
    public $item_version;
    public $enabled_name = true;
    public $enabled_subject = true;
    public $enabled_description = true;

    public $view_file = 'comments';

	public function init()
	{
		if(!isset($this->language) || !$this->language)
			$this->language = Yii::$app->language;
        if(!isset($this->view_file) || !$this->view_file)
            $this->view_file = Yii::t('comment', 'View File');
	}

    public function run()
	{
        return $this->render($this->view_file, [
            'item' => $this->item,
            'item_version' => $this->item_version,
            'service' => $this->service,
            'enabled_name' => $this->enabled_name,
            'enabled_subject' => $this->enabled_subject,
            'enabled_description' => $this->enabled_description,
        ]);
	}
}
