<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Part;

/**
 * PartSearch represents the model behind the search form of `app\models\Part`.
 * @property boolean|null $search_inactive_tickets
 */
class PartSearch extends Part
{
    public $search_inactive_tickets = false;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'part_type_id', 'quantity', 'pending_delivery'], 'integer'],
            [['part_number', 'ticket_id', 'last_modified_by_user_id', 'part_name', 'po_number', 'note', 'created', 'search_inactive_tickets', 'modified'], 'safe'],
            [['unit_price'], 'number'],
            [['search_inactive_tickets'], 'boolean'],
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
        $query = Part::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // TODO: Ignore validation because it doesn't like "search_inactive_tickets".
        // if (!$this->validate()) {
        //     // uncomment the following line if you do not want to return any records when validation fails
        //     // $query->where('0=1');
        //     return $dataProvider;
        // }

        // exact conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'part_number' => $this->part_number,
            'po_number' => $this->po_number,
            'pending_delivery' => $this->pending_delivery,
        ]);

        // in clause
        $query->andFilterWhere(['in', 'part_type_id', $this->part_type_id]);
        $query->andFilterWhere(['in', 'ticket_id', $this->ticket_id]);
        $query->andFilterWhere(['in', 'last_modified_by_user_id', $this->last_modified_by_user_id]);

        // like/contains
        $query->andFilterWhere([isset($params['part_name_order']) ? $params['part_name_order'] : '=', 'part_name', $this->part_name]);
        $query->andFilterWhere([isset($params['note_order']) ? $params['note_order'] : '=', 'note', $this->note]);
        
        // equ/grtrthn/lessthn
        $query->andFilterWhere([isset($params['quantity_order']) ? $params['quantity_order'] : '=', 'quantity', $this->quantity]);
        $query->andFilterWhere([isset($params['unit_price_order']) ? $params['unit_price_order'] : '=', 'unit_price', $this->unit_price]);
        $query->andFilterWhere([isset($params['created_order']) ? $params['created_order'] : '=', 'created', $this->created]);
        $query->andFilterWhere([isset($params['modified_order']) ? $params['modified_order'] : '=', 'modified', $this->modified]);
        
        $inactivePartsJobStatuses = [14, 15, 17];
        if ($this->search_inactive_tickets) {
            $query->innerJoin('ticket', 'part.ticket_id = ticket.id')
                ->andFilterWhere(['in', 'ticket.job_status_id', $inactivePartsJobStatuses]);
        } else {
            $query->innerJoin('ticket', 'part.ticket_id = ticket.id')
                ->andFilterWhere(['not in', 'ticket.job_status_id', $inactivePartsJobStatuses]);
        }

        return $dataProvider;
    }
}
