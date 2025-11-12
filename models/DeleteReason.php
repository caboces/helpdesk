<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "delete_reason".
 *
 * @property int $delete_code
 * @property string $reason_deleted
 * @property string|null $entry_date
 * @property string $last_modified_date
 *
 * @property Inventory[] $inventories
 * @property TechNotFound[] $techNotFounds
 */
class DeleteReason extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'delete_reason';
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
            [['delete_code', 'reason_deleted'], 'required'],
            [['delete_code'], 'integer'],
            [['entry_date', 'last_modified_date'], 'safe'],
            [['reason_deleted'], 'string', 'max' => 255],
            [['delete_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'delete_code' => 'Delete Code',
            'reason_deleted' => 'Reason Deleted',
            'entry_date' => 'Entry Date',
            'last_modified_date' => 'Last Modified Date',
        ];
    }

    /**
     * Gets query for [[Inventories]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\InventoryQuery
     */
    public function getInventories()
    {
        return $this->hasMany(Inventory::class, ['delete_code' => 'delete_code']);
    }

    /**
     * Gets query for [[TechNotFounds]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\TechNotFoundQuery
     */
    public function getTechNotFounds()
    {
        return $this->hasMany(TechNotFound::class, ['delete_code' => 'delete_code']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\DeleteReasonQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\DeleteReasonQuery(get_called_class());
    }
}
