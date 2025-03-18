<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\AuthAssignment;

use yii\web\Controller;

use app\models\AuthItem;
use app\models\Ticket;
use app\models\UserSearch;

use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use app\models\UserActiveSearch;
use app\models\UserInactiveSearch;
use kartik\grid\ActionColumn;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        // search all users
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        //search active users
        $userActiveSearchModel = new UserActiveSearch();
        $userActiveDataProvider = $userActiveSearchModel->search($this->request->queryParams);
        // search inactive users
        $userInactiveSearchModel = new UserInactiveSearch();
        $userInactiveDataProvider = $userInactiveSearchModel->search($this->request->queryParams);

        $this->layout = 'blank-container';
        return $this->render('index', [
            // search all users
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            // search active users
            'userActiveSearchModel' => $userActiveSearchModel,
            'userActiveDataProvider' => $userActiveDataProvider,
            // search inactive users
            'userInactiveSearchModel' => $userInactiveSearchModel,
            'userInactiveDataProvider' => $userInactiveDataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $currentUserAssignmentsProvider = new ArrayDataProvider([
            'allModels' => Ticket::getCurrentTicketAssignmentsByUserId($id)->asArray()->all()
        ]);
        $pastUserAssignmentsProvider = new ArrayDataProvider([
            'allModels' => Ticket::getPastTicketAssignmentsByUserId($id)->asArray()->all()
        ]);
        $ticketColumns = [
            [
                'header' => 'Actions',
                'class' => ActionColumn::class,
                'template' => '{view}',
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute(['/ticket/view', 'id' => $model['ticket_id']]);
                },
            ],
            [
                'attribute' => 'ticket_id',
                'label' => 'Ticket ID', // 'd' wont be capitalized if only 'ticket_id' is provided to the gridColumn array
            ],
            [
                'header' => 'Division > Department<br>District > Building',
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
            'summary',
            'description',
            'requester',
            'location'
        ];
        $this->layout = 'blank-container';
        return $this->render('view', [
            'model' => $this->findModel($id),
            'currentUserAssignmentsProvider' => $currentUserAssignmentsProvider,
            'pastUserAssignmentsProvider' => $pastUserAssignmentsProvider,
            'ticketColumns' => $ticketColumns
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);

        // need to have default role for this to work... event handlers?
        $auth_assignment = AuthAssignment::findOne($user->id);

        if ($this->request->isPost && $user->load($this->request->post()) && $user->save()) {
            return $this->redirect(['view', 'id' => $user->id]);
        }

        $this->layout = 'blank-container';
        return $this->render('update', [
            'user' => $user,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // if you're NOT trying to delete admin, check if the user has permission to change user status
        if (!($this->findModel($id)->username === 'admin')) {
            if (Yii::$app->user->can('delete-user')) {
                $this->findModel($id)->delete();
            } else {
                // wrong permissions!
                throw new ForbiddenHttpException('You do not have permission to delete users.');
            }
        } else {
            // don't delete admin!
            throw new ForbiddenHttpException('Hey you!! Don\'t delete my admin account! :)');
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Toggles status
     * If toggle is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException if the user does not have permissions
     */
    public function actionToggleStatus($id)
    { 
        // if selected user is not admin, move forward
        if (!($this->findModel($id)->username === 'admin')) {
            // if current user has permissions to change user status, move forward
            if (Yii::$app->user->can('change-user-status')) {
                $model = $this->findModel($id);
                // switcheroo
                if ($model->status === 10) {
                    $model->status = 9; // if active, deactivate
                } else {
                    $model->status = 10; // if inactive, activate
                }
                // save changes and go to the user view
                if ($this->request->isPost && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    // wrong permissions!
                    throw new ForbiddenHttpException('Something went wrong. Please try again.');
                }
            } else {
                throw new ForbiddenHttpException('You do not have permission to change user status.');
            }
        } else {
            // don't delete admin!
            throw new ForbiddenHttpException('Hey you!! Don\'t deactivate my admin account! :)');
        }
        // something else went wrong idk.
        throw new ForbiddenHttpException('Something went wrong..');
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
