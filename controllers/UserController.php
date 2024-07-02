<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\AuthAssignment;

use yii\web\Controller;

use app\models\AuthItem;
use app\models\UserSearch;

use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use app\models\UserActiveSearch;
use app\models\UserInactiveSearch;
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
                    'class' => VerbFilter::className(),
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
        $this->layout = 'blank-container';
        return $this->render('view', [
            'model' => $this->findModel($id),
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

        /**
         * TODO: What we really need it to prevent any users with the admin role from being deleted.
         * Also, I would like to only have the delete option (in the index ActionColumn, _form, etc) for users WITHOUT any tickets. In theory
         * you will not be able to delete them anyway because of fk constraints, however I don't
         * want to give users the option to complain about
         * 
         * Ohhhh you know it would be cool if we could replace the delete button in view with 'Deactivate'
         * accordingly. later though!
         */

        if (!($this->findModel($id)->username === 'admin')) {   // don't delete admin!!!
            $this->findModel($id)->delete();
        } else {
            throw new ForbiddenHttpException('You do not have permission to delete this user.');
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

        if (!($this->findModel($id)->username === 'admin')) {   // don't delete admin!!!
            if (Yii::$app->user->can('change-user-status')) {
                $model = $this->findModel($id);
    
                if ($model->status === 10) {
                    $model->status = 9; // if active, deactivate
                } else {
                    $model->status = 10; // if inactive, activate
                }
    
                if ($this->request->isPost && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    throw new ForbiddenHttpException('You do not have permission to change user statuses.');
                }
            }
        } else {
            throw new ForbiddenHttpException('You do not have permission to deactivate this user.');
        }

        throw new ForbiddenHttpException('You do not have permission to change user statuses.');

        
        // the following code on its own is how this was originally, but it allows users to deactivate admin

        // if (Yii::$app->user->can('change-user-status')) {
        //     $model = $this->findModel($id);

        //     if ($model->status === 10) {
        //         $model->status = 9; // if active, deactivate
        //     } else {
        //         $model->status = 10; // if inactive, activate
        //     }

        //     if ($this->request->isPost && $model->save()) {
        //         return $this->redirect(['view', 'id' => $model->id]);
        //     } else {
        //         throw new ForbiddenHttpException('You do not have permission to change user activation.');
        //     }
        // }
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
