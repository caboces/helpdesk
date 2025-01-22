<?php

namespace app\controllers;

use app\models\Asset;
use app\models\District;
use app\models\HourlyRate;
use app\models\Inventory;
use app\models\JobType;
use app\models\Part;
use app\models\Ticket;
use app\models\TimeEntry;
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

    /**
     * Displays the Support and Repair Telecom Billing
     *
     * @return mixed
     */
    public function actionSupportAndRepairTelecomBilling()
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
            'allModels' => Ticket::getSupportAndRepairTelecomBillingReport($startDate, $endDate)
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
            'allModels' => Ticket::getSupportAndRepairTelecomBillingReportTotals($startDate, $endDate),
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
        return $this->render('support-and-repair-telecom-billing', [
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

    /**
     * Detailed technician monthly report
     */
    public function actionTechnicianDetailedMonthlyReport() {
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));

        $model = [];
        $model['users'] = User::find()
            ->innerJoin('tech_ticket_assignment', 'user.id = tech_ticket_assignment.user_id')
            ->innerJoin('ticket', 'ticket.id = tech_ticket_assignment.ticket_id')
            ->innerJoin('time_entry', 'time_entry.ticket_id = ticket.id AND time_entry.entry_date BETWEEN :startDate AND :endDate')
            ->params([':startDate' => $startDate, ':endDate' => $endDate])
            ->asArray()
            ->all();
        foreach ($model['users'] as &$user) {
            $user['tickets'] = Ticket::find()
                ->innerJoin('time_entry', 'time_entry.ticket_id = ticket.id AND time_entry.entry_date BETWEEN :startDate AND :endDate')
                ->params([':startDate' => $startDate, ':endDate' => $endDate])
                ->asArray()
                ->all();
            $user['totalTechTime'] = 0;
            $user['totalOvertime'] = 0;
            $user['totalTravelTime'] = 0;
            $user['totalItinerateTime'] = 0;
            foreach ($user['tickets'] as &$ticket) {
                $customerName = Ticket::find()
                    ->select('division.name AS divname, department.name AS deptname, district.name AS distname, building.name AS bldgname')
                    ->leftJoin('division', 'ticket.division_id = division.id')
                    ->leftJoin('department', 'ticket.department_id = department.id')
                    ->leftJoin('district', 'ticket.district_id = district.id')
                    ->leftJoin('district_building', 'ticket.district_building_id = district_building.id')
                    ->leftJoin('building', 'district_building.building_id = building.id')
                    ->where("ticket.id = {$ticket['id']}")
                    ->asArray()
                    ->one();
                if ($customerName['divname'] && $customerName['deptname']) {
                    $ticket['customer_name'] = $customerName['divname'].' > '.$customerName['deptname'];
                } else if ($customerName['distname'] && $customerName['bldgname']) {
                    $ticket['customer_name'] = $customerName['distname'].' > '.$customerName['bldgname'];
                }
                $ticket['time_entries'] = TimeEntry::find()
                    ->where("time_entry.ticket_id = {$ticket['id']} AND time_entry.entry_date BETWEEN :startDate AND :endDate")
                    ->params([':startDate' => $startDate, ':endDate' => $endDate])
                    ->asArray()
                    ->all();
                $ticket['totalTechTime'] = 0;
                $ticket['totalOvertime'] = 0;
                $ticket['totalTravelTime'] = 0;
                $ticket['totalItinerateTime'] = 0;
                foreach ($ticket['time_entries'] as &$time_entry) {
                    $ticket['totalTechTime'] += $time_entry['tech_time'];
                    $ticket['totalOvertime'] += $time_entry['overtime'];
                    $ticket['totalTravelTime'] += $time_entry['travel_time'];
                    $ticket['totalItinerateTime'] = $time_entry['itinerate_time'];
                }
                $user['totalTechTime'] += $ticket['totalTechTime'];
                $user['totalOvertime'] += $ticket['totalOvertime'];
                $user['totalTravelTime'] += $ticket['totalTravelTime'];
                $user['totalItinerateTime'] += $ticket['totalItinerateTime'];
            }
        }

        $this->layout = 'blank';
        return $this->render('technician-detailed-monthly-report',[
            'startDate' => $startDate,
            'endDate' => $endDate,
            'model' => $model, 
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

    /**
     * Gets the WNYRIC iPad Repair Labor Report.
     * Shows for each district the tickets and the time and cost of labor spent fixing ipads..
     */
    public function actionWnyricIpadRepairLaborReport() {
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));
        
        // declare the model
        $model = [];
        // store the districts, only grab districts WITH tickets WITH time entries in the specified date range
        $model['districts'] = District::find()
            ->innerJoin('ticket', 'ticket.district_id = district.id AND ticket.job_category_id = 25')
            ->innerJoin('time_entry', 'time_entry.ticket_id = ticket.id AND time_entry.entry_date BETWEEN :startDate AND :endDate')
            ->params([':startDate' => $startDate, ':endDate' => $endDate])
            ->orderBy('district.name')
            ->asArray()
            ->all();
        // set the grand totals to zero
        $model['totalTechTime'] = 0;
        $model['totalLaborCost'] = 0;
        // loop through each district and set the tickets + other fields
        foreach ($model['districts'] as &$district) {
            // get the tickets, only grabbing ones with districts and time entries in the date range
            $district['tickets'] = Ticket::find()
                ->innerJoin('time_entry', 'ticket.id = time_entry.ticket_id')
                ->innerJoin('district', "ticket.district_id = {$district['id']}")
                ->where('time_entry.entry_date BETWEEN :startDate AND :endDate AND ticket.job_category_id = 25')
                ->params([':startDate' => $startDate, ':endDate' => $endDate])
                ->orderBy('district.name')
                ->asArray()
                ->all();
            $district['totalTechTime'] = 0;
            $district['totalLaborCost'] = 0;
            // loop through each ticket and set the component fields like time_entry
            foreach($district['tickets'] as &$ticket) {
                // get the list of time_entries
                $ticket['time_entry'] = TimeEntry::find()
                    ->where("ticket_id = {$ticket['id']} AND time_entry.entry_date BETWEEN :startDate AND :endDate")
                    ->params([':startDate' => $startDate, ':endDate' => $endDate])
                    ->asArray()
                    ->all();
                $ticket['totalTechTime'] = 0;
                $ticket['totalLaborCost'] = 0;
                // get the totals from the time entries
                foreach ($ticket['time_entry'] as $time_entry) {
                    $ticket['totalTechTime'] += $time_entry['tech_time'];
                    // get hourly rate for ipad repairs, depending on when this time entry was added.
                    $rate = HourlyRate::find()
                        ->innerJoin('job_type', 'hourly_rate.job_type_id = job_type.id')
                        ->innerJoin('job_type_category', 'job_type.id = job_type_category.job_type_id')
                        ->innerJoin('job_category', 'job_type_category.job_category_id = job_category.id AND job_category.id = 25') // 25 is ipad
                        ->where(':now BETWEEN first_day_effective AND last_day_effective',
                        [':now' => $time_entry['entry_date']])->one()->rate;
                    $ticket['totalLaborCost'] += $time_entry['tech_time'] * $rate;
                }
                $district['totalTechTime'] += $ticket['totalTechTime'];
                $district['totalLaborCost'] += $ticket['totalLaborCost'];
            }
            $model['totalTechTime'] += $district['totalTechTime'];
            $model['totalLaborCost'] += $district['totalLaborCost'];
        }

        $this->layout = 'blank';
        return $this->render('wnyric-ipad-repair-labor-report',[
            'startDate' => $startDate,
            'endDate' => $endDate,
            'model' => $model,
        ]);
    }

    /**
     * Gets the WNYRIC iPad Parts Summary.
     * Shows for each district the tickets their cost of parts repairing ipads.
     */
    public function actionWnyricIpadPartsSummary() {
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));

        $model = [];
        // grab districts WITH tickets with iPad job category WITH parts 
        $model['districts'] = District::find()
            ->innerJoin('ticket', 'ticket.district_id = district.id AND ticket.job_category_id = 25')
            ->innerJoin('part', 'part.ticket_id = ticket.id AND part.created BETWEEN :startDate AND :endDate')
            ->params([':startDate' => $startDate, ':endDate' => $endDate])
            ->asArray()
            ->all();
        $model['totalPartsCost'] = 0;
        foreach ($model['districts'] as &$district) {
            $district['tickets'] = 
            $district['totalPartsCost'] = 0;

            // only grab tickets with existing parts
            $district['tickets'] = Ticket::find()
                ->innerJoin('part', 'ticket.id = part.ticket_id')
                ->where('part.created BETWEEN :startDate AND :endDate AND ticket.job_category_id = 25')
                ->params([':startDate' => $startDate, ':endDate' => $endDate])
                ->asArray()
                ->all();
        
            // add ticket components
            foreach ($district['tickets'] as &$ticket) {
                // only grab parts that were created in the date range
                $ticket['parts'] = Part::find()
                    ->where("ticket_id = {$ticket['id']} AND part.created BETWEEN :startDate AND :endDate")
                    ->params([':startDate' => $startDate, ':endDate' => $endDate])
                    ->asArray()
                    ->all();
                $ticket['totalPartsCost'] = 0;
                foreach ($ticket['parts'] as $part) {
                    $ticket['totalPartsCost'] += ($part['unit_price'] * $part['quantity']);
                }
                $district['totalPartsCost'] += $ticket['totalPartsCost'];
            }
            $model['totalPartsCost'] += $district['totalPartsCost'];
        }

        $this->layout = 'blank';
        return $this->render('wnyric-ipad-parts-summary',[
            'startDate' => $startDate,
            'endDate' => $endDate,
            'model' => $model,
        ]);
    }
    
    /**
     * Gets the WNYRIC iPad Repair Billing Report.
     * Shows for each district the tickets and cost of parts and labor for repairing ipads.
     */
    public function actionWnyricIpadRepairBillingReport() {
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));

        // only choose districts that have had tickets and time_entires applied to them. as well as job_category_id 25 (wnyric ipad).
        // additionally, these districts must have tickets that have either parts OR assets applied to them.
        $model = [];
        $model['districts'] = District::find()
            ->innerJoin('ticket', 'ticket.district_id = district.id AND ticket.job_category_id = 25')
            ->leftJoin('part', 'part.ticket_id = ticket.id AND part.created BETWEEN :startDate AND :endDate')
            ->leftJoin('asset', 'asset.ticket_id = ticket.id AND asset.created BETWEEN :startDate AND :endDate')
            ->innerJoin('time_entry', 'time_entry.ticket_id = ticket.id AND time_entry.created BETWEEN :startDate AND :endDate')
            ->groupBy('district.id')
            ->having('(COUNT(asset.new_prop_tag) > 0 OR COUNT(part.id) > 0) AND COUNT(time_entry.id) > 0')
            ->params([':startDate' => $startDate . ' 00:00:00', ':endDate' => $endDate . ' 23:59:59'])
            ->asArray()
            ->all();
        // populate the components of each district
        foreach ($model['districts'] as &$district) {
            $district['totalLaborHours'] = 0;
            $district['totalLaborCost'] = 0;
            $district['totalPartsCost'] = 0;
            $district['totalCost'] = 0;
            // get the tickets
            $district['tickets'] = Ticket::find()
                ->innerJoin('district', "ticket.district_id = district.id AND district.id = {$district['id']}")
                ->leftJoin('part', 'part.ticket_id = ticket.id AND part.created BETWEEN :startDate AND :endDate')
                ->leftJoin('asset', 'asset.ticket_id = ticket.id AND asset.created BETWEEN :startDate AND :endDate')
                ->innerJoin('time_entry', 'time_entry.ticket_id = ticket.id AND time_entry.created BETWEEN :startDate AND :endDate')
                ->where('ticket.job_category_id = 25')
                ->groupBy('ticket.id')
                ->having('(COUNT(asset.new_prop_tag) > 0 OR COUNT(part.id) > 0) AND COUNT(time_entry.id) > 0')
                ->params([':startDate' => $startDate . ' 00:00:00', ':endDate' => $endDate . ' 23:59:59'])
                ->asArray()
                ->all();
            // populate each ticket
            foreach ($district['tickets'] as &$ticket) {
                $ticket['parts'] = Part::find()
                    ->where("part.ticket_id = {$ticket['id']} AND part.created BETWEEN :startDate AND :endDate")
                    ->params([':startDate' => $startDate, ':endDate' => $endDate])
                    ->asArray()
                    ->all();
                $ticket['assets'] = Asset::find()
                    ->where("asset.ticket_id = {$ticket['id']} AND asset.created BETWEEN :startDate AND :endDate")
                    ->params([':startDate' => $startDate, ':endDate' => $endDate])
                    ->asArray()
                    ->all(); // not sure if the created between condition is necessary since assets dont add to a billing
                // unfortunately we need to populate the asset with some stuff from the inventory database
                foreach ($ticket['assets'] as &$asset) {
                    $asset['inventory'] = Inventory::find()->where("inventory.new_prop_tag = {$asset['new_prop_tag']}")->asArray()->one();
                }
                $ticket['time_entries'] = TimeEntry::find()
                    ->where("time_entry.ticket_id = {$ticket['id']} AND time_entry.created BETWEEN :startDate AND :endDate")
                    ->params([':startDate' => $startDate, ':endDate' => $endDate])
                    ->asArray()
                    ->all();
                $ticket['totalLaborHours'] = 0;
                $ticket['totalLaborCost'] = 0;
                foreach ($ticket['time_entries'] as &$time_entry) {
                    $ticket['totalLaborHours'] += $time_entry['tech_time'];
                    // get hourly rate for ipad repairs, grabbing the rate from the time entry's entry date.
                    $rate = HourlyRate::find()
                        ->innerJoin('job_type', 'hourly_rate.job_type_id = job_type.id')
                        ->innerJoin('job_type_category', 'job_type.id = job_type_category.job_type_id')
                        ->innerJoin('job_category', 'job_type_category.job_category_id = job_category.id AND job_category.id = 25') // 25 is ipad
                        ->where(':now BETWEEN first_day_effective AND last_day_effective',
                        [':now' => $time_entry['entry_date']])->one()->rate;
                    $ticket['totalLaborCost'] += $rate * $time_entry['tech_time'];
                }
                $ticket['totalPartsCost'] = 0;
                foreach ($ticket['parts'] as &$part) {
                    $ticket['totalPartsCost'] += ($part['quantity'] * $part['unit_price']);
                }
                $ticket['totalCost'] = $ticket['totalLaborCost'] + $ticket['totalPartsCost'];
                $district['totalLaborHours'] += $ticket['totalLaborHours'];
                $district['totalLaborCost'] += $ticket['totalLaborCost'];
                $district['totalPartsCost'] += $ticket['totalPartsCost'];
                $district['totalCost'] += $ticket['totalCost'];
            }
        }

        $this->layout = 'blank';
        return $this->render('wnyric-ipad-repair-billing-report',[
            'startDate' => $startDate,
            'endDate' => $endDate,
            'model' => $model,
        ]);
    }

    /**
     * Non WNYRIC iPad repair labor report.
     * Gets the labor time and cost of repairing ipads in non wnyric regions.
     */
    public function actionNonWnyricIpadRepairLaborReport() {
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));

        $model = [];
        // Get all districts that have tickets with time entires in non wnyric districts.
        // but, they must have assets with "ipad" in their name.
        $model['districts'] = District::find()
            ->innerJoin('ticket', 'ticket.district_id = district.id AND ticket.job_category_id <> 25')
            ->innerJoin('time_entry', 'time_entry.ticket_id = ticket.id AND time_entry.created BETWEEN :startDate AND :endDate')
            ->innerJoin('asset', 'asset.ticket_id = ticket.id')
            ->innerJoin('federated_inventory', 'federated_inventory.new_prop_tag = asset.new_prop_tag') // should be insanely slow 
            ->where('LOWER(federated_inventory.item_description) LIKE \'%ipad%\'')                      // even more slow using like and wildcards
            ->params([':startDate' => $startDate . ' 00:00:00', ':endDate' => $endDate . ' 23:59:59'])
            ->asArray()
            ->all();
        // set the grand totals to zero
        $model['totalTechTime'] = 0;
        $model['totalLaborCost'] = 0;
        // loop through each district and set the tickets + other fields
        foreach ($model['districts'] as &$district) {
            // get the tickets, only grabbing ones with districts and time entries in the date range
            $district['tickets'] = Ticket::find()
                ->innerJoin('time_entry', 'ticket.id = time_entry.ticket_id')
                ->where('time_entry.entry_date BETWEEN :startDate AND :endDate AND ticket.job_category_id <> 25')
                ->params([':startDate' => $startDate, ':endDate' => $endDate])
                ->asArray()
                ->all();
            $district['totalTechTime'] = 0;
            $district['totalLaborCost'] = 0;
            // loop through each ticket and set the component fields like time_entry
            foreach($district['tickets'] as &$ticket) {
                // get the list of time_entries
                $ticket['time_entry'] = TimeEntry::find()
                    ->where("ticket_id = {$ticket['id']} AND time_entry.entry_date BETWEEN :startDate AND :endDate")
                    ->params([':startDate' => $startDate, ':endDate' => $endDate])
                    ->asArray()
                    ->all();
                $ticket['totalTechTime'] = 0;
                $ticket['totalLaborCost'] = 0;
                // get the totals from the time entries
                foreach ($ticket['time_entry'] as $time_entry) {
                    $ticket['totalTechTime'] += $time_entry['tech_time'];
                    // get hourly rate for ipad repairs, depending on when this time entry was added.
                    $rate = HourlyRate::find()
                        ->innerJoin('job_type', 'hourly_rate.job_type_id = job_type.id')
                        ->innerJoin('job_type_category', 'job_type.id = job_type_category.job_type_id')
                        ->innerJoin('job_category', 'job_type_category.job_category_id = job_category.id AND job_category.id = 25') // 25 is ipad
                        ->where(':now BETWEEN first_day_effective AND last_day_effective',
                        [':now' => $time_entry['entry_date']])->one()->rate;
                    $ticket['totalLaborCost'] += $time_entry['tech_time'] * $rate;
                }
                $district['totalTechTime'] += $ticket['totalTechTime'];
                $district['totalLaborCost'] += $ticket['totalTechTime'];
            }
            $model['totalTechTime'] += $district['totalTechTime'];
            $model['totalLaborCost'] += $district['totalLaborCost'];
        }

        $this->layout = 'blank';
        return $this->render('non-wnyric-ipad-repair-labor-report',[
            'startDate' => $startDate,
            'endDate' => $endDate,
            'model' => $model,
        ]);
    }

    public function actionTelecomPartsReport() {
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));
        $dataProvider = new ArrayDataProvider([
            'allModels' => Ticket::getTelecomPartsReport($startDate, $endDate)->asArray()->all()
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
        return $this->render('telecom-parts-report',[
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns
        ]);
    }

    public function actionTechnicianByCallTypeReport() {
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate', date('Y-m-01'));
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate', date('Y-m-t', strtotime(date('Y-m-d'))));

        $model = [];
        // get all users that have time entries in the date range
        $model = Ticket::find()
            ->select(['user.fname as first_name', 
                'user.lname as last_name',
                'customer_type.code as code',
                'division.name as division_name',
                'department.name as department_name',
                'district.name as district_name',
                'building.name as building_name',
                'sum(time_entry.tech_time) as total_tech_time',
                'sum(time_entry.overtime) as total_overtime',
                'sum(time_entry.itinerate_time) as total_itinerate_time',
                'sum(time_entry.travel_time) as total_travel_time'
            ])->innerJoin('customer_type', 'customer_type.id = ticket.customer_type_id')
            ->innerJoin('tech_ticket_assignment', 'ticket.id = tech_ticket_assignment.ticket_id')
            ->innerJoin('user', 'user.id = tech_ticket_assignment.user_id')
            ->innerJoin('time_entry', 'time_entry.user_id = user.id AND time_entry.ticket_id = ticket.id AND time_entry.entry_date BETWEEN :startDate AND :endDate')
            ->leftJoin('division', 'ticket.division_id = division.id')
            ->leftJoin('department', 'ticket.department_id = department.id')
            ->leftJoin('district', 'ticket.district_id = district.id')
            ->leftJoin('district_building', 'ticket.district_building_id = district_building.id')
            ->leftJoin('building', 'district_building.building_id = building.id')
            ->groupBy(['user.id', 'customer_type.id', 'district.id', 'building.id', 'division.id', 'department.id'])
            ->orderBy('last_name, first_name, code, division_name, department_name, district_name, building_name')
            ->params([':startDate' => $startDate, ':endDate' => $endDate])
            ->asArray()
            ->all();

        $this->layout = 'blank';
        return $this->render('technicians-by-call-type-report',[
            'startDate' => $startDate,
            'endDate' => $endDate,
            'model' => $model,
        ]);
    }
}