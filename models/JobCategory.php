<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%job_category}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $status
 * @property string $created
 * @property string $updated
 *
 * @property Ticket[] $tickets
 */
class JobCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%job_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['name, icon_path'], 'string', 'max' => 100],
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
            'icon_path' => 'Icon Path',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\TicketQuery
     */
    public function getTickets() {
        return $this->hasMany(Ticket::class, ['job_category_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * 
     * @return \app\models\query\JobCategoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new \app\models\query\JobCategoryQuery(get_called_class());
    }

    /**
     * Gets all categories
     * 
     * @return all categories in an alphabetical array
     */
    public static function getCategories() {
        return JobCategory::find()->orderBy('name ASC')->asArray()->all();
    }

    /**
     * Gets name of category from id
     * 
     * @return the readable name of the category
     */
    public function getName() {
        return $this->name;
    }

}
