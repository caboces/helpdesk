<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tech_ticket_assignment}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $ticket_id
 * 
 */
class TechTicketAssignment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tech_ticket_assignment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'ticket_id'], 'required'],
            [['user_id', 'ticket_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'ticket_id' => 'Ticket ID'
        ];
    }

    /**
     * Gets query for [[Ticket]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\Ticket
     */
    public function getTicket()
    {
        return $this->hasOne(Ticket::class, ['id' => 'ticket_id']);
    }

    /**
     * Gets query for [[User]].
     */
    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Find all assignments
     */
    public static function getTechAssignments() {
        return TechTicketAssignment::find()->asArray()->all();
    }

    /**
     * Gets all ids/names of assigned techs based on the ticket model
     * 
     * @return all tech usernames in an alphabetical array
     */
    public static function getTechNamesFromTicketId($model) {
        return TechTicketAssignment::find()
        ->select(['tech_ticket_assignment.id', 'tech_ticket_assignment.user_id', 'tech_ticket_assignment.ticket_id', 'user.username'])
        ->innerJoin ('user', 'tech_ticket_assignment.user_id = user.id')
        // this 'where' statement might be wrong...
        ->where(['ticket_id' => $model->id])
        ->orderBy('username ASC')
        ->asArray()
        ->all();
    }
}
