<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (isset($_GET['success'])) {
    ?>
    <?php $success = $_GET['success'] ?>
    <?php if ($success == 1) {
        ?>
        <p class="info-box bg-success">User has been deleted successfully!</p>
    <?php 
    } elseif ($success == -1) {
        ?>
        <?php $id = $_GET['id'] ?>
        <p class="info-box bg-danger">
            User cannot be deleted because of related P-U-R Relationship(s) exist(s)!
            <?php echo Html::a('View', Url::toRoute(['project-user/index', 'ProjectUserSearch[user_id]' => $id]), ['target' => '_blank']) ?>
        </p>
    <?php 
    } ?>
<?php 
}?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
        <?= Html::a('Create Users', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'fullname',
            'email:email',
            'city_id',
            [
                'label' => 'City Name',
                'value' => 'city.city',
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
