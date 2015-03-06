<?php 
use app\modules\user\Module;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\Token */
?>
<div class="top-buffer"></div>
<div class="row">   
    <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
        <div class="box box-primary">
            <div class="box-header text-center">
                <?= Module::t('token', 'password.header'); ?>
            </div>
            <div class="box-body">
                <hr class="mini central">
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'password-form',
                        'options' => ['class'   =>  'form-horizontal'],
                        'fieldConfig' => [
                            'template'  =>  '{input}{error}'
                        ],
                    ]); 
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <div class="controls">
                                    <div class="col-md-12 controls">
                                        <?= $form->field($model,'password')
                                                ->passwordInput(['placeholder'=> Module::t('token', 'rstPwd.attr.confirmPassword'),'style'=>'direction: ltr;']); ?>

                                        <span class="glyphicon glyphicon-lock form-control-feedback" style="right: 5px;"></span>                        
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-feedback">
                                <div class="controls">
                                    <div class="col-md-12 controls">
                                        <?= $form->field($model,'confirmPassword')
                                                ->passwordInput(['placeholder'=> Module::t('token', 'rstPwd.attr.confirmPassword'),'style'=>'direction: ltr;']); ?>

                                        <span class="glyphicon glyphicon-lock form-control-feedback" style="right: 5px;"></span>                        
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <div class="col-md-12 controls">
                                        <?= Html::submitButton(Module::t('token','password.changePasswordBtn'), ['class' => 'btn btn-primary btn-block']) ?>
                                    </div>
                                </div>            
                            </div>
                        </div>
                    </div>

                    <?php ActiveForm::end();?>
            </div>
        </div>
    </div>    
</div>