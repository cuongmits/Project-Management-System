<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Projects;
use app\models\Users;
use app\models\Roles;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'project_id')->dropDownList(
            ArrayHelper::map(Projects::find()->all(), 'id', 'name'),
            ['prompt' => 'Choose Project']
            )->label('Project') ?>

    <?= $form->field($model, 'user_id')->dropDownList(
            ArrayHelper::map(Users::find()->all(), 'id', 'fullname'),
            ['prompt' => 'Choose User']
            )->label('User') ?>

    <?= $form->field($model, 'role_id')->dropDownList(
            ArrayHelper::map(Roles::find()->all(), 'id', 'role'),
            ['prompt' => 'Choose Role']
            )->label('Role') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
