<?php

namespace app\controllers;

use app\models\HourlyRate;
use app\models\Ticket;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * ReportsController
 */
class ReportsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'blank-container';
        return $this->render('index', [
            // placeholder
        ]);
    }

    /**
     * Displays the Billing Detail Report
     *
     * @return mixed
     */
    public function actionBillingDetailReport()
    {
        // $ticketQuery = Ticket::find()->getBillingDetailReportQuery();
        $ticketQuery = Ticket::find()->with('timeEntries');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $ticketQuery
        ]);

        // $dataProvider = new ArrayDataProvider([
        //     'allModels' => $ticketQuery->asArray()->all()
        // ]);

        // dd($dataProvider->getModels());

        $gridColumns = [
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute(['/ticket/view', 'id' => $model->id]);
                }
            ],
            [
                'attribute' => 'ticket_id',
                'label' => 'Ticket ID',
            ],
            [
                'attribute' => 'billed',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->billed?
                            // checkmark (doesnt work)
                        Html::decode('<svg width="16" height="16" class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>')
                            :
                            // cross/x/cancel (works)
                        Html::decode('<svg fill="#000000" width="16px" height="16px" viewBox="0 0 24 24" data-name="Line Color" xmlns="http://www.w3.org/2000/svg" class="icon line-color"><line id="primary" x1="19" y1="19" x2="5" y2="5" style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></line><line id="primary-2" data-name="primary" x1="19" y1="5" x2="5" y2="19" style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></line></svg>');
                },
                'label' => 'Billed'
            ],
            'summary',
            'requester',
            [
                'attribute' => 'customer_type',
                'value' => function($model) {
                    return $model->customerType? $model->customerType->name : '';
                }
            ],
            'tech_time',
            'overtime',
            'travel_time',
            'itinerate_time',
        ];

        $laborRatesDataProvider = new ActiveDataProvider([
            'query' => HourlyRate::find()->with('jobType')
                ->where('\'' . date('Y-m-d') . '\' between first_day_effective and last_day_effective'),
            'sort' => [
                'defaultOrder' => [
                    'last_day_effective' => SORT_ASC
                ]
            ]
        ]);

        $laborRatesColumns = [
            [
                'attribute' => 'job_type_name',
                'value' => function($model) {
                    return $model->jobType->name;
                },
                'label' => 'Job Type'
            ],
            [
                'attribute' => 'rate',
                'value' => function($model) {
                    return $model->rate? '$' . $model->rate : ''; 
                }
            ],
            [
                'attribute' => 'summer_rate',
                'value' => function($model) {
                    return $model->summer_rate? '$' . $model->summer_rate : '';
                }
            ],
            'first_day_effective', 
            'last_day_effective'
        ];

        $this->layout = 'blank';
        return $this->render('billing-detail-report', [
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns,
            'laborRatesDataProvider' => $laborRatesDataProvider,
            'laborRatesColumns' => $laborRatesColumns,
        ]);
    }

    /**
     * Displays the Support and Repair Labor Billing
     * This probably won't be useful, I am using it to test the exporting feature.
     *
     * @return mixed
     */
    public function actionMasterTicketSummary()
    {
        $query = Ticket::getMasterTicketSummaryQuery();
        
        // use an array data provider instead since ActiveDataProvider is causing some weird issues:
        // we are likely missing or incorrectly implemented the 'hasOne' or 'hasMany' relationships in ticket/district/division/building/time_entry
        // because this would take a while to find out, i opt for an ArrayDataProvider instead, which is much more simple and relies on the aliases for each
        // field in the select query. - TW
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->asArray()->all()
        ]);

        $gridColumns = [
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute(['/ticket/view', 'id' => $model['ticket_id']]);
                }
            ],
            [
                'attribute' => 'ticket_id',
                'label' => 'Ticket ID', // 'd' wont be capitalized if only 'ticket_id' is provided to the gridColumn array
            ],
            'summary',
            'requester',
            'customer_type',
            'district',
            'district_building',
            'division',
            'department',
            'department_building',
            'tech_time',
            'overtime',
            'travel_time',
            'itinerate_time'
        ];

        $this->layout = 'blank';
        return $this->render('master-ticket-summary', [
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns
        ]);
    }

    /**
     * Displays the Support and Repair Labor Billing
     *
     * @return mixed
     */
    public function actionSupportAndRepairLaborBilling()
    {
        // $dataProvider = 

        $this->layout = 'blank';
        return $this->render('support-and-repair-labor-billing');
    }
}