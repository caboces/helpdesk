<?php

use yii\db\Migration;

/**
 * Class m240119_154506_init_rbac
 */
class m240119_154506_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // ============================= PERMISSIONS ============================= //

        // add "viewTicket" permission
        $viewTicket = $auth->createPermission('viewTicket');
        $viewTicket->description = 'View a ticket';
        $auth->add($viewTicket);

        // add "createTicket" permission
        $createTicket = $auth->createPermission('createTicket');
        $createTicket->description = 'Create a ticket';
        $auth->add($createTicket);

        // add "updateTicket" permission
        $updateTicket = $auth->createPermission('updateTicket');
        $updateTicket->description = 'Update a ticket';
        $auth->add($updateTicket);

        // add "deleteTicket" permission
        $deleteTicket = $auth->createPermission('deleteTicket');
        $deleteTicket->description = 'Delete a ticket';
        $auth->add($deleteTicket);

        // add "resolveTicket" permission
        $resolveTicket = $auth->createPermission('resolveTicket');
        $resolveTicket->description = 'Resolve a ticket';
        $auth->add($resolveTicket);

        // add "closeTicket" permission
        $closeTicket = $auth->createPermission('closeTicket');
        $closeTicket->description = 'Close a ticket';
        $auth->add($closeTicket);

        // add "createUser" permission
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Create a new user';
        $auth->add('$createUser');

        // add "updateUser" permission
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update a user';
        $auth->add('$updateUser');

        // add "deactivateUser" permission
        $deactivateUser = $auth->createPermission('deactivateUser');
        $deactivateUser->description = 'Deactivate a user';
        $auth->add('$deactivateUser');

        // add "deleteUser" permission
        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete a user';
        $auth->add('$deleteUser');

        // ============================= ROLES ============================= //

        // add admin role
        $admin = $auth->createRole('admin');
        $auth->add('admin');


        // add tech_lead role
        $tech_lead = $auth->createRole('tech_lead');
        $auth->add('tech_lead');
        $auth->addChild($tech_lead, $viewTicket);


        // add tech role
        $tech = $auth->createRole('tech');
        $auth->add('tech');
        $auth->addChild($tech, $viewTicket);


        // add summer_help role
        $summer_help = $auth->createRole('summer_help');
        $auth->add('summer_help');
        $auth->addChild($summer_help, $viewTicket);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240119_154506_init_rbac cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240119_154506_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
