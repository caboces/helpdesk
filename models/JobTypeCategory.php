<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "job_type_category".
 *
 * @property int $id
 * @property int $job_type_id
 * @property int $job_category_id
 * @property string|null $created
 * @property string|null $modified
 *
 * @property JobCategory $jobCategory
 * @property JobType $jobType
 */
class JobTypeCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'job_type_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_type_id', 'job_category_id'], 'required'],
            [['job_type_id', 'job_category_id'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['job_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => JobCategory::class, 'targetAttribute' => ['job_category_id' => 'id']],
            [['job_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => JobType::class, 'targetAttribute' => ['job_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_type_id' => 'Job Type ID',
            'job_category_id' => 'Job Category ID',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * Gets query for [[JobCategory]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\JobCategoryQuery
     */
    public function getJobCategory()
    {
        return $this->hasOne(JobCategory::class, ['id' => 'job_category_id']);
    }

    /**
     * Gets query for [[JobType]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\JobTypeQuery
     */
    public function getJobType()
    {
        return $this->hasOne(JobType::class, ['id' => 'job_type_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\JobTypeCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\JobTypeCategoryQuery(get_called_class());
    }

    /**
     * Gets all ids/names of job categories based off of the selected job type id
     * 
     * @return all job category names in an alphabetical array
     */
    public static function getJobCategoryNamesFromJobTypeId($model) {
        return JobTypeCategory::find()
        ->select(['job_type_category.id', 'job_type_category.job_type_id', 'job_type_category.job_category_id', 'job_category.name'])
        ->from('job_type_category')
        ->innerJoin('job_category', 'job_type_category.job_category_id = job_category.id')
        ->where(['job_type_id' => $model->job_type_id])
        ->orderBy('name ASC')
        ->asArray()
        ->all();
    }
}
