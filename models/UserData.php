<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;

class UserData extends Model
{
    public $id;
    public $name;
    public $username;
    public $password;
    public $confirm_password;
    public $email;

    public function rules()
    {
        return [
            [['id'], 'required', 'on' => 'get-by-id'],
            [['name', 'username', 'password', 'confirm_password', 'email'], 'required', 'on' => ['create', 'update']],
            [['name', 'username', 'confirm_password', 'email'], 'string', 'max' => 100],
            ['password', 'string', 'min' => 6, 'on' => ['create', 'update']],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message'=>"Passwords don't match", 'on'=> ['create', 'update']],
            [['email'],'email'],
        ];
    }

    public static function getUser($data = array())
    {
        // if data not set => get all
        if(empty($data)){

            $user = Users::find()->all();

            if(!empty($user)){
                $user = array_map(function($tag){
                    return [
                        "user_id"      => $tag['id'],
                        "name"         => $tag['name'],
                        "username"     => $tag['username'],
                        "email"        => $tag['email'],
                        "access_token" => $tag['access_token'],
                        "last_login"   => $tag['last_login']
                    ];
                }, $user);
            }

        }else{

            $user = Users::find()->where(['id' => $data['id']])->one();

            if(!empty($user)){
                $user = array(
                    "user_id"      => $user->id,
                    "name"         => $user->name,
                    "username"     => $user->username,
                    "email"        => $user->email,
                    "access_token" => $user->access_token,
                    "last_login"   => $user->last_login
                );
            }
        }

        return $user;
    }

    public static function createUser($data = array())
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $user               = new Users();
        $user->name         = $data['name'];
        $user->username     = $data['username'];
        $user->password     = Yii::$app->getSecurity()->generatePasswordHash($data['password']);
        $user->email        = $data['email'];
        $user->created_date = date('Y-m-d H:i:s');

        if(!$user->validate()){
            $resp['status']  = false;
            $resp['message'] = current($user->getErrors())[0];
        }else{
            $user->save();
        }

        return $resp;

    }

    public static function updateUser($data = array(), $id)
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $user = Users::find()->where(['id' => $id])->one();
        $user->setAttributes($data);
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($data['password']);

        if(!$user->validate()){
            $resp['status']  = false;
            $resp['message'] = current($user->getErrors())[0];
        }

        $user->save();

        return $resp;

    }

    public static function deleteUser($id)
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $user = Users::find()->where(['id' => $id])->one();

        if($user){
            $user->delete();
        }else{
            $resp['status']  = false;
            $resp['message'] = 'No Data Found';
        }

        return $resp;

    }

}
?>