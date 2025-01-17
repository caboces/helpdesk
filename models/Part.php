<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "part".
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $last_modified_by_user_id
 * @property string|null $part_number
 * @property string $part_name
 * @property int $quantity
 * @property float $unit_price
 * @property int $pending_delivery
 * @property string|null $note
 * @property string|null $created
 * @property string|null $modified
 *
 * @property User $lastModifiedByUser
 * @property Ticket $ticket
 */
class Part extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'part';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_id', 'last_modified_by_user_id', 'part_name', 'quantity', 'unit_price'], 'required'],
            [['ticket_id', 'last_modified_by_user_id', 'quantity', 'pending_delivery'], 'integer'],
            [['unit_price'], 'number', 'min' => '0.01', 'max' => 99.99],
            [['created', 'modified'], 'safe'],
            [['part_number', 'part_name'], 'string', 'max' => 100],
            [['note'], 'string', 'max' => 500],
            [['last_modified_by_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['last_modified_by_user_id' => 'id']],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::class, 'targetAttribute' => ['ticket_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticket_id' => 'Ticket ID',
            'last_modified_by_user_id' => 'Last Modified By User ID',
            'part_number' => 'Part Number',
            'part_name' => 'Part Name',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
            'pending_delivery' => 'Pending Delivery',
            'note' => 'Note',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * Gets query for [[LastModifiedByUser]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\UserQuery
     */
    public function getLastModifiedByUser()
    {
        return $this->hasOne(User::class, ['id' => 'last_modified_by_user_id']);
    }

    /**
     * Gets query for [[Ticket]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\TicketQuery
     */
    public function getTicket()
    {
        return $this->hasOne(Ticket::class, ['id' => 'ticket_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\PartQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\PartQuery(get_called_class());
    }
}
