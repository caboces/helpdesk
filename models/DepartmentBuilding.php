<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "department_building".
 *
 * @property int $id
 * @property int $department_id
 * @property int $building_id
 * @property int $status
 * @property string|null $created
 * @property string|null $modified
 *
 * @property Building $building
 * @property Department $department
 */
class DepartmentBuilding extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department_building';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['department_id', 'building_id'], 'required'],
            [['department_id', 'building_id', 'status'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['building_id'], 'exist', 'skipOnError' => true, 'targetClass' => Building::class, 'targetAttribute' => ['building_id' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['department_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'department_id' => 'Department ID',
            'building_id' => 'Building ID',
            'status' => 'Status',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * Gets query for [[Building]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\BuildingQuery
     */
    public function getBuilding()
    {
        return $this->hasOne(Building::class, ['id' => 'building_id']);
    }

    /**
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DepartmentQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['id' => 'department_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\DepartmentBuildingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\DepartmentBuildingQuery(get_called_class());
    }

    /**
     * Gets all department buildings
     * 
     * @return all department buildings in an alphabetical array
     */
    public static function getDepartmentBuildings() {
        return DepartmentBuilding::find()->orderBy('name ASC')->asArray()->all();
    }

    /**
     * Gets all ids/names of buildings based off of the selected department id
     * 
     * @return all department building names in an alphabetical array
     */
    public static function getBuildingNamesFromDepartmentId($model) {
        return DepartmentBuilding::find()
        ->select(['department_building.department_id', 'department_building.building_id', 'building.name'])
        ->innerJoin('building', 'department_building.building_id = building.id')
        ->where(['department_id' => $model->department_id])
        ->orderBy('name ASC')
        ->asArray()
        ->all();
    }

    /**
     * Gets name of department building from id
     * 
     * @return the readable name of the department building
     */
    public function getName() {
        return $this->name;
    }
}
