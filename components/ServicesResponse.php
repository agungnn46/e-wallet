<?php

namespace app\components;

use Yii;

class ServicesResponse
{

    public static function json($status = "500", $message = "Internal Error", $data = null){
        $resp = [
            'code'    => $status,
            'message' => $message,
            'data'    => $data
        ];

        Yii::$app->response->statusCode = (int) $status;
        \Yii::$app->response->format    = \yii\web\Response::FORMAT_JSON;

        return $resp;
    }

    public static function raw($status = "500", $message = "Internal Error", $data = null){
        $resp = [
            'code'    => $status,
            'message' => $message,
            'data'    => json_decode($data)
        ];

        return json_encode($resp);
    }
}