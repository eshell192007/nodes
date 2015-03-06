<?php

namespace app\modules\user;
use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace =   'app\modules\user\controllers';
    public $userTable           =   '{{%user}}';
    public $urlTable            =   '{{%url}}';
    public $tokenTable          =   '{{%token}}';
    public $followingTable      =   '{{%following}}';
    
    public $defaultRoute        =   'user';
    
    public $tokenLimitTime      =   86400;
    public function init()
    {
        parent::init();
        $this->registerTranslations();
        Yii::$app->setAliases(['@mail'=>'@app/themes/seven/views/user/views/mail']);
    }
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/user/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/user/messages',
            'fileMap' => [
                'modules/user/user'     => 'user.php',
                'modules/user/token'    => 'token.php',
                'modules/user/following'=> 'following.php'
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/user/' . $category, $message, $params, $language);
    }    
}