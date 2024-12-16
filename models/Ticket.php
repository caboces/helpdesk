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
 * @property string $location
 * @property string $requester_email
 * @property string|null $requester_phone
 * 
 * @propert int $primary_tech_id
 * @property int|null $job_category_id
 * @property int|null $job_priority_id
 * @property int|null $job_status_id
 * @property int|null $job_type_id
 * @property int $status
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
            [['summary', 'requester', 'location', 'requester_email', 'job_category_id', 'job_priority_id', 'job_status_id', 'job_type_id', 'customer_type_id', 'status'], 'required'],
            [['primary_tech_id', 'job_category_id', 'job_priority_id', 'job_status_id', 'job_type_id', 'customer_type_id', 'district_id', 'district_building_id', 'department_building_id', 'division_id', 'department_id', 'status', 'soft_deleted_by_user_id'], 'integer'],
            [['created', 'modified', 'soft_deletion_timestamp'], 'safe'],
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
            'primary_tech_id' => 'Primary Technician',
            'users' => 'Assigned Technicians',
            'requester' => 'Requester Full Name',
            'location' => 'Additional Location Information',
            'requester_email' => 'Requester Email',
            'requester_phone' => 'Requester Phone',

            'job_category_id' => 'Job Category',
            'job_priority_id' => 'Job Priority',
            'job_status_id' => 'Job Status',
            'job_type_id' => 'Job Type',

            'customer_type_id' => 'Customer Type',
            'district_id' => 'District',
            'division_id' => 'Division',
            'department_id' => 'Department',
            'district_building_id' => 'District Building',
            'department_building_id' => 'Department Building',

            'soft_deleted_by_user_id' => 'Marked for Deletion By',
            'soft_deletion_timestamp' => 'Soft Deletion Date',
            'status' => 'Status',
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
     * Finds out who the primary tech is.
     */
    public function getPrimaryTech() {
        return $this->hasOne(User::class, ['id' => 'primary_tech_id']);
    }

    /**
     * Finds out who soft-deleted a ticket.
     */
    public function getSoftDeletedBy() {
        return $this->hasOne(User::class, ['id' => 'soft_deleted_by_user_id']);
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
     * Relation to get time entries attached to ticket via TimeEntry table
     */
    public function getTimeEntries() {
        return $this->hasMany(TimeEntry::class, ['ticket_id'=>'id']);
    }

    /**
     * Junction relation to get ticket equipments attached to a ticket via ticket_equipment table
     *
     * */
    public function getTicketEquipments()
    {
        return $this->hasMany(TicketEquipment::class,['id'=>'ticket_id'])->viaTable('{{%ticket_equipment}}',['ticket_id'=>'id']); 
    }

    /**
     * Junction relation to get parts attached to a ticket via part table
     *
     * */
    public function getParts()
    {
        return $this->hasMany(Part::class,['id'=>'ticket_id'])->viaTable('{{%part}}',['ticket_id'=>'id']); 
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
     * Get the master ticket summary query
     * 
     * @return yii\db\ActiveQuery
     */
    public static function getMasterTicketSummaryQuery() {
        return Ticket::find()->select(['ticket.id as ticket_id',
            'ticket.summary as summary',
            'ticket.requester as requester',
            'customer_type.name as customer_type',
            'district.name as district',
            'b1.name as district_building',
            'division.name as division',
            'department.name as department',
            'b2.name as department_building',
            'times.tech_time',
            'times.overtime',
            'times.travel_time',
            'times.itinerate_time'])
            ->leftJoin(
                // 'times' is the aggregate subquery
                ['times' => TimeEntry::find()->select(['time_entry.ticket_id as ticket_id', // must select ticket_id to make 'on' clause
                    'SUM(time_entry.tech_time) as tech_time',
                    'SUM(overtime) as overtime',
                    'SUM(travel_time) as travel_time',
                    'SUM(itinerate_time) as itinerate_time'])
                    ->groupBy('time_entry.ticket_id')], // must use 'groupBy' so the aggregate functions work since ticket_id is ambiguous in an aggregate context
                // on clause
                'times.ticket_id = ticket.id',
            )
            ->leftJoin('customer_type', 'ticket.customer_type_id = customer_type.id')
            ->leftJoin('division', 'ticket.division_id = division.id')
            ->leftJoin('department', 'ticket.department_id = department.id')
            ->leftJoin('district', 'ticket.district_id = district.id')
            ->leftJoin('department_building', 'ticket.department_building_id = department_building.id')
            ->leftJoin('district_building', 'ticket.district_building_id = district_building.id')
            ->leftJoin('building b1', 'district_building.building_id = b1.id')
            ->leftJoin('building b2', 'department_building.building_id = b2.id');
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

    public static function getJobLabels($ticketId) {
        return Ticket::find()
            ->select(['job_category' => 'job_category.name',
            'job_priority' => 'job_priority.name',
            'job_status' => 'job_status.name',
            'job_type' => 'job_type.name'])
            ->where(['ticket.id' => $ticketId])
            ->leftJoin('job_category', 'ticket.job_category_id = job_category.id')
            ->leftJoin('job_priority', 'ticket.job_priority_id = job_priority.id')
            ->leftJoin('job_status', 'ticket.job_status_id = job_status.id')
            ->leftJoin('job_type', 'ticket.job_type_id = job_type.id')
            ->asArray()
            ->one();
    }
    public static function getBillingDetailReportQueryDivisionDepartment($month, $year) {
        $startDay = $year . '-' . $month . '-01'; // start day
        $endDay = date('Y-m-t', strtotime($startDay));
        if ($month == '00') { // All months
            $startDay = $year.'-01-01';
            $endDay = $year.'-12-31';
        }
        return Ticket::find()->select([
            'customer_type.code AS code',
            'division.name AS division_name',
            'department.name AS department_name',
            'district.name AS district_name',
            'building.name AS building_name',
            'ticket.id',
            'ticket.summary',
            'ticket.requester',
            'times.total_hours',
            'part.parts_cost',
            'SUM(hourly_rate.rate * times.total_hours) + IFNULL(part.parts_cost, 0) AS total_cost',
        ])->innerJoin('customer_type', 'ticket.customer_type_id = customer_type.id')
        ->leftJoin('division', 'ticket.division_id = division.id')
        ->leftJoin('department', 'ticket.department_id = department.id')
        ->leftJoin('district', 'ticket.district_id = district.id')
        ->leftJoin('district_building', 'ticket.district_building_id = district_building.id')
        ->leftJoin('building', 'building.id = district_building.building_id')
        ->innerJoin([
            'times' => TimeEntry::find()->select([
                'time_entry.ticket_id',
                'total_hours' => 'IFNULL(SUM(`time_entry`.tech_time)
                    + SUM(`time_entry`.overtime)
                    + SUM(`time_entry`.travel_time)
                    + SUM(`time_entry`.itinerate_time), 0)'
            ])->where('time_entry.created BETWEEN \'' . $startDay . '\' AND \'' . $endDay . '\'')->groupBy('time_entry.ticket_id')->having('total_hours > 0')],
            'times.ticket_id = ticket.id'
        )->leftJoin([
            'part' => Part::find()->select([
                'part.ticket_id',
                'parts_cost' => 'IFNULL(SUM(`part`.unit_price * `part`.quantity), 0)'
            ])->groupBy('part.ticket_id')], 
            'ticket.id = part.ticket_id'
            // Use the hourly rate that was effective on the first of that month. How would you make it so time_entries split between two hourly_rates would work...?
        )->innerJoin('hourly_rate', 'ticket.job_type_id = hourly_rate.job_type_id AND :startDay BETWEEN hourly_rate.first_day_effective AND hourly_rate.last_day_effective', ['startDay' => $startDay])
        ->where('division.status = 10 OR district.status = 10')
        ->groupBy('ticket.id')
        ->having('total_cost > 0')
        ->orderBy('division.name ASC, department.name ASC');
    }
    public static function getSupportAndRepairLaborBillingReport($month, $year, $jobType) {
        $startDay = $year . '-' . $month . '-01'; // start day
        $endDay = date('Y-m-t', strtotime($startDay));
        if ($month == '00') { // All months
            $startDay = $year.'-01-01';
            $endDay = $year.'-12-31';
        }
        return Yii::$app->getDb()->createCommand("
            SELECT `customer_type`.`code` AS code,
            `division`.`name` AS `division_name`,
            `department`.`name` AS `department_name`,
            `district`.`name` AS `district_name`,
            `building`.`name` AS `building_name`,
            `times`.tech_time AS `Tech Time`,
            `times`.overtime AS `Overtime`,
            `times`.travel_time AS `Travel Time`,
            `times`.itinerate_time AS `Itinerate Time`
            FROM `ticket`
            INNER JOIN `customer_type`
                ON `customer_type`.`id` = `ticket`.`customer_type_id`
            LEFT JOIN `division`
                ON `ticket`.division_id = `division`.id
            LEFT JOIN `department`
                ON `ticket`.department_id = `department`.id
            LEFT JOIN `district`
                ON `ticket`.district_id = `district`.id
            LEFT JOIN `district_building`
                ON `district`.id = `district_building`.district_id
            LEFT JOIN `building`
                ON `district_building`.building_id = `building`.id
            INNER JOIN (
                SELECT `time_entry`.ticket_id,
                    SUM(`time_entry`.tech_time) AS `tech_time`,
                    SUM(`time_entry`.overtime) AS `overtime`,
                    SUM(`time_entry`.travel_time) AS `travel_time`,
                    SUM(`time_entry`.itinerate_time) AS `itinerate_time`
                FROM `time_entry`
                WHERE `time_entry`.created BETWEEN :startDay AND :endDay
                GROUP BY `time_entry`.ticket_id
            ) `times` ON `times`.ticket_id = `ticket`.id
            WHERE `ticket`.job_type_id = :jobType
            ORDER BY `customer_type`.name ASC, `division`.name, `department`.name, `district`.name, `department`.name
            ;", [':startDay' => $startDay, ':endDay' => $endDay, ':jobType' => $jobType])->queryAll();
    }

    public static function getPartBillingSummary($month, $year) {
        $startDay = $year . '-' . $month . '-01'; // start day
        $endDay = date('Y-m-t', strtotime($startDay));
        if ($month == '00') { // All months
            $startDay = $year.'-01-01';
            $endDay = $year.'-12-31';
        }
        return Ticket::find()->select([
            'customer_type.code',
            'division.name as division_name',
            'department.name as department_name',
            'district.name as district_name',
            'building.name as building_name',
            'parts.totals as parts_totals'
        ])->leftJoin('customer_type', 'ticket.customer_type_id = customer_type.id')
        ->leftJoin('division', 'ticket.division_id = division.id')
        ->leftJoin('department', 'ticket.department_id = department.id')
        ->leftJoin('district', 'ticket.district_id = district.id')
        ->leftJoin('district_building', 'ticket.district_building_id = district_building.id')
        ->leftJoin('building', 'building.id = district_building.building_id')->innerJoin([
            'parts' => Part::find()->select([
                'part.ticket_id',
                'totals' => 'IFNULL(SUM(part.unit_price * part.quantity), 0)'
            ])->where('part.created BETWEEN \'' . $startDay . '\' AND \'' . $endDay . '\'')->groupBy('part.ticket_id')->having('totals > 0')],
            'parts.ticket_id = ticket.id'
        )->orderBy('division.name, department.name, district.name, department.name');
    }

    public static function getCurrentTicketAssignmentsByUserId($id) {
        return Ticket::find()->select([
            'ticket.id as ticket_id',
            'division.name as division_name',
            'department.name as department_name',
            'district.name as district_name',
            'building.name as building_name',
            'ticket.summary',
            'ticket.description',
            'ticket.requester',
            'ticket.location'
        ])->leftJoin('customer_type', 'ticket.customer_type_id = customer_type.id')
        ->leftJoin('division', 'ticket.division_id = division.id')
        ->leftJoin('department', 'ticket.department_id = department.id')
        ->leftJoin('district', 'ticket.district_id = district.id')
        ->leftJoin('district_building', 'ticket.district_building_id = district_building.id')
        ->leftJoin('building', 'building.id = district_building.building_id')
        ->innerJoin('tech_ticket_assignment', 'ticket.id = tech_ticket_assignment.ticket_id')
        ->where('tech_ticket_assignment.user_id = ' . $id)
        ->where('ticket.status IN (9,11,12,13,16)') // ticket is open, waiting for parts, waiting on external third party, paused, waiting for response from requestor.
        ->groupBy('tech_ticket_assignment.ticket_id, tech_ticket_assignment.user_id')
        ->orderBy('division.name, department.name, district.name, department.name');
    }

    public static function getPastTicketAssignmentsByUserId($id) {
        return Ticket::find()->select([
            'ticket.id as ticket_id',
            'customer_type.code',
            'division.name as division_name',
            'department.name as department_name',
            'district.name as district_name',
            'building.name as building_name',
            'ticket.summary',
            'ticket.description',
            'ticket.requester',
            'ticket.location'
        ])->leftJoin('customer_type', 'ticket.customer_type_id = customer_type.id')
        ->leftJoin('division', 'ticket.division_id = division.id')
        ->leftJoin('department', 'ticket.department_id = department.id')
        ->leftJoin('district', 'ticket.district_id = district.id')
        ->leftJoin('district_building', 'ticket.district_building_id = district_building.id')
        ->leftJoin('building', 'building.id = district_building.building_id')
        ->innerJoin('tech_ticket_assignment', 'ticket.id = tech_ticket_assignment.ticket_id')
        ->where('tech_ticket_assignment.user_id = ' . $id)
        ->where('ticket.status NOT IN (9,11,12,13,16)') // ticket is submitted, resolved, closed, or resolved and billed
        ->groupBy('ticket_id')
        ->orderBy('division.name, department.name, district.name, department.name');
    }

    public static function getWnyricIpadRepairLaborReport($month, $year) {
        $startDay = $year . '-' . $month . '-01'; // start day
        $endDay = date('Y-m-t', strtotime($startDay));
        if ($month == '00') { // All months
            $startDay = $year.'-01-01';
            $endDay = $year.'-12-31';
        }
        return Ticket::find()->select([
            'ticket.id',
            'district.name',
            'ticket.description',
            'ticket.summary',
            'times.total_hours',
            'SUM(hourly_rate.rate * times.total_hours) AS total_cost',
            'ticket.requester',
        ])->innerJoin('district', 'district.id = ticket.district_id')
        ->innerJoin([
            'times' => TimeEntry::find()->select([
                'time_entry.ticket_id',
                'total_hours' => 'IFNULL(SUM(`time_entry`.tech_time)
                    + SUM(`time_entry`.overtime)
                    + SUM(`time_entry`.travel_time)
                    + SUM(`time_entry`.itinerate_time), 0)'
            ])->where('time_entry.created BETWEEN \'' . $startDay . '\' AND \'' . $endDay . '\'')->groupBy('time_entry.ticket_id')->having('total_hours > 0')],
            'times.ticket_id = ticket.id'
        )->innerJoin('hourly_rate', 'ticket.job_type_id = hourly_rate.job_type_id AND :startDay BETWEEN hourly_rate.first_day_effective AND hourly_rate.last_day_effective', ['startDay' => $startDay])
        ->where(['ticket.customer_type_id' => 2, 'ticket.job_category_id' => 25])
        ->groupBy('ticket.id')
        ->orderBy('district.name');
    }

    public static function getWnyricIpadPartsSummary($month, $year) {
        $startDay = $year . '-' . $month . '-01'; // start day
        $endDay = date('Y-m-t', strtotime($startDay));
        if ($month == '00') { // All months
            $startDay = $year.'-01-01';
            $endDay = $year.'-12-31';
        }
        return Ticket::find()->select([
            'ticket.id',
            'district.name',
            'ticket.description',
            'ticket.summary',
            'part.parts_cost',
        ])->innerJoin('district', 'district.id = ticket.district_id')
        ->innerJoin([
            'part' => Part::find()->select([
                'part.ticket_id',
                'parts_cost' => 'IFNULL(SUM(`part`.unit_price * `part`.quantity), 0)'
            ])->where('part.created BETWEEN \'' . $startDay . '\' AND \'' . $endDay . '\'')->groupBy('part.ticket_id')->having('parts_cost > 0')], 
            'ticket.id = part.ticket_id'
            // Use the hourly rate that was effective on the first of that month. How would you make it so time_entries split between two hourly_rates would work...?
        )->innerJoin('hourly_rate', 'ticket.job_type_id = hourly_rate.job_type_id AND :startDay BETWEEN hourly_rate.first_day_effective AND hourly_rate.last_day_effective', ['startDay' => $startDay])
        ->where(['ticket.customer_type_id' => 2, 'ticket.job_category_id' => 25])
        ->groupBy('ticket.id')
        ->orderBy('district.name');
    }

    public static function getWnyricIpadRepairBillingReport($month, $year) {

    }

    public static function getNonWnyricIpadRepairLaborReport($month, $year) {
        
    }
}