<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "balance_bank_history".
 *
 * @property int $id
 * @property int $balance_bank_id
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
 * @property BalanceBank $balanceBank
 */
class BalanceBankHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'balance_bank_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['balance_bank_id', 'balance_before', 'balance_after'], 'integer'],
            [['type'], 'string'],
            [['created_date', 'updated_date'], 'safe'],
            [['activity', 'ip', 'location', 'user_agent', 'author'], 'string', 'max' => 200],
            [['balance_bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => BalanceBank::className(), 'targetAttribute' => ['balance_bank_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'balance_bank_id' => 'Balance Bank ID',
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
    public function getBalanceBank()
    {
        return $this->hasOne(BalanceBank::className(), ['id' => 'balance_bank_id']);
    }
}
