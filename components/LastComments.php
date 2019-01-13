<?php

namespace elephantsGroup\comment\components;

use Yii;
use elephantsGroup\comment\models\Comment;
use elephantsGroup\comment\models\CommentSearch;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class LastComments extends Widget
{
	public $language;
    public $number = 3;
    public $item;
		public $item_version;
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
	    $last_comment = Comment::find()->where(['status' => Comment::$_STATUS_ENABLED, 'item_id' => $this->item, 'service_id' => $this->service ])->orderBy(['creation_time' => SORT_DESC])->all();

			$parent_comment = Comment::find()
																->select('id')
																->where(['status' => Comment::$_STATUS_ENABLED, 'item_id' => $this->item, 'service_id' => $this->service, 'level' => 0 ])
																->orderBy(['creation_time' => SORT_DESC])
																->limit($this->number)
																->asArray()
																->all();

			foreach($parent_comment as $pc)
			{
				$parent_array[] = $pc['id'];
			}
        $this->_comments = [
            'items' => []
        ];
        $i = 1;
        foreach ($last_comment as $item)
        {
            if ($item->level == 0)
            {
                $i++;
								if (!isset($this->_comments['items'][$item->id]))
								{
									$this->_comments['items'][$item->id] = [
											'id' => $item->id,
											'name' => $item->name,
											'subject' => $item->subject,
											'description' => $item->description,
											'children' => []
									];
								}
								else
								{
										$this->_comments['items'][$item->id]['id'] = $item->id;
										$this->_comments['items'][$item->id]['name'] = $item->name;
										$this->_comments['items'][$item->id]['subject'] = $item->subject;
										$this->_comments['items'][$item->id]['description'] = $item->description;
								}
                if ($i > $this->number)
                    break;
            }
            else
            {
                // $this->_comments['items'][$item->comment_id]['children'][$item->id] =
								// [
                //     'id' => $item->id,
                //     'name' => $item->name,
                //     'subject' => $item->subject,
                //     'description' => $item->description,
                // ];
								if(in_array($item->comment_id, $parent_array) )
								{
									$reply = [
		                    'id' => $item->id,
		                    'name' => $item->name,
		                    'subject' => $item->subject,
		                    'description' => $item->description,
		                ];
										if (!isset($this->_comments['items'][$item->comment_id]))
											$this->_comments['items'][$item->comment_id]= [ 'children' => []];
										$children = $this->_comments['items'][$item->comment_id]['children'];

										$children = array_merge([$item->id => $reply] , $children);
										$this->_comments['items'][$item->comment_id]['children'] = $children;
								}
            }
        }

        return $this->render($this->view_file, [
            'last_comment' => $this->_comments,
            'item' => $this->item,
            'item_version' => $this->item_version,
            'service' => $this->service,
            'enabled_name' => $this->enabled_name,
            'enabled_subject' => $this->enabled_subject,
            'enabled_description' => $this->enabled_description,
        ]);
	}
}
