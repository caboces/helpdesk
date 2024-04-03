<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $division_id
 * @property int $status
 * @property string|null $created
 * @property string|null $modified
 *
 * @property Division $division
 * @property Ticket[] $tickets
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'division_id'], 'required'],
            [['division_id', 'status'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 500],
            [['division_id'], 'exist', 'skipOnError' => true, 'targetClass' => Division::class, 'targetAttribute' => ['division_id' => 'id']],
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
            'division_id' => 'Division ID',
            'status' => 'Status',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * Gets query for [[Division]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DivisionQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::class, ['id' => 'division_id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\TicketQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['department_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\DepartmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\DepartmentQuery(get_called_class());
    }
}
