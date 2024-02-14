<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%job_priority}}".
 *
 * @property int $id
 * @property string $name
 * @property int $level
 * @property string|null $description
 * @property int $status
 * @property string $created
 * @property string $modified
 *
 * @property Ticket[] $tickets
 */
class JobPriority extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%job_priority}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['level'], 'required'],
            [['status', 'level'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 500],
            [['name'], 'unique'],
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
            'level' => 'Level',
            'description' => 'Description',
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
    public function getTickets() {
        return $this->hasMany(Ticket::class, ['job_priority_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\JobPriorityQuery the active query used by this AR class.
     */
    public static function find() {
        return new \app\models\query\JobPriorityQuery(get_called_class());
    }

    /**
     * Gets all priorities
     * 
     * @return all priorities in an ordered array
     */
    public static function getPriorities() {
        return JobPriority::find()->orderBy('level ASC')->asArray()->all();
    }

    /**
     * Gets name of priority from id
     * 
     * @return the readable name of the priority
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Gets level of priority from id
     * 
     * @return the numeric level of the priority
     */
    public function getLevel() {
        return $this->level;
    }
}
