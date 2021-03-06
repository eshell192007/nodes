<?php
use yii\grid\GridView;
use yii\helpers\Html;
use app\modules\post\Module;
use app\modules\post\models\Post;
use app\modules\post\models\Comment;
use app\modules\post\models\Abuse;
use yii\widgets\Pjax;
use Miladr\Jalali\jDateTime;

$username   =   Yii::$app->user->getIdentity()->getUsername();
$pjax       =   Pjax::begin(['enablePushState'=>FALSE,'clientOptions' => ['method' => 'POST']]);
echo GridView::widget([
    'tableOptions' => [
        'class' => 'table table-striped table-hover fill-head'
    ],
    'headerRowOptions' => [
        'role' => 'row'
    ],
    'pager' => [
        'activePageCssClass' => 'active',
        'options' => [
            'class' => 'pagination pagination-colory'
        ]
    ],
    'dataProvider'      =>  $dataProvider,
    'rowOptions'        =>  function ($model, $key, $index, $grid){
        if ($model->status === Comment::STATUS_TRASH){
            return [
                'style'=> 'background-color:#d2d6de;'
            ];
        }
    },
    'columns'   =>  [
        [
            'attribute' =>  'user_id',
            'content'   =>  function ($model, $key, $index, $column) {
                $user       =   $model->user;
                $content    =   '<a href="'.Yii::$app->urlManager->createUrl("{$user->getUsername()}").'" data-pjax="0">';   
                $content    .=  '<img src="'.$user->getProfilePicture(60).'" alt="'.$user->getName().'" class="img-circle" style="width: 60px;height: 60px;margin-top: auto;">';
                $content    .=  '<span title="'.$user->tagline.'" style="padding-right:5px;">'.$model->user->getName().'</span></a>';
                return $content;
            },
            'enableSorting' =>  false
        ],
        [
            'attribute'     =>  'text',
            'content'       =>  function ($model, $key, $index, $column) {
                return \yii\helpers\HtmlPurifier::process($model->text);
            }
        ],
        [
            'attribute'     =>  'post_id',
            'enableSorting' =>  false,
            'content'       =>  function($model, $key, $index, $column){
                $post   =   $model->post;
                if ($post->status === Post::STATUS_PUBLISH){
                    $url = Yii::$app->urlManager->createUrl("{$post->user->getUsername()}/{$post->url}");
                    return '<a href="'.$url.'" target="_blank" data-pjax="0">'.$post->title.'</a>';
                } else {
                    $url = Yii::$app->urlManager->createUrl(['preview','id'=>  base_convert($post->id, 10, 36)]);
                    return '<a href="'.$url.'" target="_blank" data-pjax="0">'.$post->title.'</a>';                    
                }
            },
        ],
        [
            'attribute'     => 'created_at',
            'content' => function ($model, $key, $index, $column) {
                $post   =   $model->post;
                $content =  '<span class="small-font-size">' . jDateTime::date("l jS F Y h:i A", strtotime($model->created_at)) . '</span>';
                if ($post->status === Post::STATUS_PUBLISH && $model->status === Comment::STATUS_PUBLISH){
                    $uid    = md5($model->id);
                    $url = Yii::$app->urlManager->createUrl("{$post->user->getUsername()}/{$post->url}#comment{$uid}");
                    $content = '<a href="'.$url.'" target="_blank" data-pjax="0">'.$content.'</a>';
                }
                return $content;
            }            
        ],
        [
            'class'     => 'yii\grid\ActionColumn',
            'template'  => '{abuse}{trash}',
            'buttons'   =>  [
                'trash'     => function ($url, $model) use($pjax,$postId) {
                    if ($model->status != Comment::STATUS_TRASH) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['trash','id' => base_convert($model->id, 10, 36),'pid'=>  $postId], [
                                    'title'         =>  Module::t('comment', '_admin.btn.trash.title'),
                                    'data-pjax'     =>  $pjax->getId(),
                                    'style'         =>  'font-size:22px;padding-right:10px;',
                        ]);
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['trash','id' => base_convert($model->id, 10, 36),'pid'=>  $postId], [
                                    'title'         =>  Module::t('comment', '_admin.btn.untrash.title'),
                                    'data-pjax'     =>  $pjax->getId(),
                                    'style'         =>  'font-size:22px;padding-right:10px;',
                        ]);
                    }
                },
                'abuse'     => function ($url, $model) use($pjax,$postId) {
                    if (!Abuse::isCommentAbuseExist($model->id)){
                        return Html::a('<span class="glyphicon glyphicon-warning-sign"></span>', ['abuse','id' => base_convert($model->id, 10, 36),'pid'=>  $postId], [
                                    'title'         =>  Module::t('comment', '_admin.btn.abuse.title'),
                                    'data-pjax'     =>  $pjax->getId(),
                                    'style'         =>  'font-size:22px;padding-right:10px;',
                        ]);
                    }
                }, 
            ]
            
        ]
    ]]);
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