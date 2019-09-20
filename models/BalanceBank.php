<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "balance_bank".
 *
 * @property int $id
 * @property int $balance
 * @property int $balance_achieve
 * @property string $code
 * @property int $enable
 * @property string $created_date
 * @property string $updated_date
 *
 * @property BalanceBankHistory[] $balanceBankHistories
 */
class BalanceBank extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'balance_bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['balance', 'balance_achieve', 'enable'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['code'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'balance' => 'Balance',
            'balance_achieve' => 'Balance Achieve',
            'code' => 'Code',
            'enable' => 'Enable',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceBankHistories()
    {
        return $this->hasMany(BalanceBankHistory::className(), ['balance_bank_id' => 'id']);
    }
}
