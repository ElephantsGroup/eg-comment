<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel vendor\elephantsGroup\contact\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Comment');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'ip',
            'user_id',
            'email',
            'subject',
            'description',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return elephantsGroup\comment\models\Comment::getStatus()[$model->status];
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', elephantsGroup\comment\models\Comment::getStatus(), ['class' => 'form-control', 'prompt' => Yii::t('app', 'Select Status ...')])
            ],
            'update_time',
            'creation_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
