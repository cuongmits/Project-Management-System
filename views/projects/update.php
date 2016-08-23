<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = 'Update Projects: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?php if (isset($msgs)) {
    ?>
    <p class="info-box bg-danger">
        <?php echo Html::encode($msgs['info']) ?>
    </p>
<?php 
} ?>
<div class="projects-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (isset($msgs)) {
    ?>
        <?= $this->render('_update_form', [
            'model' => $model,
            'pur_models' => $pur_models,
            'msgs' => $msgs,
        ]) ?>
    <?php 
} else {
    ?>
        <?= $this->render('_update_form', [
            'model' => $model,
            'pur_models' => $pur_models,
            ]) ?>
        <?php 
} ?>

</div>
