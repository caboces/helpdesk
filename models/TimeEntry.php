<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "time_entry".
 *
 * @property int $id
 * @property number $tech_time
 * @property number $overtime
 * @property number $travel_time
 * @property number $itinerate_time
 * @property string $entry_date
 * @property int $user_id
 * @property int $ticket_id
 * @property string|null $created
 * @property string|null $modified
 *
 * @property Ticket $ticket
 * @property User $user
 * @property EntryCreator $entry_creator
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
            [['user_id', 'ticket_id', 'entry_creator_id'], 'integer'],
            // these times are decimals(4,2) in the db. Using min-max rules to police this.
            [['tech_time', 'overtime', 'travel_time', 'itinerate_time'], 'number', 'min' => 0, 'max' => 99.75],
            [['ticket_id'], 'required'],
            // TODO: entry_date not validating because I didn't use Yii helpers
            [['entry_date'], 'required', 'skipOnEmpty' => false, 'skipOnError' => false, 'message' => 'Please select the date of the hours worked.'],
            [['user_id'], 'required', 'message' => 'User ID is required. Please make sure the relevant tech is assigned to the ticket.'],
            [['entry_creator_id'], 'required', 'message' => 'Entry creator required.'],
            [['entry_date', 'created', 'modified'], 'safe'],
            // foreign keys
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::class, 'targetAttribute' => ['ticket_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['entry_creator_id' => 'id']],
            /* TIME ENTRIES FORMAT VALIDATOR
             * - time entries can have between 0-2 whole integers
             * - time entries can have between 0-2 decimal places
             * - decimals (if used) can be either .0, .00, .25, .5, .50, or .75
             */
            [['tech_time', 'overtime', 'travel_time', 'itinerate_time'], 'match', 'pattern' => '/^[1-9]?[0-9]?(\.(0|00|25|5|50|75))?$/', 'message' => 'Time must be recorded in quarter-hour increments (0.25 => 15mins).'],
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
            'user_id' => 'User',
            'ticket_id' => 'Ticket ID',
            'entry_creator_id' => 'Entry Creator',
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\UserQuery
     */
    public function getEntryCreator()
    {
        return $this->hasOne(User::class, ['id' => 'entry_creator_id']);
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
