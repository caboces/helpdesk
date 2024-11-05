<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventory".
 *
 * @property int $new_prop_tag
 * @property string|null $fund_id
 * @property string $bl_code
 * @property int|null $delete_code
 * @property string|null $class_id
 * @property string $vendor_id
 * @property int $tagged
 * @property int $qty
 * @property string $item_description
 * @property string|null $serial_number
 * @property string|null $purchased_date
 * @property int|null $date_purchased_num
 * @property string|null $po
 * @property float|null $unit_price
 * @property float|null $total_price
 * @property int|null $useful_life
 * @property string|null $delete_status
 * @property string|null $date_deleted
 * @property string|null $deleted_date
 * @property int|null $old_prop_tag
 * @property int $donated_to_boces
 * @property string|null $date_donated
 * @property string|null $donated_date
 * @property int $has_inv
 * @property string|null $entry_date
 * @property string $last_modified_date
 *
 * @property Location $blCode
 * @property Class $class
 * @property DeleteReason $deleteCode
 * @property Fund $fund
 * @property Vendor $vendor
 */
class Inventory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory';
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
            [['new_prop_tag', 'bl_code', 'vendor_id', 'qty', 'item_description'], 'required'],
            [['new_prop_tag', 'delete_code', 'tagged', 'qty', 'date_purchased_num', 'useful_life', 'old_prop_tag', 'donated_to_boces', 'has_inv'], 'integer'],
            [['purchased_date', 'deleted_date', 'donated_date', 'entry_date', 'last_modified_date'], 'safe'],
            [['unit_price', 'total_price'], 'number'],
            [['fund_id', 'bl_code', 'class_id', 'vendor_id', 'po', 'date_deleted', 'date_donated'], 'string', 'max' => 20],
            [['item_description', 'serial_number'], 'string', 'max' => 255],
            [['delete_status'], 'string', 'max' => 50],
            [['new_prop_tag'], 'unique'],
            [['delete_code'], 'exist', 'skipOnError' => true, 'targetClass' => DeleteReason::class, 'targetAttribute' => ['delete_code' => 'delete_code']],
            [['fund_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fund::class, 'targetAttribute' => ['fund_id' => 'fund_id']],
            [['bl_code'], 'exist', 'skipOnError' => true, 'targetClass' => Location::class, 'targetAttribute' => ['bl_code' => 'bl_code']],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::class, 'targetAttribute' => ['vendor_id' => 'vendor_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'new_prop_tag' => 'New Prop Tag',
            'fund_id' => 'Fund ID',
            'bl_code' => 'Bl Code',
            'delete_code' => 'Delete Code',
            'class_id' => 'Class ID',
            'vendor_id' => 'Vendor ID',
            'tagged' => 'Tagged',
            'qty' => 'Qty',
            'item_description' => 'Item Description',
            'serial_number' => 'Serial Number',
            'purchased_date' => 'Purchased Date',
            'date_purchased_num' => 'Date Purchased Num',
            'po' => 'Po',
            'unit_price' => 'Unit Price',
            'total_price' => 'Total Price',
            'useful_life' => 'Useful Life',
            'delete_status' => 'Delete Status',
            'date_deleted' => 'Date Deleted',
            'deleted_date' => 'Deleted Date',
            'old_prop_tag' => 'Old Prop Tag',
            'donated_to_boces' => 'Donated To Boces',
            'date_donated' => 'Date Donated',
            'donated_date' => 'Donated Date',
            'has_inv' => 'Has Inv',
            'entry_date' => 'Entry Date',
            'last_modified_date' => 'Last Modified Date',
        ];
    }

    /**
     * Gets query for [[BlCode]].
     *
     * @return \yii\db\ActiveQuery|LocationQuery
     */
    public function getBlCode()
    {
        return $this->hasOne(Location::class, ['bl_code' => 'bl_code']);
    }

    /**
     * Gets query for [[DeleteCode]].
     *
     * @return \yii\db\ActiveQuery|DeleteReasonQuery
     */
    public function getDeleteCode()
    {
        return $this->hasOne(DeleteReason::class, ['delete_code' => 'delete_code']);
    }

    /**
     * Gets query for [[Fund]].
     *
     * @return \yii\db\ActiveQuery|FundQuery
     */
    public function getFund()
    {
        return $this->hasOne(Fund::class, ['fund_id' => 'fund_id']);
    }

    /**
     * Gets query for [[Vendor]].
     *
     * @return \yii\db\ActiveQuery|VendorQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Vendor::class, ['vendor_id' => 'vendor_id']);
    }

    /**
     * {@inheritdoc}
     * @return InventoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InventoryQuery(get_called_class());
    }

    /**
     * Get the borrowers of this item based on the many to many relationship between `loaned_inventory` and `inventory.
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getBorrowers() {
        return $this->hasMany(Location::class, ['bl_code' => 'bl_code'])->viaTable('{{%loaned_inventory}}', ['bl_code' => 'bl_code']);
    }
}
