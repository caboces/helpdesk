<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ticket}}".
 *
 * @property int $id
 * @property string $summary
 * @property string|null $description
 * @property string $requester
 * @property string $requester
 * @property string $location
 * @property string $requester_email
 * @property string|null $requester_phone
 * 
 * @property int|null $job_category_id
 * @property int|null $job_priority_id
 * @property int|null $job_status_id
 * @property int|null $job_type_id
 * @property string $created
 * @property string $modified
 *
 * @property JobCategory $jobCategory
 * @property JobPriority $jobPriority
 * @property JobStatus $jobStatus
 * @property JobType $jobType
 * 
 * @property CustomerType $customerType
 * @property District $district
 * @property DistrictBuilding $districtBuilding
 * @property DepartmentBuilding $departmentBuilding
 * @property Division $division
 * @property Department $department
 * 
 */
class Ticket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ticket}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['summary', 'requester', 'location', 'requester_email', 'job_category_id', 'job_priority_id', 'job_status_id', 'job_type_id', 'customer_type_id'], 'required'],
            [['job_category_id', 'job_priority_id', 'job_status_id', 'job_type_id', 'customer_type_id', 'district_id', 'district_building_id', 'department_building_id', 'division_id', 'department_id'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['summary'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 500],
            [['requester'], 'string', 'max' => 100],
            [['location'], 'string', 'max' => 100 ],
            [['requester_email'], 'string', 'max' => 100],
            [['requester_phone'], 'string', 'max' => 100],
            
            [['job_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => JobCategory::class, 'targetAttribute' => ['job_category_id' => 'id']],
            [['job_priority_id'], 'exist', 'skipOnError' => true, 'targetClass' => JobPriority::class, 'targetAttribute' => ['job_priority_id' => 'id']],
            [['job_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => JobStatus::class, 'targetAttribute' => ['job_status_id' => 'id']],
            [['job_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => JobType::class, 'targetAttribute' => ['job_type_id' => 'id']],
            
            [['customer_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerType::class, 'targetAttribute' => ['customer_type_id' => 'id']],
            [['district'], 'exist', 'skipOnError' => true, 'targetClass' => District::class, 'targetAttribute' => ['district_id' => 'id']],
            [['division'], 'exist', 'skipOnError' => true, 'targetClass' => Division::class, 'targetAttribute' => ['division_id' => 'id']],
            [['department'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['department_id' => 'id']],
            [['district_building_id'], 'exist', 'skipOnError' => true, 'targetClass' => DistrictBuilding::class, 'targetAttribute' => ['district_building_id' => 'id']],
            [['department_building_id'], 'exist', 'skipOnError' => true, 'targetClass' => DepartmentBuilding::class, 'targetAttribute' => ['department_building_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'summary' => 'Summary',
            'description' => 'Description',
            'requester' => 'Requester',
            'location' => 'Location',
            'requester_email' => 'Requester Email',
            'requester_phone' => 'Requester Phone',

            'job_category_id' => 'Job Category ID',
            'job_priority_id' => 'Job Priority ID',
            'job_status_id' => 'Job Status ID',
            'job_type_id' => 'Job Type ID',

            'customer_type_id' => 'Customer Type ID',
            'district_id' => 'District ID',
            'division_id' => 'Division ID',
            'department_id' => 'Department ID',
            'district_building_id' => 'District Building ID',
            'department_building_id' => 'Department Building ID',

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
     * Gets query for [[JobPriority]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\JobPriorityQuery
     */
    public function getJobPriority()
    {
        return $this->hasOne(JobPriority::class, ['id' => 'job_priority_id']);
    }

    /**
     * Gets query for [[JobStatus]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\JobStatusQuery
     */
    public function getJobStatus()
    {
        return $this->hasOne(JobStatus::class, ['id' => 'job_status_id']);
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
     * Gets query for [[CustomerType]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\CustomerTypeQuery
     */
    public function getCustomerType()
    {
        return $this->hasOne(CustomerType::class, ['id' => 'customer_type_id']);
    }

    /**
     * Gets query for [[DistrictBuilding]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DistrictBuildingQuery
     */
    public function getDistrictBuilding()
    {
        return $this->hasOne(DistrictBuilding::class, ['id' => 'district_building_id']);
    }

        /**
     * Gets query for [[DepartmentBuilding]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DepartmentBuildingQuery
     */
    public function getDepartmentBuilding()
    {
        return $this->hasOne(DepartmentBuilding::class, ['id' => 'department_building_id']);
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
     * Gets query for [[District]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DistrictQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::class, ['id' => 'district_id']);
    }

    /**
     * Gets query for [[Division]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DivisionQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::class, ['id' => 'division_id']);
    }

    /**
     * Junction relation to get users attached to a ticket via tech_ticket_assignment table
     *
     * */
    public function getUsers()
    {
        return $this->hasMany(User::class,['id'=>'user_id'])->viaTable('{{%tech_ticket_assignment}}',['ticket_id'=>'id']); 
    }

    /**
     * Relation to get activities attached to ticket via activity table
     */
    public function getActivities() {
        return $this->hasMany(Activity::class, ['id'=>'ticket_id'])->viaTable('{{%activity}}', ['ticket_id'=>'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\TicketQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\TicketQuery(get_called_class());
    }

    /**
     * Trims string to length (150) and appends something (ellipsis).
     * Made to trim descriptions in tickets.
     * TODO: I really want this to be more reusable. Make $string $append
     * and $length params and pass values in controller to be passed to
     * the view?
     * @return $string
     */
    public function shortenedDesc() {
        $string = $this->description;
        $append = "...";
        $length = 150;

        $string = trim($string);

        if (strlen($string) > $length) {
            $string = wordwrap($string, $length);
            $string = explode("\n", $string, 2);
            $string = $string[0] . $append;
        }

        return $string;
    }
}
