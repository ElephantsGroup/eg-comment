<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use elephantsGroup\comment\models\Comment;
/* @var $this yii\web\View */
/* @var $searchModel vendor\elephantsGroup\contact\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$module = \Yii::$app->getModule('comment');
$this->title = $module::t('comment', 'Comment');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


<?php
    $module = \Yii::$app->getModule('comment');
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        // 'ip',
        'name',
        // 'user_id',
        'email',
        'subject',
        // 'description',
        [
            'attribute' => 'status',
            'value' => function($model){
                return elephantsGroup\comment\models\Comment::getStatus()[$model->status];
            },
            'filter' => Html::activeDropDownList($searchModel, 'status', elephantsGroup\comment\models\Comment::getStatus(), ['class' => 'form-control', 'prompt' => Yii::t('app', 'Select Status ...')])
        ],
        // 'update_time',
        // 'creation_time',
        [
          'format' => 'raw',
          'label' => Yii::t('comment', 'Change Status'),
          'value' => function ($model) use($module)  {
              if ( $model->status == Comment::$_STATUS_CREATED)
              {
                return (Html::a($module::t('comment', 'Confirm'), ['/comment/admin/confirm', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]) .
                '/' . Html::a($module::t('comment', 'Deny'), ['/comment/admin/deny', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]));
              }
              elseif ($model->status == Comment::$_STATUS_CONFIRMED)
              {
                return(Html::a($module::t('comment', 'Deny'), ['/comment/admin/deny', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]) .
                '/' . Html::a($module::t('comment', 'Archive'), ['/comment/admin/archive', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]));
              }
              elseif ($model->status == Comment::$_STATUS_DENIED)
              {
                return (Html::a($module::t('comment', 'Confirm'), ['/comment/admin/confirm', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]) .
                '/' . Html::a($module::t('comment', 'Archive'), ['/comment/admin/archive', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]));
              }
              else
              {
                return (Html::a($module::t('comment', 'Confirm'), ['/comment/admin/confirm', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]) .
                '/' . Html::a($module::t('comment', 'Deny'), ['/comment/admin/deny', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]));
              }
          },
        ],
        ['class' => 'yii\grid\ActionColumn'],
    ];
    echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $columns,
        ]);
?>
</div>
