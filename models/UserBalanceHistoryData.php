<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;
use app\models\UserBalanceHistory;

class UserBalanceHistoryData extends Model
{
    public $id;
    public $user_balance_id;
    public $balance_before;
    public $balance_after;
    public $activity;
    public $type;
    public $ip;
    public $location;
    public $user_agent;
    public $author;

    public function rules()
    {
        return [
            [['id'], 'required', 'on' => 'get-by-id'],
            [['type'], 'string'],
            [['id', 'user_balance_id', 'balance_before', 'balance_after'], 'integer'],
            [['activity', 'ip', 'location', 'user_agent', 'author'], 'string', 'max' => 200],
            [['user_balance_id', 'balance_before', 'balance_after', 'activity', 'type', 'ip', 'location', 'user_agent', 'author'], 'required', 'on' => ['create', 'update']],
            [['type'], 'in', 'range' => ['credit', 'debit']],
        ];
    }

    public static function getUserBalanceHistory($data = array())
    {
        // if data not set => get all
        if(empty($data)){

            $balance_history = UserBalanceHistory::find()->all();

        }else{

            $balance_history = UserBalanceHistory::find()->where(['id' => $data['id']])->one();

        }

        return $balance_history;
    }

    public static function createUserBalanceHistory($data = array())
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $balance_history = new UserBalanceHistory();
        $balance_history->setAttributes($data);
        $balance_history->created_date = date('Y-m-d H:i:s');

        if(!$balance_history->validate()){
            $resp['status']  = false;
            $resp['message'] = current($balance_history->getErrors())[0];
        }else{
            $balance_history->save();
        }

        return $resp;

    }

    public static function updateUserBalanceHistory($data = array(), $id)
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $balance_history = UserBalanceHistory::find()->where(['id' => $id])->one();

        if(!empty($balance_history)){
            $balance_history->setAttributes($data);
            $balance_history->updated_date = date('Y-m-d H:i:s');

            if(!$balance_history->validate()){
                $resp['status']  = false;
                $resp['message'] = current($balance_history->getErrors())[0];
            }else{
                $balance_history->save();
            }
        }else{
            $resp['status']  = false;
            $resp['message'] = 'No Data Found';
        }

        return $resp;

    }

    public static function deleteUserBalance($id)
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $balance_history = UserBalanceHistory::find()->where(['id' => $id])->one();

        if($balance_history){
            $balance_history->delete();
        }else{
            $resp['status']  = false;
            $resp['message'] = 'No Data Found';
        }

        return $resp;

    }

}
?>