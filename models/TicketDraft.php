<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket_draft".
 *
 * @property int $id
 * @property int $customer_type_id
 * @property string $requestor
 * @property int|null $division_id
 * @property int|null $department_id
 * @property int|null $department_building_id
 * @property int|null $district_id
 * @property int|null $district_building_id
 * @property string $location
 * @property string $summary
 * @property string $description
 * @property string $email
 * @property string $phone
 * @property int $frozen
 * @property string $ip_address
 * @property string $accept_language
 * @property string $user_agent
 * @property string|null $created
 * @property string|null $modified
 *
 * @property Department $department
 * @property DepartmentBuilding $departmentBuilding
 * @property District $district
 * @property DistrictBuilding $districtBuilding
 * @property Division $division
 */
class TicketDraft extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket_draft';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['requestor', 'customer_type_id','location', 'summary', 'description', 'email', 'phone', 'ip_address', 'accept_language', 'user_agent'], 'required'],
            [['division_id', 'department_id', 'department_building_id', 'district_id', 'district_building_id', 'frozen'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['requestor', 'location'], 'string', 'max' => 100],
            [['summary'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 500],
            [['email', 'phone'], 'string', 'max' => 45],
            [['email'], 'email', 'message' => 'Email must be a valid email address.'],
            [['ip_address'], 'string', 'max' => 48],
            [['accept_language', 'user_agent'], 'string', 'max' => 255],
            [['customer_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerType::class, 'targetAttribute' => ['customer_type_id' => 'id']],
            [['department_building_id'], 'exist', 'skipOnError' => true, 'targetClass' => DepartmentBuilding::class, 'targetAttribute' => ['department_building_id' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['department_id' => 'id']],
            [['district_building_id'], 'exist', 'skipOnError' => true, 'targetClass' => DistrictBuilding::class, 'targetAttribute' => ['district_building_id' => 'id']],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::class, 'targetAttribute' => ['district_id' => 'id']],
            [['division_id'], 'exist', 'skipOnError' => true, 'targetClass' => Division::class, 'targetAttribute' => ['division_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_type_id' => 'Customer Type',
            'requestor' => 'Requestor',
            'division_id' => 'Division',
            'department_id' => 'Department',
            'department_building_id' => 'Department Building',
            'district_id' => 'District',
            'district_building_id' => 'District Building',
            'location' => 'Location',
            'summary' => 'Summary',
            'description' => 'Description',
            'email' => 'Email',
            'phone' => 'Phone',
            'frozen' => 'Frozen',
            'ip_address' => 'IP Address',
            'accept_language' => 'Accept Language',
            'user_agent' => 'User Agent',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
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
     * Gets query for [[DepartmentBuilding]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DepartmentBuildingQuery
     */
    public function getDepartmentBuilding()
    {
        return $this->hasOne(DepartmentBuilding::class, ['id' => 'department_building_id']);
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
     * Gets query for [[DistrictBuilding]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\DistrictBuildingQuery
     */
    public function getDistrictBuilding()
    {
        return $this->hasOne(DistrictBuilding::class, ['id' => 'district_building_id']);
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
     * Gets query for [[CustomerType]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\CustomerTypeQuery
     */
    public function getCustomerType()
    {
        return $this->hasOne(CustomerType::class, ['id' => 'customer_type_id']);
    }

    /**
     * Sets the frozen field for this ticket draft to 1
     */
    public function freeze() {
        $this->frozen = 1;
        $this->save();
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\TicketDraftQuery the active query used by this AR class.
     */
    public static function find() {
        return new \app\models\query\TicketDraftQuery(get_called_class());
    }
}
