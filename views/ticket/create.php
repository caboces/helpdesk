<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Ticket $model */

$this->title = 'Create Ticket';
?>
<div class="ticket-create">

    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-palette-fill" viewBox="0 0 16 16">
            <path d="M12.433 10.07C14.133 10.585 16 11.15 16 8a8 8 0 1 0-8 8c1.996 0 1.826-1.504 1.649-3.08-.124-1.101-.252-2.237.351-2.92.465-.527 1.42-.237 2.433.07M8 5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m4.5 3a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3M5 6.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m.5 6.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <p>You are currently creating a new ticket. To save your changes, click the "Save" button. Your changes will be logged in this ticket's activity, along with your tech note (if provided).</p>

    <?= $this->render(
        '_form',
        [
            'model' => $model,
            // ticket tags
            'categories' => $categories,
            'priorities' => $priorities,
            'statuses' => $statuses,
            'types' => $types,
            // customers
            'customerTypes' => $customerTypes,
            'districts' => $districts,
            'departments' => $departments,
            'divisions' => $divisions,
            'buildings' => $buildings,
            // customer buildings
            'districtBuildings' => $districtBuildings,
            'departmentBuildings' => $departmentBuildings,
            // users
            'users' => $users
        ]
    ) ?>

</div>