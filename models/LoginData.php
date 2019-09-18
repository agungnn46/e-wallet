<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;
use app\models\AppSession;

class LoginData extends Model
{
    public $username;
    public $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'on' => 'login']
        ];
    }

    public static function updateUser($data = array())
    {
        // set session
        $app_session         = new AppSession();
        $app_session->id     = $data->access_token;
        $app_session->expire = strtotime('+1 day', time());
        $app_session->DATA   = 'username = '.$data->username;
        $app_session->save();

        $return_user = array(
            'name'         => $data->name,
            'username'     => $data->username,
            'access_token' => $data->access_token,
        );

        return $return_user;
    }

}
?>