<?php

namespace app\models;

use Yii;
use yii\db\Query;

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
 * @property int $last_modified_by_user_id
 * @property string|null $created
 * @property string|null $modified
 *
 * @property Ticket $ticket
 * @property User $user
 * @property LastModifiedBy $last_modified_by
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
            [['user_id', 'ticket_id', 'last_modified_by_user_id'], 'integer'],
            // these times are decimals(4,2) in the db. Using min-max rules to police this.
            [['tech_time', 'overtime', 'travel_time', 'itinerate_time'], 'number', 'min' => 0, 'max' => 99.75],
            [['ticket_id'], 'required'],
            // TODO: entry_date not validating because I didn't use Yii helpers
            // ALSO TODO: last_modified_by_user_id not working and failing validation checks with a redirect to an unstyled form...
            [['entry_date'], 'required', 'skipOnEmpty' => false, 'skipOnError' => false, 'message' => 'Please select the date of the hours worked.'],
            [['entry_date'], 'date', 'min' => strtotime('2000/01/01'), 'max' => strtotime(date('Y-m-d', strtotime('tomorrow'))), 'format' => 'php:Y-m-d', 'message' => 'The date must be in the format mm/dd/yyyy.', 'tooSmall' => 'The time entry date must be later than January 1st, 2000.', 'tooBig' => 'The time entry date must be today or before.'],
            [['user_id'], 'required', 'message' => 'User ID is required. Please make sure the relevant tech is assigned to the ticket.'],
            [['last_modified_by_user_id'], 'required', 'message' => 'Entry editor id is required.'],
            [['entry_date', 'created', 'modified'], 'safe'],
            // foreign keys
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::class, 'targetAttribute' => ['ticket_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['last_modified_by_user_id' => 'id']],
            
            /* 
             * TIME ENTRIES FORMAT VALIDATOR
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\UserQuery
     */
    public function getLastModifiedBy()
    {
        return $this->hasOne(User::class, ['id' => 'last_modified_by_user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\TimeEntryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\TimeEntryQuery(get_called_class());
    }

    /**
     * Query to find the total hours worked on a ticket for tech_time, overtime, travel_time,
     * itinerate_time, or the sum of all four.
     * 
     * ex) if you wanted total tech time for ticket 18, getTotalTicketTimeFor(18, 'tech_time')
     * 
     * Will return 0 if the $column_name isn't found in the array (acceptable_columns") or set to "all".
     */
    public static function getTotalTicketTimeFor($ticket_id, $column_name) {
        $total_ticket_time = 0.00;
        $acceptable_columns = array('tech_time', 'overtime', 'travel_time', 'itinerate_time');

        if (in_array($column_name, $acceptable_columns)) {
            $query = new Query;
            $query->select([$column_name])
                    ->from('time_entry')
                    ->where(['ticket_id' => $ticket_id]);
            if ($total_ticket_time = $query->sum($column_name) != NULL) {
                $total_ticket_time = $query->sum($column_name);
            } else {
                $total_ticket_time = 'None';
            };
        } elseif ($column_name == 'all') {
            $query = new Query;
            $query->select('SUM(tech_time) + SUM(overtime) + SUM(travel_time) + SUM(itinerate_time)')
                    ->from('time_entry')
                    ->where(['ticket_id' => $ticket_id]);
            if ($query->scalar() != NULL) {
                $total_ticket_time = $query->scalar();
            } else {
                $total_ticket_time = 'None';
            }
        }

        return $total_ticket_time;
    }
}
