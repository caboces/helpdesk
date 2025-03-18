<?php

use app\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
AppAsset::register($this);

$this->title = 'Frequently Asked Questions';

?>
<div class="site-help">
    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-patch-question-fill" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M5.933.87a2.89 2.89 0 0 1 4.134 0l.622.638.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01zM7.002 11a1 1 0 1 0 2 0 1 1 0 0 0-2 0m1.602-2.027c.04-.534.198-.815.846-1.26.674-.475 1.05-1.09 1.05-1.986 0-1.325-.92-2.227-2.262-2.227-1.02 0-1.792.492-2.1 1.29A1.7 1.7 0 0 0 6 5.48c0 .393.203.64.545.64.272 0 .455-.147.564-.51.158-.592.525-.915 1.074-.915.61 0 1.03.446 1.03 1.084 0 .563-.208.885-.822 1.325-.619.433-.926.914-.926 1.64v.111c0 .428.208.745.585.745.336 0 .504-.24.554-.627" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="container-lg">
        <h2>Managing Tickets</h2>
        <div class="accordion accordion-flush" id="accordionFlushExample1">
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        How do I edit an existing Ticket?
                    </button>
                </h3>
                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample1">
                    <div class="accordion-body">
                        <p>To update an existing <i class="fa-solid fa-ticket"></i> Ticket, go to the "<?= Html::a('Tickets', Url::toroute('/ticket/index'), ['target' => '_blank']) ?>" tab to view the "Ticket Management" page. <i class="fa-solid fa-ticket"></i> Tickets are ordered in tables and grids and organized by "Assignments", "Open", "Resolved / Closed", or "Marked for Deletion" tab panes. In each table, select the pencil icon on the left under the "Actions" column to update the ticket.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                        How do I create a Ticket?
                    </button>
                </h3>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample1">
                    <div class="accordion-body">
                        <p>To create a <i class="fa-solid fa-ticket"></i> Ticket, go to the <?= Html::a('Create Ticket', Url::toRoute(['/ticket/create']), ['target' => '_blank']) ?> page.</p>
                        <p>When creating a Ticket, you cannot add <i class="fa fa-gear"></i> Parts, <i class="fa fa-barcode"></i> Assets, or <i class="fa-solid fa-clock"></i> Time Entries.</p>
                        <p>Please be sure the information you enter is as accurate as possible as the billing system is tightly integrated with your inputted information.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                        How do I assign multiple Technicians to a Ticket?
                    </button>
                </h3>
                <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample1">
                    <div class="accordion-body">
                        <p>To assign multiple <i class="fa-solid fa-users"></i> Technicians to a <i class="fa-solid fa-ticket"></i> Ticket, you must go to the "Update Ticket" page for the specific ticket and click on the "Technicians" tab. Under the "Assigned Technicians" input in the form, add or remove specific Technicians referenced by their username.</p>
                        <p>You can also assign the Primary Technician on this page, but a certain Technician cannot be removed or added to the Primary Technician position if they are not assigned to the ticket. The Primary Technician field automatically populates depending on what is selected in the "Assigned Technicians" field.</p>
                    </div>
                </div>
            </div>
        </div>
        <h2>Managing Technicians</h2>
        <div class="accordion accordion-flush" id="accordionFlushExample2">
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                        How do I add a new tech account?
                    </button>
                </h3>
                <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample2">
                    <div class="accordion-body">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur aut ad dolores impedit delectus nihil dolore non sunt quisquam nobis.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                        How do I delete an old Technician account?
                    </button>
                </h3>
                <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample2">
                    <div class="accordion-body">
                        <p>You must have elevated permissions to delete Technicians.</p>
                        <p>A Technician may have many <i class="fa-solid fa-clock"></i> Time Entries, <i class="fa-solid fa-ticket"></i> Ticket assignments, or other Helpdesk related entities. For this reason, Technicians are rarely deleted from the system as it would harm the integrity and consistency of old data, such as billing and reporting.</p>
                        <p>However, you can disable certain Technician accounts using the "Toggle Status" switch on a specific account on the <?= Html::a('User Management', Url::toRoute(['/user/index']), ['target' => '_blank']) ?> page.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                        How do I change my password?
                    </button>
                </h3>
                <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample2">
                    <div class="accordion-body">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Adipisci architecto provident nobis expedita beatae explicabo ullam error veniam, doloribus vitae? Doloremque, quos nesciunt fuga vel temporibus dolor beatae nostrum? Debitis voluptas modi quia. Quisquam ipsam odit, similique voluptates accusantium explicabo corrupti itaque. Repellat, iste deserunt. Quisquam repellendus deserunt velit id!</div>
                </div>
            </div>
        </div>
    </div>
</div>