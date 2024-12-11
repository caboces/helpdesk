<?php

use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = 'Reports';
?>
<div class="site-reports">

    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-bar-chart-line-fill" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1z"/>
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="row row-cols-1 row-cols-lg-3 g-4">
        <div class="col">
            <div class="card h-100 bg-slate text-light">
                <div class="card-body d-flex align-items-center mx-auto">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-ticket-perforated-fill" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zm4-1v1h1v-1zm1 3v-1H4v1zm7 0v-1h-1v1zm-1-2h1v-1h-1zm-6 3H4v1h1zm7 1v-1h-1v1zm-7 1H4v1h1zm7 1v-1h-1v1zm-8 1v1h1v-1zm7 1h1v-1h-1z" />
                        </svg>
                        <h5 class="card-title | m-2">Ticket Reports</h5>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-2"><?= Html::a('Billing detail report', ['reports/billing-detail-report']) ?></li>
                    <li class="list-group-item p-2"><?= Html::a('Master ticket summary', ['reports/master-ticket-summary']) ?></li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 bg-slate text-light">
                <div class="card-body d-flex align-items-center mx-auto">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-patch-question-fill" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M5.933.87a2.89 2.89 0 0 1 4.134 0l.622.638.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01zM7.002 11a1 1 0 1 0 2 0 1 1 0 0 0-2 0m1.602-2.027c.04-.534.198-.815.846-1.26.674-.475 1.05-1.09 1.05-1.986 0-1.325-.92-2.227-2.262-2.227-1.02 0-1.792.492-2.1 1.29A1.7 1.7 0 0 0 6 5.48c0 .393.203.64.545.64.272 0 .455-.147.564-.51.158-.592.525-.915 1.074-.915.61 0 1.03.446 1.03 1.084 0 .563-.208.885-.822 1.325-.619.433-.926.914-.926 1.64v.111c0 .428.208.745.585.745.336 0 .504-.24.554-.627"/>
                        </svg>
                        <h5 class="card-title | m-2">Support Reports</h5>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-2"><?= Html::a('Support and repair labor billing', ['reports/support-and-repair-labor-billing']) ?></li>
                    <li class="list-group-item p-2"><?= Html::a('Support and repair telecom billing', ['reports/support-and-repair-telecom-billing'], ['class' => 'wip-link']) ?></li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 bg-slate text-light">
                <div class="card-body d-flex align-items-center mx-auto">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-tv-fill" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M2.5 13.5A.5.5 0 0 1 3 13h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5M2 2h12s2 0 2 2v6s0 2-2 2H2s-2 0-2-2V4s0-2 2-2" />
                        </svg>
                        <h5 class="card-title | m-2">Parts Reports</h5>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-2"><?= Html::a('Parts billing summary', ['reports/part-billing-summary']) ?></li>
                    <li class="list-group-item p-2"><?= Html::a('Telecom parts report', ['reports/part-telecom-report'], ['class' => 'wip-link']) ?></li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 bg-slate text-light">
                <div class="card-body d-flex align-items-center mx-auto">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                        </svg>
                        <h5 class="card-title | m-2">Technician Reports</h5>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-2"><?= Html::a('Monthly report', ['reports/technician-monthly-report']) ?></li>
                    <li class="list-group-item p-2"><?= Html::a('Detailed monthly report', ['reports/technician-detailed-monthly-report'], ['class' => 'wip-link']) ?></li>
                    <li class="list-group-item p-2"><?= Html::a('By call type summary', ['reports/technician-by-call-type-report'], ['class' => 'wip-link']) ?></li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 bg-slate text-light">
                <div class="card-body d-flex align-items-center mx-auto">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-tablet-fill" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm7 11a1 1 0 1 0-2 0 1 1 0 0 0 2 0"/>
                        </svg>
                        <h5 class="card-title | m-2">iPad / WNYRIC Reports</h5>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-2"><?= Html::a('WNYRIC iPad repair labor report', ['reports/wnyric-ipad-repair-labor-report'], ['class' => 'wip-link']) ?></li>
                    <li class="list-group-item p-2"><?= Html::a('WNYRIC iPad parts summary', ['reports/wnyric-ipad-parts-summary'], ['class' => 'wip-link']) ?></li>
                    <li class="list-group-item p-2"><?= Html::a('WNYRIC iPad repair billing report', ['reports/wnyric-ipad-repair-billing-report'], ['class' => 'wip-link']) ?></li>
                    <li class="list-group-item p-2"><?= Html::a('NON-WNYRIC iPad repair labor report', ['reports/non-wnyric-ipad-repair-labor-report'], ['class' => 'wip-link']) ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>