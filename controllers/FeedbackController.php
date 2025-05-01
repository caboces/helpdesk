<?php

namespace app\controllers;

use app\models\BlockedIpAddress;
use app\models\Feedback;
use app\models\User;
use Exception;
use Yii;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\HttpException;

/**
 * FeedbackController implements the CRUD actions for Feedback model.
 */
class FeedbackController extends Controller
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
     * Lists all Feedback models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Feedback::find(),
        ]);

        $this->layout = 'no-header';
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Feedback model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = 'no-header';
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Feedback model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Feedback();

        if ($this->request->isPost) {
            try {
                // test for honeypot
                Yii::$app->Honeypot::test($this->request, 'feedback');

                // test if the ip is blocked
                Yii::$app->requestUtils->isIPBlocked($this->request);
            } catch (HttpException $err) {
                return $err;
            }

            // test captcha
            $captchaResult = Yii::$app->CAPTCHA::verify($this->request);
            if (!$captchaResult['success']) {
                Yii::$app->session->setFlash('error', $captchaResult['message']);
            }

            // load data
            $requestMetadata = Yii::$app->requestUtils->getFlatData($this->request);
            $model->ip_address = $requestMetadata['ip_address'];
            $model->user_agent = $requestMetadata['user_agent'];
            $model->accept_language = $requestMetadata['accept_language'];

            if ($model->load($this->request->post()) && $model->validate()) {
                $model->save();
            } else {
                // form errors
                Yii::$app->session->setFlash('error', Html::errorSummary($model));
                return $this->redirect(['create', 'model' => $model]);
            }

            $this->sendFeedbackReceivedEmail($model);

            Yii::$app->session->setFlash('success', 'The feedback request was successfully submitted.');
            return $this->redirect(['create']);
        } else {
            $model->loadDefaultValues();
        }

        $this->layout = 'no-header';
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    // No reason to be able to update feedback models.

    /**
     * Deletes an existing Feedback model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Feedback model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Feedback the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Feedback::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Sends an email to the requester and the techs that feedback was sent in.
     * 
     * @param feedbackModel The feedback that was submitted
     * @return bool whether the emails were sent.
     */
    private function sendFeedbackReceivedEmail(Feedback $model) {
        $subject = 'CABOCES Help Desk: Feedback Received';
        $recipient = $model->email;
        $allEmails = [];
        $allEmails[] = Yii::$app->mailer
            ->compose(
                ['html' => 'feedbackCreated-html', 'text' => 'feedbackCreated-text'],
                ['feedback' => $model]
            )->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ': Automated Email'])
            ->setTo($recipient)
            ->setSubject($subject);
    
        // Send emails to the techs
        $allTechs = User::find()->all();
        foreach ($allTechs as &$tech) {
            $email = $tech->email;
            $allEmails[] = Yii::$app->mailer
            ->compose(
                ['html' => 'feedbackCreatedTechNotification-html', 'text' => 'feedbackCreatedTechNotification-text'],
                [
                    'feedback' => $model,
                    'tech' => $tech,
                ]
            )->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ': Automated Email'])
            ->setTo($email)
            ->setSubject($subject);
        }

        if (!empty($allEmails)) {
            // send multiple to save bandwidth
            return Yii::$app->mailer->sendMultiple($allEmails) > 0;
        } else {
            // no emails to deliver
            return false;
        }
    }
}
