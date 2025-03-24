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
                        <p>To assign multiple <i class="fa-solid fa-users"></i> Technicians to a <i class="fa-solid fa-ticket"></i> Ticket, you must go to the "Update Ticket" page for the specific <i class="fa-solid fa-ticket"></i> Ticket and click on the "Technicians" tab. Under the "Assigned Technicians" input in the form, add or remove specific <i class="fa-solid fa-users"></i> Technicians referenced by their username.</p>
                        <p>You can also assign the Primary Technician on this page, but a certain <i class="fa-solid fa-users"></i> Technician cannot be removed or added to the Primary Technician position if they are not assigned to the ticket. The Primary Technician field automatically populates depending on what is selected in the "Assigned Technicians" field.</p>
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
                    <div class="accordion-body">
                        <p>You can add a new account on the <?= Html::a('Create User', Url::toRoute(['/user/create'])) ?> page.</p>
                    </div>
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
                        <p>You must have elevated permissions to delete <i class="fa-solid fa-users"></i> Technicians.</p>
                        <p>A <i class="fa-solid fa-users"></i> Technician may have many <i class="fa-solid fa-clock"></i> Time Entries, <i class="fa-solid fa-ticket"></i> Ticket assignments, or other Helpdesk related entities. For this reason, <i class="fa-solid fa-users"></i> Technicians are rarely deleted from the system as it would harm the integrity and consistency of old data, such as billing and reporting.</p>
                        <p>However, you can disable certain <i class="fa-solid fa-users"></i> Technician accounts using the "Toggle Status" switch on a specific account on the <?= Html::a('User Management', Url::toRoute(['/user/index']), ['target' => '_blank']) ?> page.</p>
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
                    <div class="accordion-body">
                        <p>You cannot change your password as of right now. To change your password, please contact one of the Website Developers to help you.</p>
                    </div>
                </div>
            </div>
        </div>
        <h2>Managing Parts</h2>
        <div class="accordion accordion-flush" id="accordionFlushExample3">
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingSeven">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven">
                        What are Parts?
                    </button>
                </h3>
                <div id="flush-collapseSeven" class="accordion-collapse collapse" aria-labelledby="flush-headingSeven" data-bs-parent="#accordionFlushExample3">
                    <div class="accordion-body">
                        <p><i class="fa-solid fa-gear"></i> Parts are items you purchase on behalf of CABOCES to help resolve a ticket. For example, a district teacher's keyboard broke and they submitted a ticket — you buy one for them to help resolve the ticket.</p>
                        <p>In the billing and reporting system, <i class="fa-solid fa-gear"></i> Parts are billed toward districts or BOCES departments as they are purchased.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingEight">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight">
                        How do I add a Part?
                    </button>
                </h3>
                <div id="flush-collapseEight" class="accordion-collapse collapse" aria-labelledby="flush-headingEight" data-bs-parent="#accordionFlushExample3">
                    <div class="accordion-body">
                        <p>To add a <i class="fa-solid fa-gear"></i> Part, visit the specific <i class="fa-solid fa-ticket"></i> Ticket you would like to add <i class="fa-solid fa-gear"></i> Parts to.</p> 
                        <p>You will need to know the following required information: part name, part type, pending delivery status, quantity, and unit cost.</p>
                        <p>There are also some optional fields available: part note, part number, and Part Order (PO) number.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingNine">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine">
                        How do I remove a Part?
                    </button>
                </h3>
                <div id="flush-collapseNine" class="accordion-collapse collapse" aria-labelledby="flush-headingNine" data-bs-parent="#accordionFlushExample3">
                    <div class="accordion-body">
                        <p>You cannot remove <i class="fa-solid fa-gear"></i> Parts if they are in a closed ticket. To delete a <i class="fa-solid fa-gear"></i> Part, click the <i class="fa-solid fa-trash"></i> "Delete" icon next to it in the <i class="fa-solid fa-gear"></i> "Parts" listing when viewing a specific <i class="fa-solid fa-ticket"></i>Ticket.</p>
                    </div>
                </div>
            </div>
        </div>
        <h2>Managing Assets</h2>
        <div class="accordion accordion-flush" id="accordionFlushExample4">
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingTen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTen" aria-expanded="false" aria-controls="flush-collapseTen">
                        What are Assets?
                    </button>
                </h3>
                <div id="flush-collapseTen" class="accordion-collapse collapse" aria-labelledby="flush-headingTen" data-bs-parent="#accordionFlushExample4">
                    <div class="accordion-body">
                        <p><i class="fa-solid fa-barcode"></i> Assets are CABOCES property you use to help resolve a <i class="fa-solid fa-ticket"></i> Ticket, or are associated with the <i class="fa-solid fa-ticket"></i> Ticket in some way. For example, a BOCES employee's phone stops working. You would tag the <i class="fa-solid fa-barcode"></i> Asset to the <i class="fa-solid fa-ticket"></i> Ticket with the asset tag to let other <i class="fa-solid fa-users"></i> Technicians know what is going on and exactly which phone is associated with the <i class="fa-solid fa-ticket"></i> Ticket.</p>
                        <p>In the billing and reporting system, <i class="fa-solid fa-barcode"></i> Assets have no useful information to consider for billing purposes. If a <i class="fa-solid fa-gear"></i> Part was purchased to replace a <i class="fa-solid fa-barcode"></i> Asset, it must first become an official CABOCES <i class="fa-solid fa-barcode"></i> Asset.</p>
                        <p>The Helpdesk application nor do any Website Developers have any authority to create, update, or delete records on the official CABOCES Inventory database.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingEleven">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEleven" aria-expanded="false" aria-controls="flush-collapseEleven">
                        How do I add an Asset?
                    </button>
                </h3>
                <div id="flush-collapseEleven" class="accordion-collapse collapse" aria-labelledby="flush-headingEleven" data-bs-parent="#accordionFlushExample4">
                    <div class="accordion-body">
                        <p>To add a <i class="fa-solid fa-barcode"></i> Asset, visit the specific <i class="fa-solid fa-ticket"></i> Ticket you would like to add <i class="fa-solid fa-barcode"></i> Asset to.</p> 
                        <p>You will need to know the specific asset tag of physical CABOCES property to add the <i class="fa-solid fa-barcode"></i> Asset.</p>
                        <p><em>Note: </em> unfortunately, some <i class="fa-solid fa-barcode"></i> Assets are not properly logged to the CABOCES Inventory database. So sometimes, you may encounter a "Asset Tag was invalid" error, which means that the database found no instance of that asset tag.</p>
                        <p>This seems to be a problem with how the Inventory database is updated.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingTwelve">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwelve" aria-expanded="false" aria-controls="flush-collapseTwelve">
                        How do I remove an Asset?
                    </button>
                </h3>
                <div id="flush-collapseTwelve" class="accordion-collapse collapse" aria-labelledby="flush-headingTwelve" data-bs-parent="#accordionFlushExample4">
                    <div class="accordion-body">
                        <p>Unlike some of the other <i class="fa-solid fa-ticket"></i> Ticket related entities, you can actually delete <i class="fa-solid fa-barcode"></i> Assets! 😎.</p>
                        <p>Because <i class="fa-solid fa-barcode"></i> Assets are not important for billing or reporting activites, they can be removed from <i class="fa-solid fa-ticket"></i> Tickets as needed.</p>
                        <p>Please note, however, that because an <i class="fa-solid fa-barcode"></i> Asset is a simple tag to CABOCES property, when you delete an <i class="fa-solid fa-barcode"></i> Asset, the CABOCES property entry in the Inventory database is not deleted. <i class="fa-solid fa-barcode"></i> Assets are merely tags that some specific CABOCES property is associated with the ticket.</p>
                    </div>
                </div>
            </div>
        </div>
        <h2>Managing Time Entries</h2>
        <div class="accordion accordion-flush" id="accordionFlushExample5">
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingThirteen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThirteen" aria-expanded="false" aria-controls="flush-collapseThirteen">
                        What are Time Entries?
                    </button>
                </h3>
                <div id="flush-collapseThirteen" class="accordion-collapse collapse" aria-labelledby="flush-headingThirteen" data-bs-parent="#accordionFlushExample5">
                    <div class="accordion-body">
                        <p><i class="fa-solid fa-clock"></i> Time Entries are the times <i class="fa-solid fa-users"></i> Technicians spent on resolving <i class="fa-solid fa-ticket"></i> Tickets.</p>
                        <p>They can only logged be in quarter hour increments (e.g., 0.00, 0.25, 0.50, 0.75), and contain times organized into <em>Tech Time</em>, <em>Travel Time</em>, <em>Overtime</em>, and <em>Itinerate Time</em>.</p>
                        <p>All <i class="fa-solid fa-clock"></i> Time Entries are billed at whatever the current rate is for that <i class="fa-solid fa-ticket"></i> Ticket's job type.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingFourteen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFourteen" aria-expanded="false" aria-controls="flush-collapseFourteen">
                        How do I add a Time Entry?
                    </button>
                </h3>
                <div id="flush-collapseFourteen" class="accordion-collapse collapse" aria-labelledby="flush-headingFourteen" data-bs-parent="#accordionFlushExample5">
                    <div class="accordion-body">
                        <p>You cannot create <i class="fa-solid fa-clock"></i> Time Entries outside <i class="fa-solid fa-ticket"></i> Tickets.</p>
                        <p>To add a <i class="fa-solid fa-clock"></i> Time Entry, visit the specific <?= Html::a(Html::decode('<i class="fa-solid fa-ticket"></i>') . ' Ticket ' . Html::decode('<i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/ticket/index']), ['target' => '_blank'] ) ?> you would like to add <i class="fa-solid fa-clock"></i> Time Entry to, and click the "New time entry" button in the dark navigation menu or in the "Time Entries" tab.</p> 
                        <p>You will need to know the <em>Tech Time</em>, <em>Travel Time</em>, <em>Overtime</em>, or <em>Itinerate Time</em> spent on the <i class="fa-solid fa-ticket"></i> Ticket, as well as the <i class="fa-solid fa-users"></i> Technician.</p>
                        <p>You can optionally enter a note for certain <i class="fa-solid fa-clock"></i> Time Entries if applicable to let other <i class="fa-solid fa-users"></i> Technicians know what you accomplished if necessary.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingFifteen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFifteen" aria-expanded="false" aria-controls="flush-collapseFifteen">
                        How do I remove a Time Entry?
                    </button>
                </h3>
                <div id="flush-collapseFifteen" class="accordion-collapse collapse" aria-labelledby="flush-headingFifteen" data-bs-parent="#accordionFlushExample5">
                    <div class="accordion-body">
                        <p>You can remove <i class="fa-solid fa-clock"></i> Time Entries as needed. Go to the specific <i class="fa-solid fa-ticket"></i> Ticket you wish to remove <i class="fa-solid fa-clock"></i> Time Entries, go to the "Time Entries" tab, and click the <i class="fa-solid fa-trash"></i> "Delete" icon to the left of the <i class="fa-solid fa-clock"></i> Time Entry you wish to delete.</i></p>
                    </div>
                </div>
            </div>
        </div>
        <h2>Managing Inventory</h2>
        <div class="accordion accordion-flush" id="accordionFlushExample6">
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingSixteen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSixteen" aria-expanded="false" aria-controls="flush-collapseSixteen">
                        What is Inventory?
                    </button>
                </h3>
                <div id="flush-collapseSixteen" class="accordion-collapse collapse" aria-labelledby="flush-headingSixteen" data-bs-parent="#accordionFlushExample6">
                    <div class="accordion-body">
                        <p><i class="fa-solid fa-box"></i> Inventory refers to CABOCES property and assets. Inventory entries cannot be created, modified, or deleted.</p>
                        <p>This part of the application is mostly separate from the Helpdesk application, but exists to help <i class="fa-solid fa-users"></i> Technicians search or review certain CABOCES property.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingSeventeen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeventeen" aria-expanded="false" aria-controls="flush-collapseSeventeen">
                        How do I search Inventory?
                    </button>
                </h3>
                <div id="flush-collapseSeventeen" class="accordion-collapse collapse" aria-labelledby="flush-headingSeventeen" data-bs-parent="#accordionFlushExample6">
                    <div class="accordion-body">
                        <p>You can search <i class="fa-solid fa-box"></i> Inventory by going to the <?= Html::a('Inventory Search ' . Html::decode('<i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/inventory/search']), ['target' => '_blank']) ?> page.</p>
                        <p>There are many options to use to search specific <i class="fa-solid fa-box"></i> Inventory, and keeping fields empty will search as normal.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingEighteen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEighteen" aria-expanded="false" aria-controls="flush-collapseEighteen">
                        How do I loan Inventory to external users?
                    </button>
                </h3>
                <div id="flush-collapseEighteen" class="accordion-collapse collapse" aria-labelledby="flush-headingEighteen" data-bs-parent="#accordionFlushExample6">
                    <div class="accordion-body">
                        <p>You can loan <i class="fa-solid fa-box"></i> Inventory to external users by visting the <?= Html::a('New Loaned Inventory ' . Html::decode('<i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/inventory/create-loaned-inventory']), ['target' => '_blank']) ?> page.</p>
                        <p>Select a certain <i class="fa-solid fa-box"></i> Inventory item by its asset tag. Then, fill out explicit details about who is borrowing it, their contact details, their reason, and the date.</p>
                        <p>Candidates for loaning <i class="fa-solid fa-box"></i> Inventory could be other New York BOCES organizations or trustworthy external partners.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="flush-headingNinteen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseNinteen" aria-expanded="false" aria-controls="flush-collapseNinteen">
                        How do I mark loaned Inventory as returned?
                    </button>
                </h3>
                <div id="flush-collapseNinteen" class="accordion-collapse collapse" aria-labelledby="flush-headingNinteen" data-bs-parent="#accordionFlushExample6">
                    <div class="accordion-body">
                        <p>You can mark loaned <i class="fa-solid fa-box"></i> Inventory as returned by visting the <?= Html::a('Return Loaned Inventory ' . Html::decode('<i class="fa-solid fa-arrow-up-right-from-square"></i>'), Url::toRoute(['/inventory/return-loaned-inventory']), ['target' => '_blank']) ?> page. Simply select the asset tag in the dropdown and click "Return" to mark the Inventory item as returned.</p>
                        <p>You can view the history of loaned Inventory by selecting the "Loanded Inventory History" tab. This will show all Inventory items that were borrowed and then returned.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>