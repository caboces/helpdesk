<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "class".
 *
 * @property string $class_id
 * @property string $class_description
 * @property string|null $entry_date
 * @property string $last_modified_date
 *
 * @property CapitalAsset[] $capitalAssets
 * @property Inventory[] $inventories
 * @property TechNotFound[] $techNotFounds
 * @property UsedTag[] $usedTags
 * @property ZDel1112[] $zDel1112s
 * @property ZDelAll[] $zDelAlls
 */
class InventoryClass extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'class';
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
            [['class_id', 'class_description'], 'required'],
            [['entry_date', 'last_modified_date'], 'safe'],
            [['class_id'], 'string', 'max' => 20],
            [['class_description'], 'string', 'max' => 255],
            [['class_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'class_id' => 'Class ID',
            'class_description' => 'Class Description',
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
        return $this->hasMany(CapitalAsset::class, ['class_id' => 'class_id']);
    }

    /**
     * Gets query for [[Inventories]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\InventoryQuery
     */
    public function getInventories()
    {
        return $this->hasMany(Inventory::class, ['class_id' => 'class_id']);
    }

    /**
     * Gets query for [[TechNotFounds]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\TechNotFoundQuery
     */
    public function getTechNotFounds()
    {
        return $this->hasMany(TechNotFound::class, ['class_id' => 'class_id']);
    }

    /**
     * Gets query for [[UsedTags]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\UsedTagQuery
     */
    public function getUsedTags()
    {
        return $this->hasMany(UsedTag::class, ['class_id' => 'class_id']);
    }

    /**
     * Gets query for [[ZDel1112s]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\ZDel1112Query
     */
    public function getZDel1112s()
    {
        return $this->hasMany(ZDel1112::class, ['class_id' => 'class_id']);
    }

    /**
     * Gets query for [[ZDelAlls]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\ZDelAllQuery
     */
    public function getZDelAlls()
    {
        return $this->hasMany(ZDelAll::class, ['class_id' => 'class_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\InventoryClassQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\InventoryClassQuery(get_called_class());
    }
}
