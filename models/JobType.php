<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%job_type}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $status
 * @property string $created
 * @property string $modified
 *
 * @property Ticket[] $tickets
 */
class JobType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%job_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
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
        return $this->hasMany(Ticket::class, ['job_type_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\JobTypeQuery the active query used by this AR class.
     */
    public static function find() {
        return new \app\models\query\JobTypeQuery(get_called_class());
    }

    /**
     * Gets all types
     * 
     * @return all types in an alphabetical array
     */
    public static function getTypes() {
        return JobType::find()->orderBy('name ASC')->asArray()->all();
    }

    /**
     * Gets name of status from id
     * 
     * @return the readable name of the status
     */
    public function getName() {
        return $this->name;
    }
}
