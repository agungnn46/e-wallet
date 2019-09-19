<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_balance".
 *
 * @property int $id
 * @property int $user_id
 * @property int $balance
 * @property int $balance_achieve
 *
 * @property User $user
 * @property UserBalanceHistory[] $userBalanceHistories
 */
class UserBalance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'balance', 'balance_achieve'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'balance' => 'Balance',
            'balance_achieve' => 'Balance Achieve',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserBalanceHistories()
    {
        return $this->hasMany(UserBalanceHistory::className(), ['user_balance_id' => 'id']);
    }
}
