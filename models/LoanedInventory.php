<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "loaned_inventory".
 * 
 * @property id $id
 * @property new_prop_tag $new_prop_tag
 * @property date_borrowed $date_borrowed
 * @property date_returned $date_returned
 * @property borrower_name $borrower_name
 * @property borrower_email $borrower_email
 * @property borrower_phone $borrower_phone
 * @property borrower_loc $borrower_loc
 * @property borrower_reason $borrower_reason
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
            [['borrower_name', 'borrower_email', 'borrower_phone', 'borrower_loc', 'borrower_reason'], 'string', 'max' => 45],
            [['borrower_email'], 'email'],
            [['new_prop_tag', 'date_borrowed', 'borrower_name', 'borrower_phone', 'borrower_loc', 'borrower_reason'], 'required'],
            [['date_borrowed', 'date_returned'], 'date']
        ];
    }

    public function attributeLabels() {
        // should be right
        return [
            'id' => 'ID',
            'new_prop_tag' => 'New Prop Tag',
            'date_borrowed' => 'Date Borrowed',
            'date_returned' => 'Date Returned',
            'borrower_name' => 'Borrower Name',
            'borrower_email' => 'Borrower Email',
            'borrower_phone' => 'Borrower Phone',
            'borrower_loc' => 'Borrower Location',
            'borrower_reason' => 'Borrower Reason'
        ];
    }

    /**
     * Gets query for [[Inventory]].
     * 
     * @return \yii\db\ActiveQuery|InventoryQuery
     */
    public function getInventory() {
        return $this->hasOne(Inventory::class, ['new_prop_tag' => 'new_prop_tag']);
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
     * Gets the borrower's full name.
     * 
     * @return 
     */
    public function getBorrowerName() {
        return $this->borrower_name;
    }

    /**
     * Gets the borrower's email.
     * 
     * @return 
     */
    public function getBorrowerEmail() {
        return $this->borrower_email;
    }

    /**
     * Gets the borrower's phone number.
     * 
     * @return 
     */
    public function getBorrowerPhone() {
        return $this->borrower_phone;
    }

    /**
     * Gets the borrower's location.
     * 
     * @return 
     */
    public function getBorrowerLocation() {
        return $this->borrower_loc;
    }

    /**
     * Gets the borrower's reason for the loan.
     * 
     * @return 
     */
    public function getBorrowerReason() {
        return $this->borrower_reason;
    }

    /**
     * Returns a query that combines information from `loaned_inventory` and `federated_inventory`.
     * `federated_inventory` is in the inv database and is a table with the FEDERATED engine.
     * 
     * @return yii\db\ActiveQuery the query
     */
    public static function findWithInventoryInformation() {
        return LoanedInventory::find()
            ->select(['loaned_inventory.id',
                'loaned_inventory.new_prop_tag', 
                'federated_inventory.item_description as item_description', 
                'federated_inventory.serial_number as serial_number', 
                'loaned_inventory.date_borrowed', 
                'loaned_inventory.date_returned',
                'loaned_inventory.borrower_name',
                'loaned_inventory.borrower_email',
                'loaned_inventory.borrower_phone',
                'loaned_inventory.borrower_loc',
                'loaned_inventory.borrower_reason'])
            ->innerJoin('federated_inventory', 'loaned_inventory.new_prop_tag = federated_inventory.new_prop_tag');
    }
}
