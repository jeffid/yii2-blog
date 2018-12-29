<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="row">
    <div class="col-lg-9">
        <div class="panel-title box-title">
            <span>测试上传</span>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin() ?>
    
            <?= $form->field($model,'avatar')->fileInput() ?>
            
            <div class="form-group">
                <?= Html::submitButton('发布', ['class' => 'btn btn-success']) ?>
            </div>
            <?php $form = ActiveForm::end() ?>
        </div>
    </div>
    <div class="col-lg-3">
    </div>
</div>
