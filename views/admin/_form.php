<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use elephantsGroup\comment\models\Comment;

/* @var $this yii\web\View */
/* @var $model elephantsGroup\contact\models\Comment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comment-form">

        <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'item_id')->textInput() ?>

    <?= $form->field($model, 'service_id')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList(Comment::getStatus(), ['prompt' => Yii::t('app', 'Select Status')]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
