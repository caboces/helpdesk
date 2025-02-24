<?php

namespace app\widgets;

use app\models\User;
use yii\base\Widget;
use app\models\Ticket;
use yii\bootstrap5\Html;

/**
 * Class ActivityWidget
 * 
 * @author Emma Fox
 * @package app\widgets

 * TODO: add to init properties that will handle a range of dates as well as
 * user id's
 */

class ActivityWidget extends Widget
{
    // Note to self: handling the data/input should go here rather than in run
    public function init()
    {
        parent::init();
        ob_start();
    }

    public function run()
    {
        parent::run();
        $output = ob_get_clean();

        $db = \Yii::$app->db;
        $entries = $db->createCommand('SELECT * FROM activity ORDER BY created DESC LIMIT 10')->queryAll();

        foreach ($entries as $entry) {
            // this is the user object
            $user = User::findOne($entry['user_id']);
            $username = ucwords($user->username);

            // entry stuff
            $type =  ucfirst($entry['type']);
            $icon = $this->typeIcon($type);
            $description = $entry['description'];
            $created = $entry['created'];


            // trying to reduce ugliness by chunking up the data
            // $header = 'Ticket ' . $ticket_id . ' (' . $summary . ')';
            $header = $this->createEntryHeader($entry);
            $subheader = 'Action: ' . $type . ' by ' . $username;
            $body = 'Description: ' . $description;
            $footer = date('F j, Y g:i A', strtotime($created . ' UTC'));

            $entry_test = '
            <div class="row mb-3">
                <div class="col-auto">
                    ' . Html::tag('div', $icon, ['class' => 'icon']) . '
                </div>
                <div class="col">
                    <div class="entry">'
                . $header
                . Html::tag('p', $subheader)
                . Html::tag('p', $body)
                . Html::tag('p', $footer, ['class' => 'activity-date']) . '
                    </div>
                </div>
            </div>
            ';

            echo $entry_test;
        }
    }

    /**
     * Creates the entry header
     */

    public function createEntryHeader($entry)
    {
        $ticket_id = $entry['ticket_id'];
        $ticket = Ticket::findOne($ticket_id);
        $summary = ucfirst($ticket->summary);
        $color = 'text-info';

        // construct contents
        $header = 'Ticket ' . $ticket_id . ' | ' . $summary;

        // return contents in p tag with necessary class
        return Html::a($header, ['ticket/view', 'id' => $ticket_id]);
    }

    /**
     * Converts entry type input into an actual svg
     */
    public function typeIcon($type)
    {
        switch ($type) {
            case 'Update':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="#78dbff" class="bi bi-pencil-fill" viewBox="0 0 16 16" aria-hidden="true"><path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/></svg>';
                break;
            case 'Delete':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="#d1312e" class="bi bi-trash-fill" viewBox="0 0 16 16" aria-hidden="true"><path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/></svg>';
                break;
            case 'Create':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="#94b54a" class="bi bi-plus-square-fill" viewBox="0 0 16 16" aria-hidden="true"><path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0"/></svg>';
                break;
            default:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="#fff" class="bi bi-three-dots" viewBox="0 0 16 16" aria-hidden="true"><path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"/></svg>';
        }
    }
}
