<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Inventory;

/**
 * InventorySearch represents the model behind the search form of `app\models\Inventory`.
 */
class InventorySearch extends Inventory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['new_prop_tag', 'delete_code', 'tagged', 'qty', 'date_purchased_num', 'useful_life', 'old_prop_tag', 'donated_to_boces', 'has_inv'], 'integer'],
            [['fund_id', 'bl_code', 'class_id', 'vendor_id', 'item_description', 'serial_number', 'purchased_date', 'po', 'delete_status', 'date_deleted', 'deleted_date', 'date_donated', 'donated_date', 'entry_date', 'last_modified_date'], 'safe'],
            [['unit_price', 'total_price'], 'number'],
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
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Inventory::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'new_prop_tag' => $this->new_prop_tag,
            'delete_code' => $this->delete_code,
            'tagged' => $this->tagged,
            'qty' => $this->qty,
            'purchased_date' => $this->purchased_date,
            'date_purchased_num' => $this->date_purchased_num,
            'unit_price' => $this->unit_price,
            'total_price' => $this->total_price,
            'useful_life' => $this->useful_life,
            'deleted_date' => $this->deleted_date,
            'old_prop_tag' => $this->old_prop_tag,
            'donated_to_boces' => $this->donated_to_boces,
            'donated_date' => $this->donated_date,
            'has_inv' => $this->has_inv,
            'entry_date' => $this->entry_date,
            'last_modified_date' => $this->last_modified_date,
        ]);

        $query->andFilterWhere(['like', 'fund_id', $this->fund_id])
            ->andFilterWhere(['like', 'bl_code', $this->bl_code])
            ->andFilterWhere(['like', 'class_id', $this->class_id])
            ->andFilterWhere(['like', 'vendor_id', $this->vendor_id])
            ->andFilterWhere(['like', 'item_description', $this->item_description])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'po', $this->po])
            ->andFilterWhere(['like', 'delete_status', $this->delete_status])
            ->andFilterWhere(['like', 'date_deleted', $this->date_deleted])
            ->andFilterWhere(['like', 'date_donated', $this->date_donated]);

        return $dataProvider;
    }
}
