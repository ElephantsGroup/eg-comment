<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model elephantsGroup\contact\models\ContactUs */

$this->title = Yii::t('app', 'Create Comment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contact uses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
