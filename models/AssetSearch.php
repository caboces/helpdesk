<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Asset;
use yii\rbac\Assignment;

/**
 * AssetSearch represents the model behind the search form of `app\models\Asset`.
 */
class AssetSearch extends Asset
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ticket_id', 'new_prop_tag'], 'integer'],
            [['po_number'], 'string'],
            [['created', 'modified'], 'safe'],
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
        $query = Asset::find();

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
            'id' => $this->id,
            'ticket_id' => $this->ticket_id,
            'new_prop_tag' => $this->new_prop_tag,
            'po_number' => $this->po_number,
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        return $dataProvider;
    }
}
