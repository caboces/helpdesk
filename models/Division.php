<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "division".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $status
 * @property string|null $created
 * @property string|null $modified
 *
 * @property Department[] $departments
 * @property Ticket[] $tickets
 */
class Division extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'division';
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
     * Gets query for [[Departments]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DepartmentQuery
     */
    public function getDepartments()
    {
        return $this->hasMany(Department::class, ['division_id' => 'id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\TicketQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['division_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\DivisionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\DivisionQuery(get_called_class());
    }
}
