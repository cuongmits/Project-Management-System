<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projects-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            //'status',
            [
                'label' => 'Status',
                'value' => ($model->status === '0' ? 'Design' : ($model->status === '1' ? 'Development' : ($model->status === '2' ? 'Testing' : 'Completed'))),
            ],
        ],
    ]) ?>
    <?php 
    $purs = $model->projectUsers;
    if (count($purs)>0) {
        ?>
        <h3>Project's Users: </h4>
        <?php 
        foreach ($purs as $key => $pur) {
            ?>
            <?=
            DetailView::widget([
                'model' => $pur,
                'attributes' => [
                    [
                        'label' => '{ '.$key.' }',
                        'value' => '',
                    ],
                    [
                        'label' => 'Project',
                        'value' => $pur->project->name,
                    ],
                    [
                        'label' => 'User',
                        'value' => $pur->user->fullname,
                    ],
                    [
                        'label' => 'Role',
                        'value' => $pur->role->role,
                    ],
                ],
            ]) ?>
        <?php

        } ?>
    <?php

    } else {
        ?>
        <h3>There is no User for current Project!</h3>
    <?php

    } ?>
</div>
