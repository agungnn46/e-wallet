<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $access_token
 * @property string $last_login
 * @property string $created_date
 * @property string $updated_date
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['access_token'], 'string'],
            [['last_login', 'created_date', 'updated_date'], 'safe'],
            [['name', 'username', 'email'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'access_token' => 'Access Token',
            'last_login' => 'Last Login',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
        ];
    }
}
