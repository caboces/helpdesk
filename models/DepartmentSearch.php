<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Department;

/**
 * DepartmentSearch represents the model behind the search form of `app\models\Department`.
 */
class DepartmentSearch extends Department
{
    public $district_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'district_id', 'status'], 'integer'],
            [['name', 'description', 'district_name', 'created', 'modified'], 'safe'],
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
        $query = Department::find()->joinWith([
            'district district',
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $dataProvider->sort = [
            // show districts alphabetically
            'attributes' => [
                'district_name' => [
                    'asc' => ['district.name' => SORT_ASC],
                    'desc' => ['district.name' => SORT_DESC],
                ],
                'created',
                'modified'
            ],
        ];

        $this->load($params);

        if (!($this->load($params) && $this->validate())) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'district_id' => $this->district_id,
            'status' => $this->status,
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['LIKE', 'district.name', $this->district_name]);

        return $dataProvider;
    }
}
