<?php

namespace elephantsGroup\comment\filters;

use yii\base\ActionFilter;
use yii\web\NotFoundHttpException;

class FrontendFilter extends ActionFilter
{
    public $controllers = ['admin'];

    public function beforeAction($action)
    {
        if (in_array($action->controller->id, $this->controllers)) {
            throw new NotFoundHttpException('Not found');
        }

        return parent::beforeAction($action);
    }
}
