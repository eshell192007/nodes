<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use app\modules\user\models\Following;
use app\modules\post\Module;
/* @var $this \yii\web\View */
/* @var $user app\modules\user\models\User */
if(Yii::$app->user->id != $user->id){
    $pjax = Pjax::begin(['enablePushState'=>FALSE]);
    $form = ActiveForm::begin([
        'id'                    => 'follow-form',
        'action'                =>  Yii::$app->urlManager->createUrl(['user/follow']),
        'options'               => ['class'   =>  'form-horizontal','data-pjax'=>true],
        'fieldConfig' => [
            'template'  =>  '{input}'
        ],
    ]);    
    if (Following::isUserFollowing($user->id)){
        $btnTitle = Module::t('post','_follow.unfollow');
    } else {
        $btnTitle = Module::t('post','_follow.follow');
    }
    echo Html::hiddenInput('username', $user->username);
    echo Html::submitButton($btnTitle, ['class' => 'btn btn-default bg-olive margin']);
    ActiveForm::end();
    Pjax::end();
$js         =   <<<JS
$("#{$pjax->getId()}").on('pjax:send',function(){
    $("#{$pjax->getId()}").append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
});
$("#{$pjax->getId()}").on('pjax:complete',function(){
    $("#{$pjax->getId()}").find('.overlay').remove();
});
JS;
$this->registerJs($js);    
} else {
    echo Html::a(Module::t('post','_follow.editMyProfile'), ['/user/profile'], ['class' => 'btn btn-default bg-olive margin']);
}
?>