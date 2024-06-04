<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TimeEntry;

/**
 * TimeEntrySearch represents the model behind the search form of `app\models\TimeEntry`.
 */
class TimeEntrySearch extends TimeEntry
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'ticket_id', 'entry_creator_id'], 'integer'],
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
        $query = TimeEntry::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort = [
            // show the most recently added time entries
            'defaultOrder' => ['entry_date' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tech_time' => $this->tech_time,
            'overtime' => $this->overtime,
            'travel_time' => $this->travel_time,
            'itinerate_time' => $this->itinerate_time,
            'entry_date' => $this->entry_date,
            'user_id' => $this->user_id,
            'ticket_id' => $this->ticket_id,
            'entry_creator_id' => $this->entry_creator_id,
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        return $dataProvider;
    }
}
