<?php use yii\widgets\Pjax;use yii\helpers\Html; ?>
<div id="loginmodal" class="modal fade" tabindex="-1" role="dialog" aria-label="loginmodalLabel" aria-hidden="true">
    <button class="toggleclose" data-dismiss="modal" aria-hidden="true">
        <span class="icon-close"></span>
    </button>

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row center-block">
                <h1><?php echo Yii::t('app','login.social'); ?></h1>
                <ul class="socialicons motion">
                    <li class="six animated fadeInUp">
                        <a href="<?= Yii::$app->urlManager->createUrl(['user/user/auth','authclient'=>'google']); ?>" title="<?= Yii::t('app', 'oauth.google'); ?>"  class="twitter" target="_blank">
                            <span class="icon-google-plus"></span>
                        </a>
                    </li>
                    <li class="eight animated fadeInUp">
                        <a href="<?= Yii::$app->urlManager->createUrl(['user/user/auth','authclient'=>'linkedin']); ?>" title="<?= Yii::t('app', 'oauth.linkedin'); ?>"  class="twitter" target="_blank">
                            <span class="icon-linkedin"></span>
                        </a>
                    </li>                                                                     
                    <li class="ten animated fadeInUp">
                        <a href="<?= Yii::$app->urlManager->createUrl(['user/user/auth','authclient'=>'facebook']); ?>" title="<?= Yii::t('app', 'oauth.facebook'); ?>"   class="twitter" target="_blank">
                            <span class="icon-facebook"></span>
                        </a>
                    </li>                                        
                    <li class="twelve animated fadeInUp">
                        <a href="<?= Yii::$app->urlManager->createUrl(['user/user/auth','authclient'=>'twitter']); ?>" title="<?= Yii::t('app', 'oauth.twitter'); ?>" class="twitter" target="_blank">
                            <span class="icon-twitter"></span>
                        </a>
                    </li>                                                 
                    <li class="fourteen animated fadeInUp">
                        <a href="<?= Yii::$app->urlManager->createUrl(['user/user/auth','authclient'=>'github']); ?>" title="<?= Yii::t('app', 'oauth.github'); ?>"  class="twitter" target="_blank">
                            <span class="icon-github"></span>
                        </a>
                    </li>                    
                </ul>                    
                    <?php
                    $pjax = new Pjax();
                    $pjax->begin(['enablePushState'=>FALSE]);
                    ?>
                    <div id="login-box" style="font-size: 20px;">
                        
                    </div>                    
                    <?php $pjax->end();?>
                    <div class="row">
                        <div class="col-lg-4 col-lg-offset-4 medium-font-size">
                            <?= Html::a(Yii::t('app', 'link.join'),  Yii::$app->urlManager->createUrl(['user/user/join'])); ?>/
                            <?= Html::a(Yii::t('app', 'link.reset'),  Yii::$app->urlManager->createUrl(['user/user/reset'])); ?>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$action = Yii::$app->urlManager->createAbsoluteUrl(['user/user/login']);

$js = <<<JS
        
var pjaxDiv =   $("#{$pjax->getId()}");
pjaxDiv.on('pjax:send',function(){
    pjaxDiv.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
});
pjaxDiv.on('pjax:complete',function(){
    pjaxDiv.find('.overlay').remove();
});        
$.get("{$action}").done(function(data){
    $("#login-box").html(data);
});
$.fn.modal.Constructor.prototype.checkScrollbar = function () {}
$.fn.modal.Constructor.prototype.setScrollbar = function () {}
$.fn.modal.Constructor.prototype.resetScrollbar = function(){}
JS;
$this->registerJs($js);
?>