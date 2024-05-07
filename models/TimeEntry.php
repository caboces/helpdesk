<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "time_entry".
 *
 * @property int $id
 * @property int $tech_time
 * @property int $overtime
 * @property int $travel_time
 * @property int $itinerate_time
 * @property string $entry_date
 * @property int $user_id
 * @property int $ticket_id
 * @property string|null $created
 * @property string|null $modified
 *
 * @property Ticket $ticket
 * @property User $user
 */
class TimeEntry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'time_entry';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tech_time', 'overtime', 'travel_time', 'itinerate_time', 'user_id', 'ticket_id'], 'integer'],
            [['entry_date', 'user_id', 'ticket_id'], 'required'],
            [['entry_date', 'created', 'modified'], 'safe'],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::class, 'targetAttribute' => ['ticket_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tech_time' => 'Tech Time',
            'overtime' => 'Overtime',
            'travel_time' => 'Travel Time',
            'itinerate_time' => 'Itinerate Time',
            'entry_date' => 'Entry Date',
            'user_id' => 'User ID',
            'ticket_id' => 'Ticket ID',
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
     * {@inheritdoc}
     * @return \app\models\query\TimeEntryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\TimeEntryQuery(get_called_class());
    }
}
