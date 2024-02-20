<?php

use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = 'General Management Tools';
?>
<div class="site-manage">

    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-wrench-adjustable-circle-fill" viewBox="0 0 16 16">
            <path d="M6.705 8.139a.25.25 0 0 0-.288-.376l-1.5.5.159.474.808-.27-.595.894a.25.25 0 0 0 .287.376l.808-.27-.595.894a.25.25 0 0 0 .287.376l1.5-.5-.159-.474-.808.27.596-.894a.25.25 0 0 0-.288-.376l-.808.27z" />
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m-6.202-4.751 1.988-1.657a4.5 4.5 0 0 1 7.537-4.623L7.497 6.5l1 2.5 1.333 3.11c-.56.251-1.18.39-1.833.39a4.5 4.5 0 0 1-1.592-.29L4.747 14.2a7.03 7.03 0 0 1-2.949-2.951M12.496 8a4.5 4.5 0 0 1-1.703 3.526L9.497 8.5l2.959-1.11q.04.3.04.61" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="row row-cols-1 row-cols-lg-3 g-4">
        <div class="col">
            <div class="card h-100 bg-slate text-light">
                <div class="card-body d-flex align-items-center mx-auto">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                        </svg>
                        <h5 class="card-title | m-2">Users</h5>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-2">Manage my account</li>
                    <li class="list-group-item p-2"><?= Html::a('Manage all users', ['user/index'], ['class' => 'pacific-cyan']) ?></li>
                    <li class="list-group-item p-2"><?= Html::a('Create new user', ['site/signup'], ['class' => 'pacific-cyan']) ?></li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 bg-slate text-light">
                <div class="card-body d-flex align-items-center mx-auto">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-compass-fill" viewBox="0 0 16 16">
                            <path d="M15.5 8.516a7.5 7.5 0 1 1-9.462-7.24A1 1 0 0 1 7 0h2a1 1 0 0 1 .962 1.276 7.5 7.5 0 0 1 5.538 7.24m-3.61-3.905L6.94 7.439 4.11 12.39l4.95-2.828 2.828-4.95z" />
                        </svg>
                        <h5 class="card-title | m-2">Districts & Departments</h5>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-2"><?= Html::a('Manage departments', ['department/index'], ['class' => 'pacific-cyan']) ?></li>
                    <li class="list-group-item p-2"><?= Html::a('Manage districts', ['district/index'], ['class' => 'pacific-cyan']) ?></li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 bg-slate text-light">
                <div class="card-body d-flex align-items-center mx-auto">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-tv-fill" viewBox="0 0 16 16">
                            <path d="M2.5 13.5A.5.5 0 0 1 3 13h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5M2 2h12s2 0 2 2v6s0 2-2 2H2s-2 0-2-2V4s0-2 2-2" />
                        </svg>
                        <h5 class="card-title | m-2">Equipment</h5>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-2">Manage equipment types</li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 bg-slate text-light">
                <div class="card-body d-flex align-items-center mx-auto">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-ticket-perforated-fill" viewBox="0 0 16 16">
                            <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zm4-1v1h1v-1zm1 3v-1H4v1zm7 0v-1h-1v1zm-1-2h1v-1h-1zm-6 3H4v1h1zm7 1v-1h-1v1zm-7 1H4v1h1zm7 1v-1h-1v1zm-8 1v1h1v-1zm7 1h1v-1h-1z" />
                        </svg>
                        <h5 class="card-title | m-2">Tickets</h5>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-2"><?= Html::a('Manage ticket categories', ['job-category/index'], ['class' => 'pacific-cyan']) ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>