<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CitiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cities';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (isset($_GET['success'])) {
    ?>
    <?php $success = $_GET['success'] ?>
    <?php if ($success == 1) {
        ?>
        <p class="info-box bg-success">City has been deleted successfully!</p>
    <?php 
    } elseif ($success == -1) {
        ?>
        <?php $id = $_GET['id'] ?>
        <p class="info-box bg-danger">
            City cannot be deleted because of related User(s) exist(s)!
            <?php echo Html::a('View', Url::toRoute(['users/index', 'UsersSearch[city_id]' => $id]), ['target' => '_blank']) ?>
        </p>
    <?php 
    } ?>
<?php 
}?>
<div class="cities-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
        <?= Html::a('Create Cities', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php //Pjax::begin()?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'city',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php //Pjax::end()?>
</div>
