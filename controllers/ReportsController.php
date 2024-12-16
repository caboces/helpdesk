<?php

namespace app\controllers;

use app\models\HourlyRate;
use app\models\JobType;
use app\models\Part;
use app\models\Ticket;
use app\models\User;
use kartik\grid\ExpandRowColumn;
use kartik\grid\GridView as GridGridView;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
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

        $month = Yii::$app->getRequest()->getQueryParam('month', date('n'));
        $year = Yii::$app->getRequest()->getQueryParam('year', date('Y'));
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getBillingDetailReportQueryDivisionDepartment($month, $year)->asArray()->all()
        ]);
        $gridColumns = [
            [
                'attribute' => 'code',
                'label' => 'Code'
            ],
            [
                'label' => 'Division > Department / District > Building',
                'format' => 'raw',
                'value' => function($model) {
                    if ($model['code'] == 'BOCES') {
                        return Html::decode($model['division_name'].'&nbsp;>&nbsp;'.$model['department_name']);
                    } else if ($model['code'] == 'DISTRICT') {
                        return Html::decode($model['district_name'].'&nbsp;>&nbsp;'.$model['building_name']);
                    } else if ($model['code'] == 'EXTERNAL') {
                        return Html::decode($model['district_name'].'&nbsp;>&nbsp;'.$model['building_name']);
                    }
                }
            ],
            [
                'attribute' => 'id',
                'label' => 'Ticket ID'
            ],
            [
                'attribute' => 'summary',
                'label' => 'Summary'
            ],
            [
                'attribute' => 'requester',
                'label' => 'Requester'
            ],
            [
                'attribute' => 'total_hours',
                'label' => 'Total Hours'
            ],
            [
                'attribute' => 'parts_cost',
                'value' => function($model) {
                    return Yii::$app->formatter->asCurrency($model['parts_cost'], '$');
                },
                'label' => 'Parts Cost'
            ],
            [
                'attribute' => 'total_cost',
                'value' => function($model) {
                    return Yii::$app->formatter->asCurrency($model['total_cost'], '$');
                },
                'label' => 'Total Cost'
            ]
        ];

        $this->layout = 'blank';
        return $this->render('billing-detail-report', [
            'year' => $year,
            'month' => $month,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns,
            'laborRatesDataProvider' => $laborRatesDataProvider,
            'laborRatesColumns' => $laborRatesColumns,
        ]);
    }

    /**
     * displays the master ticket summary 
     * This probably won't be useful, I am using it to test the exporting feature.
     *
     * @return mixed
     */
    public function actionMasterTicketSummary()
    {
        $month = Yii::$app->getRequest()->getQueryParam('month', date('n'));
        $year = Yii::$app->getRequest()->getQueryParam('year', date('Y'));
        $query = Ticket::getMasterTicketSummaryQuery();
        
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
            'month' => $month,
            'year' => $year,
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
        $month = Yii::$app->getRequest()->getQueryParam('month', date('n'));
        $year = Yii::$app->getRequest()->getQueryParam('year', date('Y'));
        $jobType = Yii::$app->getRequest()->getQueryParam('jobType', 5); // 'Troubleshoot' in job_type table
        $jobTypes = ArrayHelper::map(JobType::find()->where(['id' => [5, 7]])->asArray()->all(), 'id', 'name'); // 5 and 7 are 'Troubleshoot' and 'Audio Visual Production'
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getSupportAndRepairLaborBillingReport($month, $year, $jobType)
        ]);

        $gridColumns = [
            [
                'attribute' => 'code'
            ],
            [
                'label' => 'Division > Department / District > Building',
                'format' => 'raw',
                'value' => function($model) {
                    if ($model['code'] == 'BOCES') {
                        return Html::decode($model['division_name'].'&nbsp;>&nbsp;'.$model['department_name']);
                    } else if ($model['code'] == 'DISTRICT') {
                        return Html::decode($model['district_name'].'&nbsp;>&nbsp;'.$model['building_name']);
                    } else if ($model['code'] == 'EXTERNAL') {
                        return Html::decode($model['district_name'].'&nbsp;>&nbsp;'.$model['building_name']);
                    }
                }
            ],
            [
                'attribute' => 'Tech Time',
                'label' => 'Tech Time'
            ],
            [
                'attribute' => 'Overtime',
                'label' => 'Overtime'
            ],
            [
                'attribute' => 'Travel Time',
                'label' => 'Travel Time'
            ],
            [
                'attribute' => 'Itinerate Time',
                'label' => 'Itinerate Time'
            ]
        ];

        $this->layout = 'blank';
        return $this->render('support-and-repair-labor-billing', [
            'month' => $month,
            'year' => $year,
            'jobType' => $jobType,
            'jobTypes' => $jobTypes,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns
        ]);
    }

    public function actionTechnicianMonthlyReport() {
        $month = Yii::$app->getRequest()->getQueryParam('month', date('n'));
        $year = Yii::$app->getRequest()->getQueryParam('year', date('Y'));
        $dataProvider = new ArrayDataProvider([
            'allModels' => User::getTechnicianMonthlyReport($month, $year)->asArray()->all()
        ]);
        $gridColumns = [
            [
                'attribute' => 'fname',
                'label' => 'Full Name',
                'value' => function($model) {
                    return $model['fname'] . ' ' . $model['lname'];
                }
            ],
            [
                'attribute' => 'email',
                'label' => 'Email',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model['email'], 'mailto:' . $model['email']);
                }
            ],
            [
                'attribute' => 'tech_time',
                'label' => 'Tech Time'
            ],
            [
                'attribute' => 'overtime',
                'label' => 'Overtime'
            ],
            [
                'attribute' => 'travel_time',
                'label' => 'Travel Time'
            ],
            [
                'attribute' => 'itinerate_time',
                'label' => 'Itinerate Time'
            ]
        ];

        $this->layout = 'blank';
        return $this->render('technician-monthly-report',[
            'month' => $month,
            'year' => $year,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns
        ]);
    }

    public function actionPartBillingSummary() {
        $month = Yii::$app->getRequest()->getQueryParam('month', date('n'));
        $year = Yii::$app->getRequest()->getQueryParam('year', date('Y'));
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getPartBillingSummary($month, $year)->asArray()->all()
        ]);
        $gridColumns = [
            [
                'attribute' => 'code'
            ],
            [
                'label' => 'Division > Department / District > Building',
                'format' => 'raw',
                'value' => function($model) {
                    if ($model['code'] == 'BOCES') {
                        return Html::decode($model['division_name'].'&nbsp;>&nbsp;'.$model['department_name']);
                    } else if ($model['code'] == 'DISTRICT') {
                        return Html::decode($model['district_name'].'&nbsp;>&nbsp;'.$model['building_name']);
                    } else if ($model['code'] == 'EXTERNAL') {
                        return Html::decode($model['district_name'].'&nbsp;>&nbsp;'.$model['building_name']);
                    }
                }
            ],
            [
                'attribute' => 'parts_totals',
                'label' => 'Parts Totals'
            ],
        ];

        $this->layout = 'blank';
        return $this->render('part-billing-summary',[
            'month' => $month,
            'year' => $year,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns
        ]);
    }

    public function actionWnyricIpadRepairLaborReport() {
        $month = Yii::$app->getRequest()->getQueryParam('month', date('n'));
        $year = Yii::$app->getRequest()->getQueryParam('year', date('Y'));
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getWnyricIpadRepairLaborReport($month, $year)->asArray()->all()
        ]);
        $gridColumns = [
            [
                'label' => 'Ticket ID'
            ],
            [
                'label' => 'District'
            ],
            [
                'label' => 'Job Description'
            ],
            [
                'label' => 'RIC Queue Ticket'
            ],
            [
                'label' => 'Tech Time'
            ],
            [
                'label' => 'Labor Cost'
            ],
            [
                'label' => 'Who'
            ]
        ];

        $this->layout = 'blank';
        return $this->render('wnyric-ipad-repair-labor-report',[
            'month' => $month,
            'year' => $year,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns
        ]);
    }

    public function actionWnyricIpadPartsSummary() {
        $month = Yii::$app->getRequest()->getQueryParam('month', date('n'));
        $year = Yii::$app->getRequest()->getQueryParam('year', date('Y'));
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getWnyricIpadPartsSummary($month, $year)->asArray()->all()
        ]);
        $gridColumns = [
            [
                'label' => 'Ticket ID'
            ],
            [
                'label' => 'District'
            ],
            [
                'label' => 'Job Description'
            ],
            [
                'label' => 'RIC Queue Ticket'
            ],
            [
                'label' => 'Parts Cost'
            ]
        ];

        $this->layout = 'blank';
        return $this->render('wnyric-ipad-parts-summary',[
            'month' => $month,
            'year' => $year,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns
        ]);
    }
    

    public function actionWnyricIpadRepairBillingReport() {
        $month = Yii::$app->getRequest()->getQueryParam('month', date('n'));
        $year = Yii::$app->getRequest()->getQueryParam('year', date('Y'));
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getWnyricIpadRepairBillingReport($month, $year)->asArray()->all()
        ]);
        $gridColumns = [
            [
                'label' => 'Ticket ID'
            ],
            [
                'label' => 'District'
            ],
            [
                'label' => 'Job Description'
            ],
            [
                'label' => 'RIC Queue Ticket'
            ],
            [
                'label' => 'Tech Time'
            ],
            [
                'label' => 'Labor Cost'
            ],
            [
                'label' => 'Who'
            ]
        ];

        $this->layout = 'blank';
        return $this->render('wnyric-ipad-repair-billing-report',[
            'month' => $month,
            'year' => $year,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns
        ]);
    }

    public function actionNonWnyricIpadRepairLaborReport() {
        $month = Yii::$app->getRequest()->getQueryParam('month', date('n'));
        $year = Yii::$app->getRequest()->getQueryParam('year', date('Y'));
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getNonWnyricIpadRepairLaborReport($month, $year)->asArray()->all()
        ]);
        $gridColumns = [
            [
                'label' => 'Ticket ID'
            ],
            [
                'label' => 'District'
            ],
            [
                'label' => 'Job Description'
            ],
            [
                'label' => 'RIC Queue Ticket'
            ],
            [
                'label' => 'Tech Time'
            ],
            [
                'label' => 'Labor Cost'
            ],
            [
                'label' => 'Who'
            ]
        ];

        $this->layout = 'blank';
        return $this->render('non-wnyric-ipad-repair-labor-report',[
            'month' => $month,
            'year' => $year,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns
        ]);
    }
}