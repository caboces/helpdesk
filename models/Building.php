<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "building".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $status
 * @property string|null $created
 * @property string|null $modified
 *
 * @property DepartmentBuilding[] $departmentBuildings
 * @property DistrictBuilding[] $districtBuildings
 * @property Ticket[] $tickets
 */
class Building extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%building}}';
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
     * Gets query for [[DepartmentBuildings]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DepartmentBuildingQuery
     */
    public function getDepartmentBuildings()
    {
        return $this->hasMany(DepartmentBuilding::class, ['building_id' => 'id']);
    }

    /**
     * Gets query for [[DistrictBuildings]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DistrictBuildingQuery
     */
    public function getDistrictBuildings()
    {
        return $this->hasMany(DistrictBuilding::class, ['building_id' => 'id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\TicketQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['building_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\BuildingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\BuildingQuery(get_called_class());
    }

    /**
     * Gets all buildings
     * 
     * @return all buildings in an alphabetical array
     */
    public static function getBuildings() {
        return Building::find()->orderBy('name ASC')->asArray()->all();
    }

    /**
     * Gets name of building from id
     * 
     * @return the readable name of the building
     */
    public function getName() {
        return $this->name;
    }
}
