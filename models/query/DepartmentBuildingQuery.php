<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\DepartmentBuilding]].
 *
 * @see \app\models\DepartmentBuilding
 */
class DepartmentBuildingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\models\DepartmentBuilding[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\DepartmentBuilding|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
