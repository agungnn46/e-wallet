<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;
use app\models\BalanceBankHistory;

class BalanceBankHistoryData extends Model
{
    public $id;
    public $balance_bank_id;
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
            [['id', 'balance_bank_id', 'balance_before', 'balance_after'], 'integer'],
            [['activity', 'ip', 'location', 'user_agent', 'author'], 'string', 'max' => 200],
            [['balance_bank_id', 'balance_before', 'balance_after', 'activity', 'type', 'ip', 'location', 'user_agent', 'author'], 'required', 'on' => ['create', 'update']],
            [['type'], 'in', 'range' => ['credit', 'debit']],
        ];
    }

    public static function getBalanceBankHistory($data = array())
    {
        // if data not set => get all
        if(empty($data)){

            $balance_history = BalanceBankHistory::find()->all();

        }else{

            $balance_history = BalanceBankHistory::find()->where(['id' => $data['id']])->one();

        }

        return $balance_history;
    }

    public static function createBalanceBankHistory($data = array())
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $balance_history = new BalanceBankHistory();
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

    public static function updateBalanceBankHistory($data = array(), $id)
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $balance_history = BalanceBankHistory::find()->where(['id' => $id])->one();

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

    public static function deleteBalanceBankHistory($id)
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $balance_history = BalanceBankHistory::find()->where(['id' => $id])->one();

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