<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = 'Create Projects';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (isset($msgs)) {
    ?>
    <p class="info-box bg-danger">
        <?php echo Html::encode($msgs['info']) ?>
    </p>
<?php 
} ?>
<div class="projects-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (isset($pur_models)) {
    ?>
        <?= $this->render('_form', [
            'model' => $model,
            'pur_models' => $pur_models,
            'msgs' => $msgs,
        ]) ?>
    <?php 
} else {
    ?>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        <?php 
} ?>

</div>
