<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;
use app\models\UserBalance;

class UserBalanceData extends Model
{
    public $id;
    public $user_id;
    public $balance;
    public $balance_achieve;

    public function rules()
    {
        return [
            [['id'], 'required', 'on' => 'get-by-id'],
            [['user_id', 'balance', 'balance_achieve'], 'required', 'on' => ['create', 'update']],
            [['id', 'user_id', 'balance', 'balance_achieve'], 'integer']
        ];
    }

    public static function getUserBalance($data = array())
    {
        // if data not set => get all
        if(empty($data)){

            $user_balance = UserBalance::find()->all();

        }else{

            $user_balance = UserBalance::find()->where(['id' => $data['id']])->one();

        }

        return $user_balance;
    }

    public static function createUserBalance($data = array())
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $user_balance = new UserBalance();
        $user_balance->setAttributes($data);
        $user_balance->created_date = date('Y-m-d H:i:s');

        if(!$user_balance->validate()){
            $resp['status']  = false;
            $resp['message'] = current($user_balance->getErrors())[0];
        }else{
            $user_balance->save();
        }

        return $resp;

    }

    public static function updateUserBalance($data = array(), $id)
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $user_balance = UserBalance::find()->where(['id' => $id])->one();
        $user_balance->setAttributes($data);
        $user_balance->updated_date = date('Y-m-d H:i:s');

        if(!$user_balance->validate()){
            $resp['status']  = false;
            $resp['message'] = current($user_balance->getErrors())[0];
        }

        $user_balance->save();

        return $resp;

    }

    public static function deleteUserBalance($id)
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $user_balance = UserBalance::find()->where(['id' => $id])->one();

        if($user_balance){
            $user_balance->delete();
        }else{
            $resp['status']  = false;
            $resp['message'] = 'No Data Found';
        }

        return $resp;

    }

}
?>