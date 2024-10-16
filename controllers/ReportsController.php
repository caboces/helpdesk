<?php

namespace app\controllers;

use app\models\Ticket;

use yii\db\Expression;
use yii\web\Controller;

use yii\bootstrap5\Html;
use app\models\TimeEntry;
use app\models\UserSearch;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\UserActiveSearch;
use yii\data\ActiveDataProvider;
use app\models\UserInactiveSearch;

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
        // $dataProvider = 

        $this->layout = 'blank';
        return $this->render('billing-detail-report');
    }

    /**
     * Displays the Support and Repair Labor Billing
     * This probably won't be useful, I am using it to test the exporting feature.
     *
     * @return mixed
     */
    public function actionMasterTicketSummary()
    {
        $query = (
            Ticket::find()->SELECT(['ticket.id AS ticket_id',
                'ticket.summary AS summary',
                'ticket.requester AS requester',
                'customer_type.name AS customer_type',
                'district.name AS district',
                'b1.name AS district_building',
                'division.name AS division',
                'department.name AS department',
                'b2.name AS department_building',
                // subqueries
                'tech_time' => TimeEntry::find()
                ->SELECT(['SUM(tech_time)'])
                ->WHERE('time_entry.ticket_id = ticket.id'),
                'overtime' => TimeEntry::find()
                ->SELECT(['SUM(overtime)'])
                ->WHERE('time_entry.ticket_id = ticket.id'),
                'travel_time' => TimeEntry::find()
                ->SELECT(['SUM(travel_time)'])
                ->WHERE('time_entry.ticket_id = ticket.id'),
                'itinerate_time' => TimeEntry::find()
                ->SELECT(['SUM(itinerate_time)'])
                ->WHERE('time_entry.ticket_id = ticket.id'),
                //  previously billed column should be yes/no instead of 1/0
                // 'previously_billed' => Ticket::find()
                //  ->SELECT("(CASE WHEN 'ticket.billed=1' THEN 'Yes' ELSE 'No' END)")
            ])
            ->JOIN('LEFT JOIN', 'customer_type', 'ticket.customer_type_id = customer_type.id')
            ->JOIN('LEFT JOIN', 'division', 'ticket.division_id = division.id')
            ->JOIN('LEFT JOIN', 'department', 'ticket.department_id = department.id')
            ->JOIN('LEFT JOIN', 'district', 'ticket.district_id = district.id')
            ->JOIN('LEFT JOIN', 'department_building', 'ticket.department_building_id = department_building.id')
            ->JOIN('LEFT JOIN', 'district_building', 'ticket.district_building_id = district_building.id')
            ->JOIN('LEFT JOIN', 'building b1', 'district_building.building_id = b1.id')
            ->JOIN('LEFT JOIN', 'building b2', 'department_building.building_id = b2.id;')
        );
        
            $dataProvider = new ActiveDataProvider([
                'pagination' => false,
                // 'pagination' => [
                //     'pageSize' => 10,
                // ],
                'query' => $query,
        ]);

        $gridColumns = [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'ticket_id',
                'label' => 'Ticket ID',
            ],
            'summary',
            'requester',
            [
                'attribute' => 'customer type',
                'label' => 'Customer type',
                'value' => function($model) {
                    if ($model->customer_type_id != NULL) {
                        return $model->customer_type_id;
                    } else {
                        return NULL;
                    }
                }
            ],
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