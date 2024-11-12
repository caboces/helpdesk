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
            [['borrower_name', 'borrower_email', 'borrower_phone', 'borrower_loc', 'borrower_reason'], 'string', 'max' => 45],
            [['date_borrowed', 'date_returned'], 'date']
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
            'date_borrowed' => $this->date_borrowed,
            'date_returned' => $this->date_returned,
            'borrower_name' => $this->borrower_name,
            'borrower_email' => $this->borrower_email,
            'borrower_phone' => $this->borrower_phone,
            'borrower_loc' => $this->borrower_loc,
            'borrower_reason' => $this->borrower_reason
        ]);

        $query->andFilterWhere(['like', 'date_borrowed', $this->date_borrowed])
            ->andFilterWhere(['like', 'date_returned', $this->date_returned])
            ->andFilterWhere(['like', 'borrower_name', $this->borrower_name])
            ->andFilterWhere(['like', 'borrower_email', $this->borrower_email])
            ->andFilterWhere(['like', 'borrower_phone', $this->borrower_phone])
            ->andFilterWhere(['like', 'borrower_loc', $this->borrower_loc])
            ->andFilterWhere(['like', 'borrower_reason', $this->borrower_reason]);
        
        return $dataProvider;
    }
}