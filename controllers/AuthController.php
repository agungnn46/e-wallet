<?php

namespace app\controllers;

use Yii;
use yii\base\Model;
use yii\web\Request;
use yii\web\Controller;
use app\models\Users;
use app\models\AppSession;
use app\components\ServicesResponse;

class AuthController extends Controller
{
	public $enableCsrfValidation = false;
    
    public $dataReq;
	public $user;
    public $error_status = false;
    public $http_code    = 200;
    public $http_message = 'success';

    public function beforeAction($action)
    {

        try{
        	date_default_timezone_set('Asia/Jakarta');

            // get request
            $request = Yii::$app->request;

            if ($request->isGet){

                $req_data = $request->get();

            }else if($request->isPost){

                $req_data = $request->post();

                if(empty($req_data)){
                    $req_data = json_decode(file_get_contents('php://input'), true);                
                }

            }else if($request->isPut){

                $req_data = json_decode(file_get_contents('php://input'), true);  

            }

            $mdlRequest = new ParentRequest();

            if($action->id != "login"){
                $mdlRequest->scenario = 'all';
            }

            $mdlRequest->setAttributes($req_data);

            // validate model request
            if(!$mdlRequest->validate()){
                $this->error_status = true;
                $this->http_code    = 400;
                $this->http_message = current($mdlRequest->getErrors())[0];
                return parent::beforeAction($action);
            }

            if($action->id != "login"){

                // ===================== user validation ===================== //
                $this->user = Users::find()->where(['id' => $mdlRequest->user_id])->one();

                if(!isset($this->user)){
                    $this->error_status = true;
                    $this->http_code    = 401;
                    $this->http_message = 'Invalid User Id';
                    return parent::beforeAction($action);
                }
                // ===================== user validation ===================== //

                $check_session = AppSession::find()->where(['id' => $mdlRequest->user_id])->one(); 

                if(!$check_session){
                    $this->error_status = true;
                    $this->http_code    = 403;
                    $this->http_message = 'Session Timeout';
                    return parent::beforeAction($action);
                }

                if($check_session->expire < time()){
                    $check_session->delete();
                    $this->error_status = true;
                    $this->http_code    = 403;
                    $this->http_message = 'Session Timeout';
                    return parent::beforeAction($action);
                }

            }

            // send to public
            $this->dataReq = $req_data;

    	}catch(\Exception $e){
            $this->error_status = true;
            $this->http_code    = 500;
            $this->http_message = $e->getMessage().' - Line : '.$e->getLine();
            return parent::beforeAction($action);
        }

		return parent::beforeAction($action);
	}

	public function afterAction($action, $result)
    {
	    if($this->error_status){
            return ServicesResponse::json($this->http_code, $this->http_message);
        }else{

            $result = parent::afterAction($action, $result);

            return $result;
        }
    }
}

class ParentRequest extends Model
{
    public $user_id;

    public function rules()
    {
        return [
            [['user_id'], 'required', 'on' => 'all'],
        ];
    }
}