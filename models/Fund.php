<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Funds".
 *
 * @property string $fund_id
 * @property string $fund_description
 * @property string|null $entry_date
 * @property string $last_modified_date
 */
class Fund extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'funds';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fund_id', 'fund_description'], 'required'],
            [['entry_date', 'last_modified_date'], 'safe'],
            [['fund_id'], 'string', 'max' => 20],
            [['fund_description'], 'string', 'max' => 255],
            [['fund_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fund_id' => 'Fund ID',
            'fund_description' => 'Fund Description',
            'entry_date' => 'Entry Date',
            'last_modified_date' => 'Last Modified Date',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\FundsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\FundsQuery(get_called_class());
    }
}
