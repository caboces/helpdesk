<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "locations".
 *
 * @property string $bl_code
 * @property string $division_id
 * @property string $site_id
 * @property string $supervisor_id
 * @property string $bl_name
 * @property int $has_inventory
 * @property int $retd_inv_2012
 * @property int $site_visited_2004
 * @property int $active When a location is no longer active, this field is set to zero.
 * @property string|null $entry_date
 * @property string $last_modified_date
 *
 * @property CapitalAsset[] $capitalAssets
 * @property Division $division
 * @property Inventory[] $inventories
 * @property Site $site
 * @property Supervisor $supervisor
 * @property TechNotFound[] $techNotFounds
 * @property UsedTag[] $usedTags
 * @property ZDel1112[] $zDel1112s
 * @property ZDelAll[] $zDelAlls
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'locations';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bl_code', 'division_id', 'site_id', 'supervisor_id', 'bl_name'], 'required'],
            [['has_inventory', 'retd_inv_2012', 'site_visited_2004', 'active'], 'integer'],
            [['entry_date', 'last_modified_date'], 'safe'],
            [['bl_code', 'division_id', 'site_id', 'supervisor_id'], 'string', 'max' => 20],
            [['bl_name'], 'string', 'max' => 255],
            [['bl_code'], 'unique'],
            [['division_id'], 'exist', 'skipOnError' => true, 'targetClass' => Division::class, 'targetAttribute' => ['division_id' => 'division_id']],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => Site::class, 'targetAttribute' => ['site_id' => 'site_id']],
            [['supervisor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supervisor::class, 'targetAttribute' => ['supervisor_id' => 'supervisor_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bl_code' => 'Bl Code',
            'division_id' => 'Division ID',
            'site_id' => 'Site ID',
            'supervisor_id' => 'Supervisor ID',
            'bl_name' => 'Bl Name',
            'has_inventory' => 'Has Inventory',
            'retd_inv_2012' => 'Retd Inv 2012',
            'site_visited_2004' => 'Site Visited 2004',
            'active' => 'Active',
            'entry_date' => 'Entry Date',
            'last_modified_date' => 'Last Modified Date',
        ];
    }

    /**
     * Gets query for [[CapitalAssets]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\CapitalAssetQuery
     */
    public function getCapitalAssets()
    {
        return $this->hasMany(CapitalAsset::class, ['bl_code' => 'bl_code']);
    }

    /**
     * Gets query for [[Division]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DivisionQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::class, ['division_id' => 'division_id']);
    }

    /**
     * Gets query for [[Inventories]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\InventoryQuery
     */
    public function getInventories()
    {
        return $this->hasMany(Inventory::class, ['bl_code' => 'bl_code']);
    }

    /**
     * Gets query for [[Site]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\SiteQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::class, ['site_id' => 'site_id']);
    }

    /**
     * Gets query for [[Supervisor]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\SupervisorQuery
     */
    public function getSupervisor()
    {
        return $this->hasOne(Supervisor::class, ['supervisor_id' => 'supervisor_id']);
    }

    /**
     * Gets query for [[TechNotFounds]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\TechNotFoundQuery
     */
    public function getTechNotFounds()
    {
        return $this->hasMany(TechNotFound::class, ['bl_code' => 'bl_code']);
    }

    /**
     * Gets query for [[UsedTags]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\UsedTagQuery
     */
    public function getUsedTags()
    {
        return $this->hasMany(UsedTag::class, ['bl_code' => 'bl_code']);
    }

    /**
     * Gets query for [[ZDel1112s]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\ZDel1112Query
     */
    public function getZDel1112s()
    {
        return $this->hasMany(ZDel1112::class, ['bl_code' => 'bl_code']);
    }

    /**
     * Gets query for [[ZDelAlls]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\ZDelAllQuery
     */
    public function getZDelAlls()
    {
        return $this->hasMany(ZDelAll::class, ['bl_code' => 'bl_code']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\LocationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\LocationQuery(get_called_class());
    }
}
