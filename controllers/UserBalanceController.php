<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\UserBalanceData;
use app\components\ServicesResponse;

class UserBalanceController extends AuthController
{

    public function actionGetUserBalance()
    {
        if(!$this->error_status){
            try{

                if(!empty($this->dataReq)){

                    $get_data           = new UserBalanceData();
                    $get_data->scenario = 'get-by-id';
                    $get_data->setAttributes($this->dataReq);

                    if(!$get_data->validate()){
                        return ServicesResponse::json(400, "Invalid Parent Format : ".current($get_data->getErrors())[0]);
                    }

                }

                $data_user = UserBalanceData::getUserBalance($this->dataReq);

                return ServicesResponse::json(200, "Success", $data_user);

            } catch(\Exception $e){

                return ServicesResponse::json(500, $e->getMessage().' - Line : '.$e->getLine());

            }
        }
    }

    public function actionCreate(){
        if(!$this->error_status){
            try{

                $get_data           = new UserBalanceData();
                $get_data->scenario = 'create';
                $get_data->setAttributes($this->dataReq);

                if(!$get_data->validate()){
                    return ServicesResponse::json(400, "Invalid Parent Format : ".current($get_data->getErrors())[0]);
                }

                $data_user = UserBalanceData::createUserBalance($this->dataReq);

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

    public function actionUpdate($id){
        if(!$this->error_status){
            try{

                $get_data           = new UserBalanceData();
                $get_data->scenario = 'update';
                $get_data->setAttributes($this->dataReq);

                if(empty($id)){
                    return ServicesResponse::json(400, "Invalid Parent Format : ID cannot be blank");
                }

                if(!$get_data->validate()){
                    return ServicesResponse::json(400, "Invalid Parent Format : ".current($get_data->getErrors())[0]);
                }
                
                $data_user = UserBalanceData::updateUserBalance($this->dataReq, $id);

                if($data_user['status'] == false){
                    return ServicesResponse::json(500, $data_user['message']);
                }else{
                    return ServicesResponse::json(204, "Success");
                }

            } catch(\Exception $e){

                return ServicesResponse::json(500, $e->getMessage().' - Line : '.$e->getLine());

            }
        }
    }

    public function actionDelete($id){
        if(!$this->error_status){
            try{

                if(empty($id)){
                    return ServicesResponse::json(400, "Invalid Parent Format : ID cannot be blank");
                }
                
                $data_user = UserBalanceData::deleteUserBalance($id);

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