<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Projects;
use app\models\Users;
use app\models\Roles;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Project-User-Role (P-U-R) Relationship';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (isset($_GET['success'])) {
    ?>
    <?php $success = $_GET['success'] ?>
    <?php if ($success == 1) {
        ?>
        <p class="info-box bg-success">P-U-R Relationship has been deleted successfully!</p>
    <?php 
    } elseif ($success == -1) {
        ?>
        <p class="info-box bg-danger">P-U-R Relationship cannot be deleted because of related P-U-R Relationship(s) exist(s)!</p>
    <?php 
    } ?>
<?php 
}?>
<div class="project-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
        <?= Html::a('Create P-U-R Relationship', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'project_id',
            'user_id',
            'role_id',
            [
                'label' => 'Project Name',
                'value' => function ($data) {
                    return Projects::findOne(['id' => $data->project_id])->name;
                },
            ],
            [
                'label' => 'Person',
                'value' => function ($data) {
                    return Users::findOne(['id' => $data->user_id])->fullname;
                },
            ],
            [
                'label' => 'Role',
                'value' => function ($data) {
                    return Roles::findOne(['id' => $data->role_id])->role;
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
