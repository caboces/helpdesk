<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "part_type".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $created_by
 * @property bool $status
 * @property string|null $created
 * @property string|null $modified
 * 
 * @property User createdBy
 */
class PartType extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'part_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'status', 'created_by'], 'required'],
            [['created_by'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['status'], 'boolean'],
            [['description'], 'string', 'max' => 250],
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
            'created_by' => 'Created By',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * Gets the user that created this part type
     */
    public function getCreatedBy() {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\PartTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\PartTypeQuery(get_called_class());
    }
}
