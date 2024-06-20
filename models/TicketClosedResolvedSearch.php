<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Ticket;
use yii\data\ActiveDataProvider;

/**
 * TicketSearch represents the model behind the search form of `app\models\Ticket`.
 */
class TicketClosedResolvedSearch extends TicketSearch
{
    public $job_category_name;
    public $job_priority_name;
    public $job_priority_level;
    public $job_status_name;
    public $job_status_level;
    public $job_type_name;

    // rules + scenarios in parent

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
        ]);

        $ticketClosedResolvedDataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $ticketClosedResolvedDataProvider->sort = [
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

        // if (!($this->load($params) && $this->validate())) {
        if (!($this->validate())) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $ticketClosedResolvedDataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'job_category_id' => $this->job_category_id,
            'job_priority_id' => $this->job_priority_id,

            // TODO: this needs to become an or statement. Use the job level to filter out jobs at a level below resolved (level 7)
            'job_status_id' => $this->job_status_id,

            'job_type_id' => $this->job_type_id,
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['LIKE', 'summary', $this->summary])
            ->andFilterWhere(['LIKE', 'jobCategory.name', $this->job_category_name])
            ->andFilterWhere(['LIKE', 'jobPriority.name', $this->job_priority_name])
            ->andFilterWhere(['LIKE', 'jobStatus.name', $this->job_status_name])
            ->andFilterWhere(['LIKE', 'jobType.name', $this->job_type_name]);

        return $ticketClosedResolvedDataProvider;
    }
}
