<?php 
use yii\widgets\Pjax;
use Miladr\Jalali\jDateTime;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\post\Module;
/* @var $this \yii\web\View */
/* @var $post app\modules\post\models\Post */
/* @var $newComment \app\modules\post\models\Comment*/
/* @var $comments array */
/* @var $lastComment integer last comment timestamp*/
?>
<ul class="messages">
        <?php 
        $lastComment = 0;
        foreach ($comments as $comment):
            $deleteComment =   false;
            if ($comment->user_id === Yii::$app->user->getId() || $post->user_id === Yii::$app->user->getId()){
                Pjax::begin(['enablePushState'=>FALSE,'options' =>  ['class'=>'pjax'],'clientOptions' => ['method' => 'POST']]);
                $deleteComment  =   true;
            }
            $user           =   $comment->getUser()->one();
            $uid            =   md5($comment->id);
            $lastComment    =   strtotime($comment->created_at);
        ?>
        <li id="comment<?= $uid;?>">
            <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}"]) ?>" data-pjax="0">
                <img src="<?= $user->getProfilePicture(60);?>" alt="<?= $user->getName();?>">            
            </a>
            <div>
                <div>
                    <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}"]) ?>" data-pjax="0">
                        <h5><?= $user->getName();?></h5>
                    </a>                    
                    <span class="time"><i class="fa fa-clock-o"></i>
                        <a href="<?= Yii::$app->urlManager->createUrl(["{$post->user->getUsername()}/{$post->url}#comment{$uid}"]) ?>">
                            <?= jDateTime::date("l jS F Y H:i",$lastComment)?>
                        </a>
                    </span>
                    <?php
                        if ($deleteComment){
                            $form = ActiveForm::begin([
                                'action'=>Yii::$app->urlManager->createUrl(["{$user->getUsername()}/{$post->url}/comment/delete",'id'=>  base_convert($comment->id,10,36)]),
                                'enableClientValidation'=>true,
                                'options' => ['class'   =>  'form-horizontal','data-pjax'=>TRUE,'style'=>'display:none;'],
                            ]);
                            ActiveForm::end();
                            echo Html::a('', '#', [
                                'class'     =>  'fa fa-trash-o trash',
                                'onclick'   =>  'return false;',
                                'title'     =>  Module::t('post','comment.delete'),
                            ]);                            
                        }
                    ?>                    
                </div>
                <p>
                    <?= $comment->text;?>
                </p>
            </div>
        </li>
        <?php
        if ($deleteComment){
            Pjax::end();
        }
        endforeach;
        if (Yii::$app->user->isGuest){
            echo $this->render('_login_to_comment');
        } else {
            Pjax::begin(['enablePushState'=>FALSE,'options' =>  ['class'=>'pjax'],'clientOptions' => ['method' => 'POST']]);
            ?>
            <li>
                <img src="<?= Yii::$app->user->getIdentity()->getProfilePicture(60);?>" alt="<?= Yii::$app->user->getIdentity()->getName();?>">
                <div>
                    <?= $this->render('_comment_form',['post'=>$post,'newComment'=>$newComment,'timestamp'=>$lastComment]);?>
                </div>        
            </li>        
            <?php
            Pjax::end();
        }
    ?>
</ul>

<?php
$js         =   <<<JS
var pjax =   $("div.pjax");
pjax.on('pjax:send',function(){
    pjax.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
});
pjax.on('pjax:complete',function(){
    pjax.find('.overlay').remove();
});
$("div.pjax a.trash").on('click',function(){
    $(this).parent().find("form").submit();
});
JS;
$this->registerJs($js);
?>