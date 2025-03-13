<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Vendors".
 *
 * @property string $vendor_id
 * @property string $vendor_name
 * @property string|null $entry_date
 * @property string $last_modified_date
 */
class Vendor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vendors';
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
            [['vendor_id', 'vendor_name'], 'required'],
            [['entry_date', 'last_modified_date'], 'safe'],
            [['vendor_id'], 'string', 'max' => 20],
            [['vendor_name'], 'string', 'max' => 255],
            [['vendor_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'vendor_id' => 'Vendor ID',
            'vendor_name' => 'Vendor Name',
            'entry_date' => 'Entry Date',
            'last_modified_date' => 'Last Modified Date',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\VendorsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\VendorsQuery(get_called_class());
    }
}
