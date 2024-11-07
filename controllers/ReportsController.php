<?php

namespace app\controllers;

use app\models\Ticket;

use yii\web\Controller;

use app\models\TimeEntry;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

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
        $query = Ticket::getMasterTicketSummaryQuery();
        

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->asArray()->all()
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
                'attribute' => 'customer_type',
                'label' => 'Customer Type',
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