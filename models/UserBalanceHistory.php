<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_balance_history".
 *
 * @property int $id
 * @property int $user_balance_id
 * @property int $balance_before
 * @property int $balance_after
 * @property string $activity
 * @property string $type
 * @property string $ip
 * @property string $location
 * @property string $user_agent
 * @property string $author
 * @property string $created_date
 * @property string $updated_date
 *
 * @property UserBalance $userBalance
 */
class UserBalanceHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_balance_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_balance_id', 'balance_before', 'balance_after'], 'integer'],
            [['type'], 'string'],
            [['created_date', 'updated_date'], 'safe'],
            [['activity', 'ip', 'location', 'user_agent', 'author'], 'string', 'max' => 200],
            [['user_balance_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserBalance::className(), 'targetAttribute' => ['user_balance_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_balance_id' => 'User Balance ID',
            'balance_before' => 'Balance Before',
            'balance_after' => 'Balance After',
            'activity' => 'Activity',
            'type' => 'Type',
            'ip' => 'Ip',
            'location' => 'Location',
            'user_agent' => 'User Agent',
            'author' => 'Author',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserBalance()
    {
        return $this->hasOne(UserBalance::className(), ['id' => 'user_balance_id']);
    }
}
