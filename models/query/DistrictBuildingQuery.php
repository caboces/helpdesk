<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\DistrictBuilding]].
 *
 * @see \app\models\DistrictBuilding
 */
class DistrictBuildingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\models\DistrictBuilding[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\DistrictBuilding|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
