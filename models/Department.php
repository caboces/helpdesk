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
 * @property DepartmentBuilding[] $departmentBuildings
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
        return '{{%department}}';
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
     * Gets query for [[DepartmentBuildings]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DepartmentBuildingQuery
     */
    public function getDepartmentBuildings()
    {
        return $this->hasMany(DepartmentBuilding::class, ['department_id' => 'id']);
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

    /**
     * Gets all departments
     * 
     * @return all statuses in an alphabetical array
     */
    public static function getDepartments() {
        return Department::find()->orderBy('name ASC')->asArray()->all();
    }

    /**
     * Gets name of department from id
     * 
     * @return the readable name of the department
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Gets the name of the departments and concatenates it with the corresponding division
     * e.g., "division > department" will be displayed
     */
    public function getDivisionDepartmentNames() {
        return $this->division_id . ' > ' . $this->name;
    }
}
