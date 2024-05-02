<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "time_entry".
 *
 * @property int $id
 * @property int $duration_minutes
 * @property string $entry_date
 * @property int $tech_ticket_assignment_id
 * @property string|null $created
 * @property string|null $modified
 *
 * @property TechTicketAssignment $techTicketAssignment
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
            [['duration_minutes', 'tech_ticket_assignment_id'], 'integer'],
            [['entry_date', 'tech_ticket_assignment_id'], 'required'],
            [['entry_date', 'created', 'modified'], 'safe'],
            [['tech_ticket_assignment_id'], 'exist', 'skipOnError' => true, 'targetClass' => TechTicketAssignment::class, 'targetAttribute' => ['tech_ticket_assignment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'duration_minutes' => 'Duration Minutes',
            'entry_date' => 'Entry Date',
            'tech_ticket_assignment_id' => 'Tech Ticket Assignment ID',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * Gets query for [[TechTicketAssignment]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\TechTicketAssignmentQuery
     */
    public function getTechTicketAssignment()
    {
        return $this->hasOne(TechTicketAssignment::class, ['id' => 'tech_ticket_assignment_id']);
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
