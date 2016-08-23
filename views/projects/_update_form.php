<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Users;
use app\models\Roles;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$urlAjaxAddUser = Url::to(['projects/ajax-add-user-to-update-project']);
$urlAjaxDeletePUR = Url::to(['projects/ajax-delete-user-from-project']);

$this->registerJs(<<< EOT_JS
    $(document).on('click', '#add_user', function(e) {
        var index = parseInt($('#index').val());
        $('#index').val(index+1);
        $.get(
            '{$urlAjaxAddUser}',
            {'index' : index},
            function(data) {
                $('#add_user_wrapper').append(data.html_script);
                $('#new_js_script').append(data.js_script);
            }
        );
        e.preventDefault();
    });
    $('#add_user_wrapper').on('click', '.add_user_item > a', function(){
        var i = 0;
        var inputs = new Array();
        $(this).parent().find(':input').each(function(){
            inputs[i++] = $(this).val();
        });
        var p = $(this).parent();
        var js_block_index = $(this).attr('val');
        if (i==3 && inputs[2]!=='') {
            $.ajax({
                type: "POST",
                data: {id : inputs[2]},
                url: "{$urlAjaxDeletePUR}",
                success: function(data){
                    if (data=='success') {
                        p.remove();
                        if (js_block_index !== 'undefined')
                            $('#'+js_block_index).remove();
                        //console.log('deleted');
                    } else {
                        alert('Error when trying to delete User from Project.');
                    }
                }
            });
        } else {
            p.remove();
            if (js_block_index !== 'undefined')
                $('#'+js_block_index).remove();
        } 
    });
EOT_JS
);
?>

<div class="projects-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList(
            ['Design', 'Development', 'Testing', 'Completed'],
            ['prompt' => 'Please choose Status']
            )->label('Status') ?>
    
    <div id="add_user_wrapper">
        <p>[Optional] More Users to Project: Click <a id="add_user" class="btn btn-default" href="#"><b>+ Add User</b></a></p>
        
        <p class="info-box bg-danger" id="add_user_message"></p>

            <?php $count = count($pur_models) ?>
            <input id="index" hidden="true" value="<?php echo $count?>">
            <?php foreach ($pur_models as $index => $pur_model) {
                ?>
                <div class='add_user_item'>
                    <h4>User { <?php echo $index ?> }</h4>
                    <?php if (isset($msgs['dup_keys']) && in_array($index, $msgs['dup_keys'])) {
                    ?>
                        <?= $form->field($pur_model, "[$index]user_id", ['options' => ['class' => 'has-error']])->dropDownList(
                                ArrayHelper::map(Users::find()->all(), 'id', 'fullname'),
                                ['prompt' => 'Choose User', 'name' => 'ProjectUser['.$index.'][user_id]']
                                )->label(false); ?>
                        <?= $form->field($pur_model, "[$index]role_id", ['options' => ['class' => 'has-error']])->dropDownList(
                                ArrayHelper::map(Roles::find()->all(), 'id', 'role'),
                                ['prompt' => 'Choose Role', 'name' => 'ProjectUser['.$index.'][role_id]']
                                )->label(false); ?>
                    <?php 
                } else {
                    ?>
                        <?= $form->field($pur_model, "[$index]user_id")->dropDownList(
                                ArrayHelper::map(Users::find()->all(), 'id', 'fullname'),
                                ['prompt' => 'Choose User', 'name' => 'ProjectUser['.$index.'][user_id]']
                                )->label(false); ?>
                        <?= $form->field($pur_model, "[$index]role_id")->dropDownList(
                                ArrayHelper::map(Roles::find()->all(), 'id', 'role'),
                                ['prompt' => 'Choose Role', 'name' => 'ProjectUser['.$index.'][role_id]']
                                )->label(false); ?>
                    <?php 
                } ?>
                    <?= $form->field($pur_model, "[$index]id")->input('hidden', ['name' => 'ProjectUserIndex[]'])->label(false); ?>
                    <a class='btn btn-danger' href='#'>Cancel</a>
                </div>
            <?php 
            } ?>    
    </div>
    
    <div id="new_js_script"></div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
