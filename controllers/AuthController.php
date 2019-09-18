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
    public $access_token;
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

            if($action->id != "login"){

                // =====================  access token validation ===================== //
                $this->access_token = $request->headers->get('access-token');

                if(empty($this->access_token)){
                    $this->error_status = true;
                    $this->http_code    = 401;
                    $this->http_message = 'Invalid Access Token';
                    return parent::beforeAction($action);
                }

                $token_user = Users::find()->where(['access_token' => $this->access_token])->one();

                if(empty($this->access_token)){
                    $this->error_status = true;
                    $this->http_code    = 401;
                    $this->http_message = 'Invalid Access Token';
                    return parent::beforeAction($action);
                }
                // =====================  access token validation ===================== //

                $check_session = AppSession::find()->where(['id' => $this->access_token])->one(); 

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