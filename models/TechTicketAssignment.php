<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tech_ticket_assignment}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $ticket_id
 * @property int $primary_tech
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
            [['user_id', 'ticket_id', 'primary_tech'], 'required'],
            [['user_id', 'ticket_id', 'primary_tech'], 'integer'],
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
            'ticket_id' => 'Ticket ID',
            'primary_tech' => 'Primary Tech',
        ];
    }

    public static function getTechAssignments() {
        return TechTicketAssignment::find()->asArray()->all();
    }
}
