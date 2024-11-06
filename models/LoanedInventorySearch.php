<?php

namespace app\models;

use yii\base\Model;
use yii\date\ActiveDataProvider;
use app\models\LoanedInventory;
use yii\data\ActiveDataProvider as DataActiveDataProvider;

class LoanedInventorySearch extends LoanedInventory {
    /**
     * {@inheritdoc}
     */
    public function ruless() {
        return [
            [['id', 'new_prop_tag'], 'integer'],
            [['bl_code'], 'string'],
            [['date_borrowed', 'date_returned'], 'datetime']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * 
     * @param array @params
     * 
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = LoanedInventory::findWithInventoryInformation();

        $dataProvider = new DataActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider; 
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'new_prop_tag' => $this->new_prop_tag, 
            'bl_code' => $this->bl_code,
            'date_borrowed' => $this->date_borrowed,
            'date_returned' => $this->date_returned
        ]);

        $query->andFilterWhere(['like', 'bl_code', $this->bl_code])
            ->andFilterWhere(['like', 'date_borrowed', $this->date_borrowed])
            ->andFilterWhere(['like', 'date_returned', $this->date_returned]);
        
        return $dataProvider;
    }
}