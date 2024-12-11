<?php

namespace app\models;

use Yii;

use yii\base\model;
use app\models\Part;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'User ID',
            'username' => 'Username',
            'fname' => 'First Name',
            'lname' => 'Last Name',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * get username of user given their id
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public static function getUsers() {
        return User::find()->orderBy('username ASC')->asArray()->all();
    }

    /**
     * Junction relation to get tickets attached to a user via tech_ticket_assignment table
     *
     * */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class,['id'=>'ticket_id'])->viaTable('{{%tech_ticket_assignment}}',['user_id'=>'id']); 
    }

    /**
     * Junction relation to get parts added to tickets by a user via part table
     *
     * */
    public function getParts()
    {
        return $this->hasMany(Part::class,['id'=>'user_id'])->viaTable('{{%part}}',['user_id'=>'id']); 
    }

    /**
     * Relation to get activities attached to a user via activity table
     */
    public function getActivities() {
        return $this->hasMany(Activity::class, ['id'=>'user_id'])->viaTable('{{%activity}}', ['user_id'=>'id']);
    }

    public function getPassword() {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    /**
     * Get the technician monthly report -> tech times for each user
     */
    public static function getTechnicianMonthlyReport($month, $year) {
        $startDay = $year . '-' . $month . '-01'; // start day
        $endDay = date('Y-m-t', strtotime($startDay));
        return User::find()->select([
            'user.fname',
            'user.lname',
            'user.email',
            'SUM(times.tech_time) as tech_time',
            'SUM(times.overtime) as overtime',
            'SUM(times.travel_time) as travel_time',
            'SUM(times.itinerate_time) as itinerate_time'
        ])->innerJoin('tech_ticket_assignment', 'user.id = tech_ticket_assignment.user_id')
        ->innerJoin(
            // 'times' is the aggregate subquery
            ['times' => TimeEntry::find()->select([
                'time_entry.ticket_id as ticket_id', // must select ticket_id to make 'on' clause
                'time_entry.user_id as user_id',
                'SUM(time_entry.tech_time) as tech_time',
                'SUM(overtime) as overtime',
                'SUM(travel_time) as travel_time',
                'SUM(itinerate_time) as itinerate_time'
            ])->groupBy(['time_entry.ticket_id','time_entry.user_id'])
                ->having('tech_time > 0 OR overtime > 0 OR travel_time > 0 OR itinerate_time > 0')
                ->where('time_entry.created BETWEEN \'' . $startDay . '\' AND \'' . $endDay . '\'')], // must use 'groupBy' so the aggregate functions work since ticket_id is ambiguous in an aggregate context
            // on clause
            'times.ticket_id = tech_ticket_assignment.ticket_id AND times.user_id = tech_ticket_assignment.user_id',
        )->groupBy('user.id') // group by user name
        ->orderBy('user.lname asc, user.fname asc');
    }
}