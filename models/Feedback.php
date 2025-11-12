<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback_form".
 *
 * @property int $id
 * @property int|null $ticket_id
 * @property string $name
 * @property string $note
 * @property string $email
 * @property string $phone
 * @property string $ip_address
 * @property string $user_agent
 * @property string $accept_language
 * @property string $created
 *
 * @property Ticket $ticket
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'note', 'email', 'phone', 'ip_address', 'accept_language', 'user_agent'], 'required'],
            [['ticket_id'], 'integer'],
            [['note'], 'string', 'max' => 1500],
            [['created'], 'safe'],
            [['ip_address'], 'string', 'max' => 48],
            [['name', 'user_agent', 'accept_language'], 'string', 'max' => 255],
            [['email'], 'email', 'message' => 'Email must be a valid email address.'],
            [['email', 'phone'], 'string', 'max' => 100],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::class, 'targetAttribute' => ['ticket_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticket_id' => 'Ticket ID',
            'name' => 'Name',
            'note' => 'Note',
            'email' => 'Email',
            'phone' => 'Phone',
            'ip_address' => 'IP Address',
            'accept_language' => 'Accept Language',
            'user_agent' => 'User Agent',
            'created' => 'Created',
        ];
    }

    /**
     * Gets query for [[Ticket]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\TicketQuery
     */
    public function getTicket()
    {
        return $this->hasOne(Ticket::class, ['id' => 'ticket_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\FeedbackQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\FeedbackQuery(get_called_class());
    }
}
