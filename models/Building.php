<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "building".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $street_address
 * @property string|null $po_box
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip
 * @property string|null $phone
 * @property int $status
 * @property string|null $created
 * @property string|null $modified
 *
 * @property Ticket[] $tickets
 */
class Building extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'building';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['name', 'street_address', 'city'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 500],
            [['po_box', 'zip', 'phone'], 'string', 'max' => 50],
            [['state'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'street_address' => 'Street Address',
            'po_box' => 'Po Box',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'phone' => 'Phone',
            'status' => 'Status',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\TicketQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['building_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\BuildingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\BuildingQuery(get_called_class());
    }
}
