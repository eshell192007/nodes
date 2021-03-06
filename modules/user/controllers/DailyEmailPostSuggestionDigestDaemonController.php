<?php
namespace app\modules\user\controllers;

use Yii;
use app\modules\user\Module;
use app\modules\user\models\User;
use app\modules\post\models\Post;
use yii\db\Query;
class DailyEmailPostSuggestionDigestDaemonController extends EmailPostSuggestionBase
{    
    public function actionIndex()
    {
        do
        {
            $timestamp      =   User::find()->where('status=:status AND reading_list=:reading_list',
                                            [':status'=>User::STATUS_ACTIVE,':reading_list'=>User::READING_LIST_DAILY])
                                            ->min('last_digest_mail');
            
            if ($timestamp === NULL){
                sleep(Module::DAY_SECONDS);
                Yii::$app->db->close();
                Yii::$app->db->open();                
                continue;
            }

            $diff   =   time() - strtotime($timestamp);
            
            if ($diff < Module::DAY_SECONDS){
                sleep(Module::DAY_SECONDS + Module::ADDITIONAL_SLEEP_SECS - $diff);
                Yii::$app->db->close();
                Yii::$app->db->open();
                continue;
            }            
            
            $users  = $this->getUsers($timestamp, User::READING_LIST_DAILY);
            
            foreach ($users as $user)
            {
                $posts  =   $this->getPosts($user->id);
                if (count($posts) >= 3)
                {
                    $this->sendDigestMail($user, $posts);
                    $this->setPostsAsSent($user, $posts);
                }
                $this->updateUserDigestTime($user);
            }
        } while(true);
    }    
}