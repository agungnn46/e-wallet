<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;
use app\models\UserBalance;
use app\models\UserBalanceHistory;

class TransactionData extends Model
{
    public $amount;
    public $location;
    public $user_agent;
    public $to;

    public function rules()
    {
        return [
            [['amount', 'location', 'user_agent'], 'required', 'on' => ['topup', 'transfer']],
            [['amount', 'to'], 'integer'],
            [['location', 'user_agent'], 'string'],
            ['to', 'required', 'on' => 'transfer'],
        ];
    }

    public static function topup($data, $user)
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $transaction = Yii::$app->db->beginTransaction();

        $data_balance = UserBalance::find()->where(['user_id' => $user->id])->one();

        if(empty($data_balance)){

            $data_balance                  = new UserBalance();
            $data_balance->user_id         = $user->id;
            $data_balance->balance         = $data['amount'];
            $data_balance->balance_achieve = 10000000;
            $data_balance->created_date    = date('Y-m-d H:i:s');

            if(!$data_balance->validate()){
                $resp['status']  = false;
                $resp['message'] = current($data_balance->getErrors())[0];
                $transaction->rollBack();
                return $resp;
            }

            $data_balance->save();

            $balance_before = 0;
            $balance_after  = $data['amount'];

        }else{
            
            $balance_before = $data_balance->balance;
            $balance_after  = $balance_before + $data['amount'];

            if($balance_after > $data_balance->balance_achieve){
                $resp['status']  = false;
                $resp['message'] = 'Over The Limit';
                $transaction->rollBack();
                return $resp;
            }

            $data_balance->balance      = $balance_after;
            $data_balance->updated_date = date('Y-m-d H:i:s');

            if(!$data_balance->validate()){
                $resp['status']  = false;
                $resp['message'] = current($data_balance->getErrors())[0];
                $transaction->rollBack();
                return $resp;
            }

            $data_balance->save();

        }

        $history                  = new UserBalanceHistory();
        $history->user_balance_id = $data_balance->id;
        $history->balance_before  = $balance_before;
        $history->balance_after   = $balance_after;
        $history->activity        = 'topup';
        $history->type            = 'credit';
        $history->ip              = Yii::$app->request->userIP;
        $history->location        = $data['location'];
        $history->user_agent      = $data['user_agent'];
        $history->author          = $user->name;
        $history->created_date    = date('Y-m-d H:i:s');

        if(!$history->validate()){
            $resp['status']  = false;
            $resp['message'] = current($history->getErrors())[0];
            $transaction->rollBack();
            return $resp;
        }

        $history->save();
        $transaction->commit();

        return $resp;
    }

    public static function transfer($data, $user)
    {
        $resp['status']  = true;
        $resp['message'] = '';

        $transaction = Yii::$app->db->beginTransaction();

        $data_balance = UserBalance::find()->where(['user_id' => $user->id])->one();

        if(empty($data_balance)){
            $resp['status']  = false;
            $resp['message'] = 'No Balance Data Found';
            $transaction->rollBack();
            return $resp;
        }
            
        $balance_before = $data_balance->balance;
        $balance_after  = $balance_before - $data['amount'];

        if($balance_after < 0){
            $resp['status']  = false;
            $resp['message'] = 'Over The Limit';
            $transaction->rollBack();
            return $resp;
        }

        // ===================== reduce the sender's balance ===================== //
        $data_balance->balance      = $balance_after;
        $data_balance->updated_date = date('Y-m-d H:i:s');

        if(!$data_balance->validate()){
            $resp['status']  = false;
            $resp['message'] = current($data_balance->getErrors())[0];
            $transaction->rollBack();
            return $resp;
        }

        $data_balance->save();
        // ===================== reduce the sender's balance ===================== //

        // ==================== add to the recipient's balance =================== //
        $receiver_data = Users::find()->where(['id' => $data['to']])->one();

        if(empty($receiver_data)){
            $resp['status']  = false;
            $resp['message'] = 'Invalid Receiver ID';
            $transaction->rollBack();
            return $resp;
        }

        $receiver_balance = UserBalance::find()->where(['user_id' => $data['to']])->one();

        if(empty($receiver_balance)){
            $receiver_balance                  = new UserBalance();
            $receiver_balance->user_id         = $data['to'];
            $receiver_balance->balance         = $data['amount'];
            $receiver_balance->balance_achieve = 10000000;
            $receiver_balance->created_date    = date('Y-m-d H:i:s');

            $balance_before_receiver = 0;
            $balance_after_receiver  = $data['amount'];
        }else{
            $balance_before_receiver = $receiver_balance->balance;
            $balance_after_receiver  = $receiver_balance->balance + $data['amount'];

            $receiver_balance->balance      = $receiver_balance->balance + $data['amount'];
            $receiver_balance->created_date = date('Y-m-d H:i:s');

        }

        if(!$receiver_balance->validate()){
            $resp['status']  = false;
            $resp['message'] = current($receiver_balance->getErrors())[0];
            $transaction->rollBack();
            return $resp;
        }

        $receiver_balance->save();
        // ==================== add to the recipient's balance =================== //

        // =========================== history sender ============================ //
        $history_sender                  = new UserBalanceHistory();
        $history_sender->user_balance_id = $data_balance->id;
        $history_sender->balance_before  = $balance_before;
        $history_sender->balance_after   = $balance_after;
        $history_sender->activity        = 'transfer';
        $history_sender->type            = 'debit';
        $history_sender->ip              = Yii::$app->request->userIP;
        $history_sender->location        = $data['location'];
        $history_sender->user_agent      = $data['user_agent'];
        $history_sender->author          = $user->name;
        $history_sender->created_date    = date('Y-m-d H:i:s');

        if(!$history_sender->validate()){
            $resp['status']  = false;
            $resp['message'] = current($history_sender->getErrors())[0];
            $transaction->rollBack();
            return $resp;
        }

        $history_sender->save();
        // =========================== history sender ============================ //

        // ========================== history receiver =========================== //
        $history_receiver                  = new UserBalanceHistory();
        $history_receiver->user_balance_id = $data_balance->id;
        $history_receiver->balance_before  = $balance_before_receiver;
        $history_receiver->balance_after   = $balance_after_receiver;
        $history_receiver->activity        = 'transfer';
        $history_receiver->type            = 'credit';
        $history_receiver->ip              = Yii::$app->request->userIP;
        $history_receiver->location        = $data['location'];
        $history_receiver->user_agent      = $data['user_agent'];
        $history_receiver->author          = $user->name;
        $history_receiver->created_date    = date('Y-m-d H:i:s');

        if(!$history_receiver->validate()){
            $resp['status']  = false;
            $resp['message'] = current($history_receiver->getErrors())[0];
            $transaction->rollBack();
            return $resp;
        }

        $history_receiver->save();
        // ========================== history receiver =========================== //

        $transaction->commit();

        return $resp;
    }

}
?>