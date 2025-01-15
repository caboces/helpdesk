<?php

namespace app\controllers;

use app\models\District;
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
     * Lists all reports.
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

    // ======================================================================================================
    // TODO: Would be nice to abstract out the "report" at some point so we dont have so much duplicate code.
    // You can try creating a "report.php" file and then basically copy the format each of the reports have,
    // but you'd end up with a giant if/elseif statement of query parameters directing to the correct report.
    // That is probably much worse than just writing individual action...() functions for each report.
    // ======================================================================================================

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

        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getBillingDetailReportQueryDivisionDepartment($startDate, $endDate)->asArray()->all()
        ]);
        $gridColumns = [
            [
                'attribute' => 'code',
                'label' => 'Code'
            ],
            [
                'attribute' => 'division_name',
                'label' => 'Division'
            ],
            [
                'attribute' => 'department_name',
                'label' => 'Department'
            ],
            [
                'attribute' => 'district_name',
                'label' => 'District'
            ],
            [
                'attribute' => 'building_name',
                'label' => 'Building'
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
                    return Yii::$app->formatter->asCurrency($model['parts_cost']?? 0, '$');
                },
                'label' => 'Parts Cost',
                'pageSummary' => true,
            ],
            [
                'attribute' => 'total_cost',
                'value' => function($model) {
                    return Yii::$app->formatter->asCurrency($model['total_cost']?? 0, '$');
                },
                'label' => 'Total Cost',
            ]
        ];

        $totalsDataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getBillingDetailReportQueryDivisionDepartmentTotals($startDate, $endDate)->asArray()->all(),
        ]);

        $totalsColumns = [
            [
                'attribute' => 'code',
                'label' => 'Code'
            ],
            [
                'attribute' => 'division_name',
                'label' => 'Division'
            ],
            [
                'attribute' => 'department_name',
                'label' => 'Department'
            ],
            [
                'attribute' => 'district_name',
                'label' => 'District'
            ],
            [
                'attribute' => 'building_name',
                'label' => 'Building'
            ],
            [
                'attribute' => 'total_hours',
                'label' => 'Total Hours',
            ],
            [
                'attribute' => 'parts_total',
                'label' => 'Total Parts Cost',
                'value' => function($model) {
                    return Yii::$app->formatter->asCurrency($model['parts_total']?? 0, '$');
                }
            ],
            [
                'attribute' => 'total_cost',
                'label' => 'Grand Total',
                'value' => function($model) {
                    return Yii::$app->formatter->asCurrency($model['total_cost']?? 0, '$');
                }
            ]
        ];

        $this->layout = 'blank';
        return $this->render('billing-detail-report', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns,
            'laborRatesDataProvider' => $laborRatesDataProvider,
            'laborRatesColumns' => $laborRatesColumns,
            'totalsDataProvider' => $totalsDataProvider,
            'totalsColumns' => $totalsColumns,
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
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));
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
            'startDate' => $startDate,
            'endDate' => $endDate,
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
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));
        $jobType = Yii::$app->getRequest()->getQueryParam('jobType', 5); // 'Troubleshoot' in job_type table
        $jobTypes = ArrayHelper::map(JobType::find()->where(['id' => [5, 7]])->asArray()->all(), 'id', 'name'); // 5 and 7 are 'Troubleshoot' and 'Audio Visual Production'
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getSupportAndRepairLaborBillingReport($startDate, $endDate, $jobType)
        ]);

        $gridColumns = [
            [
                'attribute' => 'code'
            ],
            [
                'attribute' => 'division_name',
                'label' => 'Division'
            ],
            [
                'attribute' => 'department_name',
                'label' => 'Department'
            ],
            [
                'attribute' => 'district_name',
                'label' => 'District'
            ],
            [
                'attribute' => 'building_name',
                'label' => 'Building'
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

        $totalsDataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getSupportAndRepairLaborBillingReportTotals($startDate, $endDate, $jobType),
        ]);

        $totalsColumns = [
            [
                'attribute' => 'code',
                'label' => 'Code'
            ],
            [
                'attribute' => 'division_name',
                'label' => 'Division'
            ],
            [
                'attribute' => 'department_name',
                'label' => 'Department'
            ],
            [
                'attribute' => 'district_name',
                'label' => 'District'
            ],
            [
                'attribute' => 'building_name',
                'label' => 'Building'
            ],
            [
                'attribute' => 'total_tech_time',
                'label' => 'Total Tech Time',
            ],
            [
                'attribute' => 'total_overtime',
                'label' => 'Total Overtime',
            ],
            [
                'attribute' => 'total_travel_time',
                'label' => 'Total Travel Time',
            ],
            [
                'attribute' => 'total_itinerate_time',
                'label' => 'Total Itinerate Time',
            ]
        ];

        $this->layout = 'blank';
        return $this->render('support-and-repair-labor-billing', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'jobType' => $jobType,
            'jobTypes' => $jobTypes,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns,
            'laborRatesDataProvider' => $laborRatesDataProvider,
            'laborRatesColumns' => $laborRatesColumns,
            'totalsDataProvider' => $totalsDataProvider,
            'totalsColumns' => $totalsColumns,
        ]);
    }

    public function actionTechnicianMonthlyReport() {
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));
        $dataProvider = new ArrayDataProvider([
            'allModels' => User::getTechnicianMonthlyReport($startDate, $endDate)->asArray()->all()
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
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns,
        ]);
    }

    public function actionPartBillingSummary() {
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getPartBillingSummary($startDate, $endDate)->asArray()->all()
        ]);
        $gridColumns = [
            [
                'attribute' => 'code'
            ],
            [
                'attribute' => 'division_name',
                'label' => 'Division'
            ],
            [
                'attribute' => 'department_name',
                'label' => 'Department'
            ],
            [
                'attribute' => 'district_name',
                'label' => 'District'
            ],
            [
                'attribute' => 'building_name',
                'label' => 'Building'
            ],
            [
                'attribute' => 'parts_totals',
                'label' => 'Parts Totals',
                'value' => function($model) {
                    return $model['parts_totals']? '$' . $model['parts_totals'] : '';
                }
            ],
        ];

        $this->layout = 'blank';
        return $this->render('part-billing-summary',[
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns
        ]);
    }

    public function actionWnyricIpadRepairLaborReport() {
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getWnyricIpadRepairLaborReport($startDate, $endDate)->asArray()->all()
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
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns
        ]);
    }

    public function actionWnyricIpadPartsSummary() {
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getWnyricIpadPartsSummary($startDate, $endDate)->asArray()->all()
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
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns
        ]);
    }
    

    public function actionWnyricIpadRepairBillingReport() {
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));

        // build the 'districts' object
        $districts = District::findBySql("
            SELECT * 
            FROM `district`
            INNER JOIN `ticket` ON `ticket`.`district_id` = `district`.`id`
            INNER JOIN `part` ON `part`.`ticket_id` = `ticket`.`id` AND `part`.`created` BETWEEN :startDate AND :endDate
            INNER JOIN `time_entry` ON `time_entry`.`ticket_id` = `ticket`.`id` AND `time_entry`.`created` BETWEEN :startDate AND :endDate
            WHERE `ticket`.`job_category
        ", [':startDate' => $startDate, ':endDate' => $endDate])->all();

        $this->layout = 'blank';
        return $this->render('wnyric-ipad-repair-billing-report',[
            'startDate' => $startDate,
            'endDate' => $endDate,
            'districts' => $districts,
        ]);
    }

    public function actionNonWnyricIpadRepairLaborReport() {
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getNonWnyricIpadRepairLaborReport($startDate, $endDate)->asArray()->all()
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
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns
        ]);
    }
}