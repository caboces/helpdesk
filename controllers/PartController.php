<?php

namespace app\controllers;

use app\models\Part;
use app\models\PartSearch;
use app\models\PartType;
use app\models\Ticket;
use app\models\User;
use Yii;
use yii\db\ForeignKeyConstraint;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\ForbiddenHttpException;

/**
 * PartController implements the CRUD actions for Part model.
 */
class PartController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Part models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PartSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $inactivePartsSearchModel = new PartSearch();
        $inactivePartsSearchModel->pending_delivery = false;
        $inactivePartsSearchModel->search_inactive_tickets = true;
        $inactivePartsDataProvider = $inactivePartsSearchModel->search(array_merge($this->request->queryParams, ['search_inactive_tickets' => true]));
        
        $this->layout = 'blank-container';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'inactivePartsDataProvider' => $inactivePartsDataProvider,
        ]);
    }

    /**
     * Displays a single Part model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = 'blank-container';
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Part model.
     * NOTE: Should not be able to make a part without a ticket.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $models = [new Part()];
        $ticket_id = $this->request->get('ticket_id');
        $partTypes = PartType::find()->select(['id', 'name', 'description'])->orderBy('name ASC')->all();

        if ($this->request->isPost) {
            $count = count($this->request->post('Part'));
            for ($i = 0; $i < $count; $i++) {
                $models[$i] = new Part();
            }
            // validate and load
            if (Part::loadMultiple($models, $this->request->post()) && Part::validateMultiple($models)) {
                foreach ($models as $model) {
                    // do not run validation since we already did, save each one
                    $model->save(false);
                }
            } else {
                Yii::$app->session->setFlash('error', Html::errorSummary($models));
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->redirect("/ticket/update?id=$ticket_id&tabPane=pills-parts-tab");
        } else {
            foreach ($models as $model) {
                $model->loadDefaultValues();
            }
        }
        
        $this->layout = 'blank';
        return $this->renderAjax('create', [
            'models' => $models,
            'ticket_id' => $ticket_id,
            'partTypes' => $partTypes,
        ]);
    }

    /**
     * Updates an existing Part model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $this->layout = 'blank';
        return $this->renderAjax('update', [
            'model' => $model,
            'ticket_id' => $model->ticket_id,
        ]);
    }

    /**
     * Deletes an existing Part model.
     * If deletion is successful, the browser will be redirected to the 'part/update' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $part = $this->findModel($id);
        if ($part->pending_delivery) {
            throw new ForbiddenHttpException('You cannot delete parts that are pending delivery. Please mark it as arrived and then delete it if required.');
        }
        $part->delete();
        $ticketId = $part->ticket_id;

        $this->layout = 'blank-container';
        return $this->redirect(["/ticket/update?id=$ticketId&tabPane=pills-parts-tab"]);
    }

    public function actionSearch() {
        $partTypesOptions = ArrayHelper::map(
            PartType::find()
                ->select(['id', 'name'])
                ->orderBy('name ASC')
                ->all(), 'id', 'name'
        );
        $usersOptions = ArrayHelper::map(
            User::find()
                ->select(['id', 'username'])
                ->orderBy('username ASC')
                ->all(), 'id', 'username'
        );
        $ticketsOptions = ArrayHelper::map(
            Ticket::find()
                ->select(['ticket.id', 'ticket.summary'])
                ->orderBy('ticket.summary ASC')
                ->innerJoin('part', 'part.ticket_id = ticket.id')
                ->all(), 'id', 'summary'
        );

        $this->layout = 'blank-container';
        return $this->render('search', [
            'model' => new PartSearch(),
            'partTypesOptions' => $partTypesOptions,
            'usersOptions' => $usersOptions,
            'ticketsOptions' => $ticketsOptions,
        ]);
    }

    /**
     * Finds the Part model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Part the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Part::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
