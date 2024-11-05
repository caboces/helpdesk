<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "loaned_inventory".
 * 
 * @property id $id
 * @property new_prop_tag $new_prop_tag
 * @property bl_code $bl_code
 * @property date_borrowed $date_borrowed
 * @property date_returned $date_returned
 */
class LoanedInventory extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'loaned_inventory';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        // Check and see if this is right
        return [
            [['id', 'new_prop_tag'], 'integer'],
            [['new_prop_tag', 'bl_code', 'date_borrowed'], 'required'],
            [['bl_code'], 'string', 'max' => 20],
            [['date_borrowed', 'date_returned'], 'datetime']
        ];
    }

    public function attributeLabels() {
        // should be right
        return [
            'id' => 'ID',
            'new_prop_tag' => 'New Prop Tag',
            'bl_code' => 'Bl Code',
            'date_borrowed' => 'Date Borrowed',
            'date_returned' => 'Date Returned'
        ];
    }

    /**
     * Gets query for [[Inventory]].
     * 
     * @return \yii\db\ActiveQuery|InventoryQuery
     */
    public function getNewProp() {
        return $this->hasOne(Inventory::class, ['new_prop_tag' => 'new_prop_tag']);
    }

    /**
     * Gets query for [[BlCode]].
     *
     * @return \yii\db\ActiveQuery|LocationQuery
     */
    public function getBlCode() {
        // need to create Location class (same error in Inventory class)
        return $this->hasOne(Location::class, ['bl_code' => 'bl_code']);
    }

    /**
     * Gets the date borrowed.
     * 
     * @return date The date this was borrowed
     */
    public function getDateBorrowed() {
        return $this->date_borrowed;
    }

    /**
     * Gets the date returned.
     * 
     * @return date The date this was returned
     */
    public function getDateReturned() {
        return $this->date_returned;
    }

    /**
     * Returns a query that combines `loaned_inventory` and `inventory`
     * TODO Needs a solution for creating the inner join as `loaned_inventory` and `inventory` are in
     * different databases
     * 
     * @return yii\db\ActiveQuery the query
     */
    public static function findWithInventoryInformation() {
        return LoanedInventory::find()
            ->select(['loaned_inventory.id', 
                'inventory.item_description', 
                'inventory.serial_number', 
                'loaned_inventory.bl_code', 
                'loaned_inventory.date_borrowed', 
                'loaned_inventory.date_returned'])
            ->innerJoin('inventory', 'loaned_inventory.new_prop_tag = inventory.new_prop_tag');
    }
}
