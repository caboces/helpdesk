<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ticket;

/**
 * TicketSearch represents the model behind the search form of `app\models\Ticket`.
 */
class TicketSearch extends Ticket
{
    public $job_category_name;
    public $job_priority_name;
    public $job_priority_level;
    public $job_status_name;
    public $job_status_level;
    public $job_type_name;
    public $primary_tech_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'job_category_id', 'job_priority_id', 'job_status_id', 'job_type_id', 'job_priority_level', 'primary_tech_id'], 'integer'],
            [['summary', 'description', 'created', 'modified', 'job_category_name', 'job_priority_name', 'job_priority_level', 'job_status_name', 'job_status_level', 'job_type_name'], 'safe'],
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
        $query = Ticket::find()->joinWith([
            // writing this because it caused me a headache, you NEED to add the alias here or it won't work.
            // e.g., 'books b' where b is the alias.
            'jobCategory jobCategory',
            'jobPriority jobPriority',
            'jobStatus jobStatus',
            'jobType jobType',
            'primaryTech primaryTech',
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort = [
            // show the most recently modified tickets first
            'defaultOrder' => ['job_priority_level' => SORT_DESC],
            'attributes' => [
                'job_priority_level' => [
                    'asc' => ['jobPriority.level' => SORT_ASC],
                    'desc' => ['jobPriority.level' => SORT_DESC],
                ],
                'job_status_level' => [
                    'asc' => ['jobStatus.level' => SORT_ASC],
                    'desc' => ['jobStatus.level' => SORT_DESC],
                ],
                'created',
                'modified'
            ]
        ];

        $this->load($params);

        if (!($this->validate())) {
            // uncomment the following line if you do not want to return any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'job_category_id' => $this->job_category_id,
            'job_priority_id' => $this->job_priority_id,
            'job_status_id' => $this->job_status_id,
            'job_type_id' => $this->job_type_id,
            'created' => $this->created,
            'modified' => $this->modified,
            // filter out soft-deletion tickets
            'ticket.status' => '10'
        ]);

        $query->andFilterWhere(['LIKE', 'summary', $this->summary])
            ->andFilterWhere(['LIKE', 'jobCategory.name', $this->job_category_name])
            ->andFilterWhere(['LIKE', 'jobPriority.name', $this->job_priority_name])
            ->andFilterWhere(['LIKE', 'jobStatus.name', $this->job_status_name])
            ->andFilterWhere(['LIKE', 'jobType.name', $this->job_type_name]);

        return $dataProvider;
    }
}
