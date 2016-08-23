<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (isset($_GET['success'])) {
    ?>
    <?php $success = $_GET['success'] ?>
    <?php if ($success == 1) {
        ?>
        <p class="info-box bg-success">Project has been deleted successfully!</p>
    <?php 
    } elseif ($success == -1) {
        ?>
        <?php $id = $_GET['id'] ?>
        <p class="info-box bg-danger">
            Project cannot be deleted because of related P-U-R Relationship(s) exist(s)!
            <?php echo Html::a('View', Url::toRoute(['project-user/index', 'ProjectUserSearch[project_id]' => $id]), ['target' => '_blank']) ?>
        </p>
    <?php 
    } ?>
<?php 
}?>
<div class="projects-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
        <?= Html::a('Create Projects', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description:ntext',
            'status',
            [
                'label' => 'Status Explain',
                'value' => function ($data) {
                    if ($data->status == '0') {
                        return 'Design';
                    } elseif ($data->status == '1') {
                        return 'Development';
                    } elseif ($data->status == '2') {
                        return 'Testing';
                    } elseif ($data->status == '3') {
                        return 'Completed';
                    }
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
