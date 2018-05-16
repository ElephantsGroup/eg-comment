<?php

namespace elephantsGroup\comment\filters;

use yii\base\ActionFilter;
use yii\web\NotFoundHttpException;

class BackendFilter extends ActionFilter
{
    public $controllers = ['ajax'];

    public function beforeAction($action)
    {
        if (in_array($action->controller->id, $this->controllers)) {
            throw new NotFoundHttpException('Not found');
        }

        return parent::beforeAction($action);
    }
}
