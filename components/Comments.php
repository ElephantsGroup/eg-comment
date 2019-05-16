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
		$module = \Yii::$app->getModule('comment');

		if(!isset($this->language) || !$this->language)
			$this->language = Yii::$app->language;
        if(!isset($this->view_file) || !$this->view_file)
            $this->view_file = Yii::t('comment', 'View File');
		if(isset($module->comments['enabled_name']))
			$this->enabled_name = $module->comments['enabled_name'];
		if(isset($module->comments['enabled_subject']))
			$this->enabled_subject = $module->comments['enabled_subject'];
		if(isset($module->comments['enabled_description']))
			$this->enabled_description = $module->comments['enabled_description'];
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
