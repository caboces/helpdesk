<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\JobType]].
 *
 * @see \app\models\JobType
 */
class JobTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\models\JobType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\JobType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
