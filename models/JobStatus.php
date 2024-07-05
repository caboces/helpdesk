<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%job_status}}".
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
class JobStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%job_status}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['level', 'status'], 'integer'],
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
        return $this->hasMany(Ticket::class, ['job_status_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\JobStatusQuery the active query used by this AR class.
     */
    public static function find() {
        return new \app\models\query\JobStatusQuery(get_called_class());
    }

    /**
     * Gets all SELECTABLE statuses
     * 
     * @return all statuses in an alphabetical array
     */
    public static function getStatuses() {
        return JobStatus::find()->where(['selectable' => 1])->orderBy('name ASC')->asArray()->all();
    }

    /**
     * Gets all NON-SELECTABLE statuses
     * 
     * @return all statuses in an alphabetical array
     */
    public static function getNonSelectableStatuses() {
        return JobStatus::find()->where(['selectable' => 0])->orderBy('name ASC')->asArray()->all();
    }

    /**
     * Gets name of status from id
     * 
     * @return the readable name of the status
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Gets level of status from id
     * 
     * @return the readable name of the status
     */
    public function getLevel() {
        return $this->level;
    }
}
