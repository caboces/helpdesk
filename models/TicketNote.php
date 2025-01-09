<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket_note".
 *
 * @property int $id
 * @property int $ticket_id
 * @property string|null $note
 * @property int $last_modified_by_user_id
 * @property string|null $created
 * @property string|null $modified
 *
 * @property Ticket $ticket
 */
class TicketNote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket_note';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
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
            'note' => 'Note',
            'last_modified_by_user_id' => 'Last Note Editor',
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
     * @return \app\models\query\TicketNoteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\TicketNoteQuery(get_called_class());
    }
}
