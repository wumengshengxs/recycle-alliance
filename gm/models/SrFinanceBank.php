<?php

namespace gm\models;

use Yii;

/**
 * This is the model class for table "sr_finance_bank".
 *
 * @property string $id 财务信息表id
 * @property string $company_name 收款企业名称
 * @property string $bank_account 收款企业银行账号
 * @property string $bank_name 收款企业开户行名称
 * @property string $bank_number 收款企业开户行行号
 */
class SrFinanceBank extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sr_finance_bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_name', 'bank_account', 'bank_name', 'bank_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_name' => 'Company Name',
            'bank_account' => 'Bank Account',
            'bank_name' => 'Bank Name',
            'bank_number' => 'Bank Number',
        ];
    }
}
