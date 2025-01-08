<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asset".
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $new_prop_tag
 * @property int $last_modified_by_user_id
 * @property string|null $created
 * @property string|null $modified
 *
 * @property Ticket $ticket
 */
class Asset extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asset';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_id', 'new_prop_tag'], 'required'],
            [['ticket_id', 'new_prop_tag', 'last_modified_by_user_id'], 'integer'],
            [['created', 'modified'], 'safe'],
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
            'new_prop_tag' => 'Asset Tag',
            'last_modified_by_user_id' => 'Last Entry Editor',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Inventory]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\Inventory
     */
    public function getInventory()
    {
        return $this->hasOne(Inventory::class, ['new_prop_tag' => 'new_prop_tag']);
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
     * {@inheritdoc}
     * @return \app\models\query\AssetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\AssetQuery(get_called_class());
    }
}
