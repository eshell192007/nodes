<?php

namespace app\modules\post\models;

use Yii;
use app\modules\post\Module;
/**
 * This is the model class for table "{{%post}}".
 *
 * @property string $id
 * @property string $url
 * @property string $title
 * @property string $content
 * @property string $autosave_content
 * @property string $pin
 * @property string $comments_count
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $user_id
 *
 * @property User $user
 */
class Post extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT      =   'DRAFT';
    const STATUS_PUBLISH    =   'PUBLISH';
    const STATUS_TRASH      =   'TRASH';
    const STATUS_DELETE     =   'DELETE';
    const STATUS_WRITTING   =  'WRITTING';
    
    const PIN_ON            =   'ON';
    const PIN_OFF           =   'OFF';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->getModule('post')->postTable;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'title', 'content', 'user_id'], 'required'],
            [['content','autosave_content'], 'string'],
            [['comments_count', 'user_id'], 'integer'],
            [['url', 'title'], 'string', 'max' => 256],
            [['status'],'in','range'=>[self::STATUS_DELETE,self::STATUS_DRAFT,self::STATUS_PUBLISH,self::STATUS_TRASH,self::STATUS_WRITTING]],
            [['pin'],'in','range'   =>[self::PIN_ON,self::PIN_OFF]],            
            [['url'],'validateUniqueUrl']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'url' => Yii::t('app', 'Url'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'pin' => Yii::t('app', 'Pin'),
            'comments_count' => Yii::t('app', 'Comments Count'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    public function beforeValidate() {
        if (parent::beforeValidate()){
            if ($this->isNewRecord){
                $this->user_id  =   Yii::$app->user->id;
                $this->url      =   " asdasd";
                $this->title    =   " asdsad";
                $this->content  =   " asdasd";
            }
            return TRUE;
        }
        return FALSE;
    }

    public static function getLastUserWrittingPost()
    {
        return self::find()->where('status=:status AND user_id=:user_id',[':status'=>self::STATUS_WRITTING,'user_id'=>Yii::$app->user->id])
                            ->orderBy('updated_at')
                            ->limit(1)->one();
    }

    public static function draftAllWrttingPost()
    {
        return self::updateAll(['status'=>self::STATUS_DRAFT],'user_id=:user_id AND status=:status',[':user_id'=>Yii::$app->user->id,':status'=>self::STATUS_WRITTING]);
    }

    /**
     * 
     * @param string $title
     * @param string $id ID is base36 number
     * @return string
     */
    public static function suggestUniqueUrl($title,$id)
    {
        $title      =   strtolower($title);
        $postfix    =   strtolower($id);
        $url        =   NULL;
        $baseUrl    =   substr(preg_replace('/[[:space:]]+/', '-', $title),0,1500);
        $counter    =   0;
        do {
            if ($postfix === $id){
                $url        =   urlencode($baseUrl);    
            } else {
                $url        =   urlencode($baseUrl.$postfix);
            }
            
            /**
             * Similar Idea as Aloha (network algorith)
             */
            if ($counter <= 15){
                $number = base_convert($postfix, 36, 10);
            } else {
                $number =   base_convert($postfix, 36, 10);
                $number =   rand($number, ($number + $counter));
            }
            $counter++;
            $postfix= base_convert($number + 1, 10, 36);                
        } while(!self::isUniueUrl($url));
        return $url;
    }
    
    public static function isUniueUrl($url)
    {
        $model = Post::findOne(['user_id'=>Yii::$app->user->id,'url'=>  $url]);
        if ($model) {
            return false;
        }
        return true;
    }

    public function validateUniqueUrl($attribute,$params)
    {
        $post   =   Post::findOne(['user_id'=>Yii::$app->user->id,'url'=>  $this->url]);
        if ($post && ($this->isNewRecord || ($this->id != $post->id))){
            $this->addError($attribute, Module::t('post','post.vld.uniqueUrl'));
        }
    }

        /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}