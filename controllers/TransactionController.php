<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\TransactionData;
use app\components\ServicesResponse;

class TransactionController extends AuthController
{

    public function actionTopup(){
        if(!$this->error_status){
            try{

                $get_data           = new TransactionData();
                $get_data->scenario = 'topup';
                $get_data->setAttributes($this->dataReq);

                if(!$get_data->validate()){
                    return ServicesResponse::json(400, "Invalid Parent Format : ".current($get_data->getErrors())[0]);
                }

                $data_user = TransactionData::topup($this->dataReq,$this->user);

                if($data_user['status'] == false){
                    return ServicesResponse::json(500, $data_user['message']);
                }else{
                    return ServicesResponse::json(200, "Success");
                }

            } catch(\Exception $e){

                return ServicesResponse::json(500, $e->getMessage().' - Line : '.$e->getLine());

            }
        }
    }

    public function actionTransfer(){
        if(!$this->error_status){
            try{

                $get_data           = new TransactionData();
                $get_data->scenario = 'transfer';
                $get_data->setAttributes($this->dataReq);

                if(!$get_data->validate()){
                    return ServicesResponse::json(400, "Invalid Parent Format : ".current($get_data->getErrors())[0]);
                }

                $data_user = TransactionData::transfer($this->dataReq,$this->user);

                if($data_user['status'] == false){
                    return ServicesResponse::json(500, $data_user['message']);
                }else{
                    return ServicesResponse::json(200, "Success");
                }

            } catch(\Exception $e){

                return ServicesResponse::json(500, $e->getMessage().' - Line : '.$e->getLine());

            }
        }
    }

}