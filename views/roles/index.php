<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roles';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (isset($_GET['success'])) {
    ?>
    <?php $success = $_GET['success'] ?>
    <?php if ($success == 1) {
        ?>
        <p class="info-box bg-success">Role has been deleted successfully!</p>
    <?php 
    } elseif ($success == -1) {
        ?>
        <?php $id = $_GET['id'] ?>
        <p class="info-box bg-danger">
            Role cannot be deleted because of related Project(s) exist(s)! 
            <?php echo Html::a('View', Url::toRoute(['project-user/index', 'ProjectUserSearch[role_id]' => $id]), ['target' => '_blank']) ?>
        </p>
    <?php 
    } ?>
<?php 
}?>
<div class="roles-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
        <?= Html::a('Create Roles', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'role',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
