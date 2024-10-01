<?php

namespace app\controllers;

use yii\web\Controller;

use app\models\UserSearch;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
use app\models\UserActiveSearch;
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
     * Displays billing detail report
     *
     * @return mixed
     */
    public function actionBillingDetailReport()
    {
        $this->layout = 'blank';
        return $this->render('billing-detail-report');
    }
}