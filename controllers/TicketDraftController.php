<?php

namespace app\controllers;

use app\models\BlockedIpAddress;
use app\models\Building;
use app\models\CustomerType;
use app\models\Department;
use app\models\DepartmentBuilding;
use app\models\District;
use app\models\DistrictBuilding;
use app\models\Division;
use app\models\JobType;
use app\models\JobTypeCategory;
use app\models\TicketDraft;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\web\ForbiddenHttpException;

/**
 * TicketDraftController implements the CRUD actions for TicketDraft model.
 */
class TicketDraftController extends Controller
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
     * Lists all TicketDraft models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TicketDraft::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TicketDraft model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TicketDraft model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        // TODO not great that this is in the source code, but yii2 has 0 support for env files without installing another package.
        // problem is, this VM has some funky crap with the internet connection, so its hard to install new dependencies. 
        // Fix it later
        $reCaptchaPrivateApiKey = '6LcEMsEqAAAAAHb_vxGCjgC8iY5Xm2IbUAGDvEpE';
        // 0 is very likely a bot, 1.0 is clean. So, treat 0.5 as the threshold to determine if they are a bot or not.
        $reCaptchaActionThreshold = 0.5;
        $model = new TicketDraft();

        $jobTypes = ArrayHelper::map(JobType::getTypes(), 'id', 'name');
        $jobTypeCategoryData = JobTypeCategory::getJobCategoryNamesFromJobTypeId($model);
        // customers
        $customerTypes = ArrayHelper::map(CustomerType::getCustomerTypes(), 'id', 'name');
        $districts = ArrayHelper::map(District::getDistricts(), 'id', 'name');
        // tack the corresponding division name onto department options
        $departments = ArrayHelper::map(Department::getSortedDepartments(), 'id', function($model) {return Division::findOne($model['division_id'])->name . ' > ' . $model['name'];});
        $divisions = ArrayHelper::map(Division::getDivisions(), 'id', 'name');
        $buildings = ArrayHelper::map(Building::getBuildings(), 'id', 'name');
        // customer buildings: this data will populate the ddl's based on dept/dist on load
        $departmentBuildingData = DepartmentBuilding::getBuildingNamesFromDepartmentId($model);
        $districtBuildingData = DistrictBuilding::getBuildingNamesFromDistrictId($model);

        // Check if this ip address is blocked!
        $isIpBlocked = BlockedIpAddress::find()->where(['ip_address' => Yii::$app->request->getUserIP()])->exists();
        if ($isIpBlocked) {
            return new ForbiddenHttpException("You are forbidden from accessing this resource.");
        }

        if ($this->request->isPost) {
            $honeypot = $this->request->post('vc090h3n');
            if ($honeypot) {
                // honeypot was detected, block this IP address
                $blockedIp = new BlockedIpAddress();
                $blockedIp->ip_address = Yii::$app->request->getUserIP();
                $blockedIp->reason = "User submitted nonempty information to honeypot field within Ticket Draft form";
                $blockedIp->save();
                return new ForbiddenHttpException("You are forbidden from accessing this resource.");
            }
            $model->ip_address = Yii::$app->request->getUserIP();
            $model->user_agent = Yii::$app->request->getUserAgent();
            // useful, if it has stuff like zn-CH, vs, en-US, we know they are probably a bot
            $model->accept_language = implode(';', Yii::$app->request->getAcceptableLanguages());

            // Send recaptcha request. If failed, then redirect to ticket draft again.
            $client = new Client();

            $captchaResponse = $client->createRequest()
                ->setMethod('POST')
                ->setUrl('https://www.google.com/recaptcha/api/siteverify')
                ->setData([
                    'secret' => $reCaptchaPrivateApiKey,
                    'response' => $this->request->post('g-recaptcha-response'),
                    'remoteip' => Yii::$app->request->getUserIP(),
                ])
                ->setFormat(Client::FORMAT_URLENCODED)
                ->send();
            // this won't work in dev because there is no internet connection.
            if ($captchaResponse->isOk) {
                // reCaptcha failed
                if (!$captchaResponse->data['success']) {
                    Yii::$app->session->setFlash('captchaError', 'There was an error verifying your reCAPTCHA.');
                    return $this->redirect(['/ticket-draft/create', 'model' => $model]);
                }
                if (!$captchaResponse->data['action'] < $reCaptchaActionThreshold) {
                    Yii::$app->session->setFlash('captchaError', 'You failed the reCAPTCHA. Try again.');
                    return $this->redirect(['/ticket-draft/create', 'model' => $model]);
                }
            } else {
                Yii::$app->session->setFlash('captchaError', 'There was an error verifying your reCAPTCHA. Google\'s API may be down or the server lost internet connection.');
                return $this->redirect(['/ticket-draft/create', 'model' => $model]);
            }
            // reCAPTCHA passed

            if ($model->load($this->request->post()) && $model->validate()) {
                // save the model
                $model->save(false);

                // send an email to the requestor and all techs
                $this->sendTicketDraftEmail($model);

                Yii::$app->session->setFlash('success', 'The ticket request was successfully submitted.');
                return $this->redirect(['/ticket-draft/create', 'model' => $model]);
            } else {
                // form errors
                $errors = [];
                if ($model->hasErrors()) {
                    $errors = $model->getErrors();
                }
                Yii::$app->session->setFlash('ticketDraftErrors', $errors);
                return $this->redirect(['/ticket-draft/create', 'model' => $model]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $this->layout = 'blank-container';
        return $this->render('create', [
            'model' => $model,
            'jobTypeCategoryData' => $jobTypeCategoryData,
            'jobTypes' => $jobTypes,
            // customers
            'customerTypes' => $customerTypes,
            'districts' => $districts,
            'departments' => $departments,
            'divisions' => $divisions,
            'buildings' => $buildings,
            // customer buildings
            'departmentBuildingData' => $departmentBuildingData,
            'districtBuildingData' => $districtBuildingData,
        ]);
    }

    /**
     * Updates an existing TicketDraft model.
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

        $this->layout = 'blank-container';
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TicketDraft model.
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
     * TODO Copied from TicketController.php
     * Populate the ticket categories DDL based on selected job type id
     * Can probably remove the job type naming and stuff, think i only need category stuff. not sure what ill do yet
     */
    public function actionJobCategoryDependentDropdownQuery() {
        $search_reference = Yii::$app->request->post('job_category_search_reference');
        $query = new Query();
        $query->select(['job_type_category.id', 'job_type_category.job_type_id', 'job_type_category.job_category_id', 'job_category.name'])
            ->from('job_type_category')
            ->innerJoin('job_category', 'job_type_category.job_category_id = job_category.id')
            ->where(['job_type_id' => $search_reference])
            ->orderBy('name ASC');
        $rows = $query->all();

        $data = [];
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $data[] = ['id' => $row['job_category_id'], 'name' => $row['name']];
            }
        } else {
            $data = '';
        }

        return $this->asJson($data);
    }

    /**
     * TODO Copied from TicketController.php
     * Dependent dropdown query for DistrictBuilding. Checks for change
     */
    public function actionDistrictBuildingDependentDropdownQuery()
    {
        $search_reference = Yii::$app->request->post('district_search_reference');
        $query = new Query;
        $query->select(['district_building.id', 'district_building.district_id', 'district_building.building_id', 'building.name'])
            ->from('district_building')
            ->innerJoin('building', 'district_building.building_id = building.id')
            ->where(['district_id' => $search_reference])
            ->orderBy('name ASC');
        $rows = $query->all();

        $data = [];
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $data[] = ['id' => $row['id'], 'name' => $row['name']];
            }
        } else {
            $data = '';
        }

        return $this->asJson($data);
    }

    /**
     * TODO Copied from TicketController.php
     * Dependent dropdown query for DepartmentBuilding. Checks for change
     */
    public function actionDepartmentBuildingDependentDropdownQuery()
    {
        $search_reference = Yii::$app->request->post('department_search_reference');
        $query = new Query;
        $query->select(['department_building.id', 'department_building.department_id', 'department_building.building_id', 'building.name', 'department.division_id'])
            ->from('department_building')
            ->innerJoin('building', 'department_building.building_id = building.id')
            ->innerJoin('department', 'department_building.department_id = department.id')
            ->where(['department_id' => $search_reference])
            ->orderBy('name ASC');
        $rows = $query->all();

        $data = [];
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $data[] = ['id' => $row['id'], 'name' => $row['name'], 'division' => $row['division_id']];
            }
        } else {
            $data = '';
        }

        return $this->asJson($data);
    }

    
    /**
     * Sends an email to the requestor and techs that a new draft was created.
     * 
     * Must separate the $app->mailer calls from requestor and the techs so the requestor isn't also thrown into
     * the bulk email.
     *
     * @param ticketModel The ticket
     * @return bool whether the emails were sent
     */
    public function sendTicketDraftEmail(TicketDraft $model) {
        // string of user email
        $recipients = $model->email;
        // create Yii email object array
        $emails = [];
        $emails[] = Yii::$app->mailer
            ->compose(
                ['html' => 'ticketDraftCreated-html', 'text' => 'ticketDraftCreated-text'],
                ['ticketDraft' => $model]
            )->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ': Automated Email'])
            ->setTo($recipients)
            ->setSubject('CABOCES Help Desk: Ticket Request Submitted');
        
        // Send emails to all techs of the new draft
        $techEmails = User::getAllEmails();
        // add to emails
        $emails[] = Yii::$app->mailer
            ->compose(
                ['html' => 'ticketDraftCreatedTechNotification-html', 'text' => 'ticketDraftCreatedTechNotification-text'],
                ['ticketDraft' => $model]
            )->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ': Automated Email'])
            ->setTo($techEmails)
            // attach files
            ->setSubject('CABOCES Help Desk: New Ticket Request');

        if (!empty($emails)) {
            // send multiple to save bandwidth
            return Yii::$app->mailer->sendMultiple($emails);
        } else {
            // no emails to deliver
            return false;
        }
    }

    /**
     * Finds the TicketDraft model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return TicketDraft the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TicketDraft::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
