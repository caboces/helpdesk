<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "district".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $component_district
 * @property int $status
 * @property string|null $created
 * @property string|null $modified
 *
 * @property DistrictBuilding[] $districtBuildings
 * @property Ticket[] $tickets
 */
class District extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%district}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['component_district', 'status'], 'integer'],
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
            'component_district' => 'Component District',
            'status' => 'Status',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * Gets query for [[DistrictBuildings]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DistrictBuildingQuery
     */
    public function getDistrictBuildings()
    {
        return $this->hasMany(DistrictBuilding::class, ['district_id' => 'id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\TicketQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['district_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\DistrictQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\DistrictQuery(get_called_class());
    }

    /**
     * Gets all districts
     * 
     * @return all districts in an alphabetical array
     */
    public static function getDistricts() {
        return District::find()->orderBy('name ASC')->asArray()->all();
    }

    /**
     * Gets name of district from id
     * 
     * @return the readable name of the district
     */
    public function getName() {
        return $this->name;
    }
}
