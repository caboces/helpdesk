<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "part".
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $last_modified_by_user_id
 * @property int $part_type_id
 * @property string|null $part_number
 * @property string $part_name
 * @property int $quantity
 * @property float $unit_price
 * @property int $pending_delivery
 * @property string|null $po_number
 * @property string|null $note
 * @property string|null $created
 * @property string|null $modified
 *
 * @property User $lastModifiedByUser
 * @property Ticket $ticket
 * @property PartType $partType
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
            [['ticket_id', 'last_modified_by_user_id', 'part_type_id', 'part_name', 'quantity', 'unit_price', 'pending_delivery'], 'required'],
            [['ticket_id', 'last_modified_by_user_id', 'part_type_id', 'quantity', 'pending_delivery'], 'integer'],
            [['unit_price'], 'number', 'min' => '0.01', 'max' => 9999.99],
            [['created', 'modified'], 'safe'],
            [['po_number'], 'string', 'max' => 45],
            [['part_number', 'part_name'], 'string', 'max' => 100],
            [['note'], 'string', 'max' => 250],
            [['last_modified_by_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['last_modified_by_user_id' => 'id']],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::class, 'targetAttribute' => ['ticket_id' => 'id']],
            [['part_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PartType::class, 'targetAttribute' => ['part_type_id' => 'id']],
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
            'part_type_id' => 'Part Type',
            'part_number' => 'Part Number',
            'po_number' => 'PO Number',
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
     * Gets query for [[PartType]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\PartTypeQuery
     */
    public function getPartType()
    {
        return $this->hasOne(PartType::class, ['id' => 'part_type_id']);
    }

    /**
     * Gets the parts associated with the specified PO number
     */
    public static function get($po)
    {
        return Part::find()
            ->where([
                'po_number' => $po
            ])->asArray()
            ->all();
    }

    /**
     * Returns the specific columns needed to show in the Parts Management page
     */
    public static function getDetailedParts() {
        return Part::find()
            ->select([
                'part.*',
                'part_type.name',
                'part_type.description',
            ])->leftJoin('part_type', 'part.part_type_id = part_type.id')
            ->asArray()
            ->all();
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
