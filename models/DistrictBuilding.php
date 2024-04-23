<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "district_building".
 *
 * @property int $id
 * @property int $district_id
 * @property int $building_id
 * @property int $status
 * @property string|null $created
 * @property string|null $modified
 *
 * @property Building $building
 * @property District $district
 */
class DistrictBuilding extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'district_building';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['district_id', 'building_id'], 'required'],
            [['district_id', 'building_id', 'status'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::class, 'targetAttribute' => ['district_id' => 'id']],
            [['building_id'], 'exist', 'skipOnError' => true, 'targetClass' => Building::class, 'targetAttribute' => ['building_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'district_id' => 'District ID',
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
     * Gets query for [[District]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DistrictQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::class, ['id' => 'district_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\DistrictBuildingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\DistrictBuildingQuery(get_called_class());
    }

    /**
     * Gets all district buildings
     * 
     * @return all district buildings in an alphabetical array
     */
    public static function getDistrictBuildings() {
        return DistrictBuilding::find()->orderBy('name ASC')->asArray()->all();
    }

    /**
     * Gets all ids/names of buildings based off of the selected district id
     * 
     * @return all district building names in an alphabetical array
     */
    public static function getBuildingNamesFromDistrictId($model) {
        return DistrictBuilding::find()
        ->select(['district_building.id', 'district_building.district_id', 'district_building.building_id', 'building.name'])
        ->innerJoin('building', 'district_building.building_id = building.id')
        ->where(['district_id' => $model->district_id])
        ->orderBy('name ASC')
        ->asArray()
        ->all();
    }

    /**
     * Gets name of district building from id
     * 
     * @return the readable name of the district building
     */
    public function getName() {
        return $this->name;
    }
}
