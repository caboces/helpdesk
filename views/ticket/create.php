<?php

use app\models\Part;
use yii\helpers\Html;
use yii\helpers\Inflector;

/** @var yii\web\View $this */
/** @var app\models\Ticket $model */

$this->title = 'Create Ticket';
?>
<div class="ticket-create">

    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-palette-fill" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M12.433 10.07C14.133 10.585 16 11.15 16 8a8 8 0 1 0-8 8c1.996 0 1.826-1.504 1.649-3.08-.124-1.101-.252-2.237.351-2.92.465-.527 1.42-.237 2.433.07M8 5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m4.5 3a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3M5 6.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m.5 6.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <p>You are currently creating a new ticket. To save your changes, click the "Save" button. Your changes will be logged in this ticket's activity, along with your tech note (if provided).</p>

    <!-- Time Entry Errors -->
    <?php if (Yii::$app->session->hasFlash('timeEntryErrors')): ?>
        <div class="alert alert-danger">
            <h2>Time Entry Errors</h2>
            <hr>
            <?php foreach (Yii::$app->session->getFlash('timeEntryErrors') as $username => $timeEntry): ?>
                <h4>Time Entry for user "<?= Html::encode($username) ?>":</h4>
                <ul>
                    <?php foreach($timeEntry as $timeEntryField => $fieldErrors): ?>
                    <strong><?= Html::encode(Inflector::camel2words($timeEntryField)) ?></strong>
                    <ul>
                        <?php foreach($fieldErrors as $error): ?>
                            <li><?= Html::encode($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Asset Errors-->
    <?php if (Yii::$app->session->hasFlash('assetErrors')): ?>
        <div class="alert alert-danger">
            <h2>Asset Errors</h2>
            <hr>
            <?php foreach (Yii::$app->session->getFlash('assetErrors') as $new_prop_tag => $asset): ?>
                <h4>Asset Error for Asset ID "<?= Html::encode($new_prop_tag) ?>":</h4>
                <ul>
                    <?php foreach($asset as $assetField => $fieldErrors): ?>
                    <strong><?= Html::encode(Inflector::camel2words($assetField)) ?></strong>
                    <ul>
                        <?php foreach($fieldErrors as $error): ?>
                            <li><?= Html::encode($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Parts Errors-->
    <?php if (Yii::$app->session->hasFlash('partErrors')): ?>
        <div class="alert alert-danger">
            <h2>Part Errors</h2>
            <hr>
            <?php foreach (Yii::$app->session->getFlash('partErrors') as $part_name => $part): ?>
                <h4>Part Error for Part Name "<?= Html::encode($part_name) ?>":</h4>
                <ul>
                    <?php foreach($part as $partField => $fieldErrors): ?>
                    <strong><?= Html::encode(Inflector::camel2words($partField)) ?></strong>
                    <ul>
                        <?php foreach($fieldErrors as $error): ?>
                            <li><?= Html::encode($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?= $this->render(
        '_form',
        [
            'model' => $model,
            'techTimeEntryDataProvider' => $techTimeEntryDataProvider,
            // search time entries
            'techTimeEntrySearch' => $techTimeEntrySearch,
            'techTimeEntryDataProvider' => $techTimeEntryDataProvider,
            // ticket tags
            'categories' => $categories,
            'priorities' => $priorities,
            'statuses' => $statuses,
            'types' => $types,
            'jobTypeCategoryData' => $jobTypeCategoryData,
            // customers
            'customerTypes' => $customerTypes,
            'districts' => $districts,
            'departments' => $departments,
            'divisions' => $divisions,
            'buildings' => $buildings,
            // customer buildings
            'departmentBuildingData' => $departmentBuildingData,
            'districtBuildingData' => $districtBuildingData,
            // users
            'users' => $users,
            'assignedTechData' => $assignedTechData,
            'assetProvider' => $assetProvider,
            'assetColumns' => $assetColumns,
            'partsProvider' => $partsProvider,
            'partsColumns' => $partsColumns,
            'ticketNotesProvider' => $ticketNotesProvider,
            'ticketNotesColumns' => $ticketNotesColumns,
            'ticketDraft' => $ticketDraft,
        ]
    ) ?>

</div>