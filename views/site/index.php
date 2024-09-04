<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */

$this->title = 'Dashboard';
?>
<div class="site-index">
    <div class="title-icon d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor" class="bi bi-speedometer" viewBox="0 0 16 16">
            <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2M3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.39.39 0 0 0-.029-.518z" />
            <path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.95 11.95 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0" />
        </svg>
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div>
        <p>Welcome! You are accessing an early version of the CA BOCES Help Desk 2 app. Features and behaviours of this app are not final. The information accessible in this version may be sensitive. <strong>Do not share your login information for this app with anyone</strong>.</p>
        <p>There are several unfinished features in the application. You are encouraged to:
            <ul>
                <li>Note parts you feel are incomplete, even if you believe them to be known to the developers. Your feedback can help shape how these features will be implemented.</li>
                <li>Note your frustrations and confusion. This will not only help with developing features further, but also give the developers a sense of what the "Help" section should look like for future users.</li>
                <li>Test (and/or break) everything! Breaking things now will make the application stronger in the future.</li>
            </ul>
        <p>We will have follow up meetings to discuss your experiences at a later date.</p>
        <p>Please contact Emma Fox (Programmer, <a href="mailto:emma_fox@caboces.org">emma_fox@caboces.org</a>) or Frank Wilson (IT Project Manager, <a href="mailto:frank_wilson@caboces.org">frank_wilson@caboces.org</a>) with any urgent concerns regarding the application.</p>
        <p>Thank you for your time spent testing this application!</p>
    </div>
</div>