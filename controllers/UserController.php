<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Users;
use app\models\LoginData;
use app\models\UserData;
use app\models\AppSession;
use app\components\ServicesResponse;

class UserController extends AuthController
{
    public function actionLogin()
    {
        if(!$this->error_status){
            try{
                
                $get_data           = new LoginData();
                $get_data->scenario = 'login';
                $get_data->setAttributes($this->dataReq);

                if(!$get_data->validate()){
                    return ServicesResponse::json(400, "Invalid Parent Format : ".current($get_data->getErrors())[0]);
                }

                $user_data = Users::find()->where(['username' => $get_data->username])->one();

                if(empty($user_data)){
                    return ServicesResponse::json(401, "Invalid Credential");
                }

                if(!Yii::$app->security->validatePassword($get_data->password, $user_data->password)){
                    return ServicesResponse::json(401, "Invalid Credential");
                }

                $user_data->access_token = Yii::$app->security->generateRandomString();
                $user_data->last_login   = date("Y-m-d H:i:s");
                $user_data->updated_date = date("Y-m-d H:i:s");
                $user_data->save();

                // update data user
                $data_user = LoginData::updateUser($user_data);

                return ServicesResponse::json(200, "Success", $data_user);

            } catch(\Exception $e){

                return ServicesResponse::json(500, $e->getMessage().' - Line : '.$e->getLine());

            }
        }
    }

    public function actionLogout()
    {
        if(!$this->error_status){
            try{

                $check_session = AppSession::find()->where(['id' => $this->access_token])->one();

                if(!empty($check_session)){
                    $check_session->delete();
                }

                $user_data               = Users::find()->where(['access_token' => $this->access_token])->one();
                $user_data->access_token = NULL;
                $user_data->save();

                return ServicesResponse::json(200, "Success");

            } catch(\Exception $e){

                return ServicesResponse::json(500, $e->getMessage().' - Line : '.$e->getLine());

            }
        }
    }

    public function actionGet()
    {
        if(!$this->error_status){
            try{

                if(!empty($this->dataReq)){

                    $get_data           = new UserData();
                    $get_data->scenario = 'get-by-id';
                    $get_data->setAttributes($this->dataReq);

                    if(!$get_data->validate()){
                        return ServicesResponse::json(400, "Invalid Parent Format : ".current($get_data->getErrors())[0]);
                    }

                }

                $data_user = UserData::getUser($this->dataReq);

                return ServicesResponse::json(200, "Success", $data_user);

            } catch(\Exception $e){

                return ServicesResponse::json(500, $e->getMessage().' - Line : '.$e->getLine());

            }
        }
    }

    public function actionCreate(){
        if(!$this->error_status){
            try{

                $get_data           = new UserData();
                $get_data->scenario = 'create';
                $get_data->setAttributes($this->dataReq);

                if(!$get_data->validate()){
                    return ServicesResponse::json(400, "Invalid Parent Format : ".current($get_data->getErrors())[0]);
                }

                $data_user = UserData::createUser($this->dataReq);

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

                $get_data           = new UserData();
                $get_data->scenario = 'update';
                $get_data->setAttributes($this->dataReq);

                if(empty($id)){
                    return ServicesResponse::json(400, "Invalid Parent Format : ID cannot be blank");
                }

                if(!$get_data->validate()){
                    return ServicesResponse::json(400, "Invalid Parent Format : ".current($get_data->getErrors())[0]);
                }
                
                $data_user = UserData::updateUser($this->dataReq, $id);

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
                
                $data_user = UserData::deleteUser($id);

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