<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hourly_rate".
 *
 * @property int $id
 * @property int $job_type_id
 * @property float $rate
 * @property float|null $summer_rate
 * @property string|null $description
 * @property string $first_day_effective
 * @property string|null $last_day_effective
 *
 * @property JobType $jobType
 */
class HourlyRate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hourly_rate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_type_id', 'rate', 'first_day_effective'], 'required'],
            [['job_type_id'], 'integer'],
            [['rate', 'summer_rate'], 'number'],
            [['first_day_effective', 'last_day_effective'], 'safe'],
            [['description'], 'string', 'max' => 500],
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
            'rate' => 'Rate',
            'summer_rate' => 'Summer Rate',
            'description' => 'Description',
            'first_day_effective' => 'First Day Effective',
            'last_day_effective' => 'Last Day Effective',
        ];
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
     * @return \app\models\query\HourlyRateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\HourlyRateQuery(get_called_class());
    }
}
