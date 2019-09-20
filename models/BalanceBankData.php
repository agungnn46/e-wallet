<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;
use app\models\BalanceBank;

class BalanceBankData extends Model
{
    public $id;
    public $balance;
    public $balance_achieve;
    public $code;
    public $enable;

    public function rules()
    {
        return [
            [['id'], 'required', 'on' => 'get-by-id'],
            [['balance', 'balance_achieve', 'code', 'enable'], 'required', 'on' => ['create', 'update']],
            [['id', 'balance', 'balance_achieve'], 'integer'],
            [['enable'], 'match', 'pattern' => '/^[0-1]*$/', 'message' => '{attribute} value only 1 or 0'],
            [['enable'], 'integer', 'max' => 1],
            [['code'], 'string'],
        ];
    }

    public static function getBalanceBank($data = array())
    {
        // if data not set => get all
        if(empty($data)){

            $balance_bank = BalanceBank::find()->all();

        }else{

            $balance_bank = BalanceBank::find()->where(['id' => $data['id']])->one();

        }

        return $balance_bank;
    }

    public static function createBalanceBank($data = array())
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $balance_bank = new BalanceBank();
        $balance_bank->setAttributes($data);
        $balance_bank->created_date = date('Y-m-d H:i:s');

        if(!$balance_bank->validate()){
            $resp['status']  = false;
            $resp['message'] = current($balance_bank->getErrors())[0];
        }else{
            $balance_bank->save();
        }

        return $resp;

    }

    public static function updateBalanceBank($data = array(), $id)
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $balance_bank = BalanceBank::find()->where(['id' => $id])->one();

        if(!empty($balance_bank)){
            $balance_bank->setAttributes($data);
            $balance_bank->updated_date = date('Y-m-d H:i:s');

            if(!$balance_bank->validate()){
                $resp['status']  = false;
                $resp['message'] = current($balance_bank->getErrors())[0];
            }else{
                $balance_bank->save();
            }
        }else{
            $resp['status']  = false;
            $resp['message'] = 'No Data Found';
        }

        return $resp;

    }

    public static function deleteBalanceBank($id)
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $balance_bank = BalanceBank::find()->where(['id' => $id])->one();

        if($balance_bank){
            $balance_bank->delete();
        }else{
            $resp['status']  = false;
            $resp['message'] = 'No Data Found';
        }

        return $resp;

    }

}
?>