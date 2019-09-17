<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;
use app\models\AppSession;

class LoginData extends Model
{
    public $user_id;
    public $username;
    public $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'on' => 'login'],
            [['user_id'], 'required', 'on' => 'logout']
        ];
    }

    public static function updateUser($data = array())
    {
        // set session
        $app_session         = new AppSession();
        $app_session->id     = $data->id;
        $app_session->expire = strtotime('+1 day', time());
        $app_session->DATA   = 'username = '.$data->username;
        $app_session->save();

        $return_user = array(
            'user_id' => $data->id,
            'email'   => $data->email,
        );

        return $return_user;
    }

}
?>