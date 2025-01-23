<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "blocked_ip_address".
 *
 * @property int $id
 * @property string $ip_address
 * @property string|null $reason
 * @property string|null $created
 * @property string|null $modified
 */
class BlockedIpAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blocked_ip_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip_address'], 'required'],
            [['created', 'modified'], 'safe'],
            [['ip_address'], 'string', 'max' => 48],
            [['reason'], 'string', 'max' => 100],
            [['ip_address'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip_address' => 'Ip Address',
            'reason' => 'Reason',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }
}
