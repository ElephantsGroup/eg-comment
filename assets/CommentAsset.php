<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace elephantsGroup\comment\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * 
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 2.0
 */
class CommentAsset extends AssetBundle
{
    public $sourcePath = '@vendor/elephantsgroup/yii2-comment/assets';
   
    public function init() {
        $this->jsOptions['position'] = View::POS_END;
        parent::init();
    }

	public $css = [];
    public $js = [
        'js/bootstrap-notify.min.js'
	];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
