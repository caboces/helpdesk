<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_type".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property int|null $bill_using_coser
 *
 * @property Ticket[] $tickets
 */
class CustomerType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['bill_using_coser'], 'integer'],
            [['code', 'name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'description' => 'Description',
            'bill_using_coser' => 'Bill Using Coser',
        ];
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\TicketQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['customer_type_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\CustomerTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\CustomerTypeQuery(get_called_class());
    }
}
