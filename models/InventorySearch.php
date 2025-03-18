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
            [['new_prop_tag', 'tagged', 'qty', 'date_purchased_num', 'old_prop_tag', 'donated_to_boces', 'has_inv'], 'integer'],
            [['fund_id', 'bl_code', 'class_id', 'vendor_id', 'item_description', 'serial_number', 'purchased_date', 'po', 'delete_status', 'date_deleted', 'deleted_date', 'date_donated', 'donated_date', 'entry_date', 'last_modified_date', 'delete_code', 'useful_life'], 'safe'],
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
           return $dataProvider;
        }

        // text input, exact conditions
        $query->andFilterWhere([
            'new_prop_tag' => $this->new_prop_tag,
            'serial_number' => $this->serial_number,
            'po' => $this->po,
            'tagged' => $this->tagged,
            'old_prop_tag' => $this->old_prop_tag,
            'donated_to_boces' => $this->donated_to_boces,
        ]);

        // multiple selections/select2/IN clause
        // dd($this);
        $query->andFilterWhere(['in', 'fund_id', $this->fund_id]);
        $query->andFilterWhere(['in', 'bl_code', $this->bl_code]);
        $query->andFilterWhere(['in', 'delete_code', $this->delete_code]);
        $query->andFilterWhere(['in', 'class_id', $this->class_id]);
        $query->andFilterWhere(['in', 'vendor_id', $this->vendor_id]);
        $query->andFilterWhere(['in', 'useful_life', $this->useful_life]);
        $query->andFilterWhere(['in', 'delete_status', $this->delete_status]);

        // like/contains
        if (isset($params['item_description_order'])) {
            $query->andFilterWhere([$params['item_description_order'], 'item_description', $this->item_description]);
        }

        // comparisons
        if (isset($params['purchased_date_order'])) {
            $query->andFilterCompare('purchased_date', $this->purchased_date, $params['purchased_date_order']);
        }
        if (isset($params['unit_price_order'])) {
            $query->andFilterCompare('unit_price', $this->unit_price, $params['unit_price_order']);
        }
        if (isset($params['total_price_order'])) {
            $query->andFilterCompare('total_price', $this->total_price, $params['total_price_order']);
        }
        if (isset($params['date_deleted_order'])) {
            $query->andFilterCompare('date_deleted', $this->date_deleted, $params['date_deleted_order']);
        }
        if (isset($params['donated_date_order'])) {
            $query->andFilterCompare('donated_date', $this->donated_date, $params['donated_date_order']);
        }
        if (isset($params['entry_date_order'])) {
            $query->andFilterCompare('entry_date', $this->entry_date, $params['entry_date_order']);
        }
        if (isset($params['last_modified_date_order'])) {
            $query->andFilterCompare('last_modified_date', $this->last_modified_date, $params['last_modified_date_order']);
        }

        // NOTE: 'qty' is only ever 1 in the database, so its irrelevant
        // $query->andFilterCompare('qty', $this->qty, $params['qty_order']);

        return $dataProvider;
    }
}
