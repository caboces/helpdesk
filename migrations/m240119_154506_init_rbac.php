<?php

use app\models\AuthAssignment;
use app\models\AuthItem;
use app\models\AuthItemChild;
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
        // roles in the system: 
        // - admin
        // - tech lead
        // - tech
        // - summer-help

        // ============================= ROLES ============================= //

        // add admin role
        $admin = $auth->createRole('admin');
        $auth->add('admin');

        // add tech_lead role
        $tech_lead = $auth->createRole('tech-lead');
        $auth->add('tech-lead');

        // add tech role
        $tech = $auth->createRole('tech');
        $auth->add('tech');

        // add summer_help role
        $summer_help = $auth->createRole('summer-help');
        $auth->add('summer-help');

        $roles = [
            'admin' => $admin,
            'tech-lead' => $tech_lead,
            'tech' => $tech,
            'summer-help' => $summer_help,
        ];

        // ============================= PERMISSIONS ============================= //

        // deleteSelfCreated - permission that allows users to delete an entity if they created it.

        $entities = [
            'asset' => [
                'label' => 'Asset',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view Asset Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to create Assets.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view a single Asset.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to update all Assets.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to delete all Assets.',
                    ]
                ],
            ],
            'blocked_ip_address' => [
                'label' => 'BlockedIPAddress',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to view Blocked IP Address Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to create Blocked IP Addresses.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to view a single Blocked IP Address.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to update all Blocked IP Addresses.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to delete all Blocked IP Addresses.',
                    ]
                ],
            ],
            'department' => [
                'label' => 'Department',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view Department Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to create Departments.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view a single Department.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to update all Departments.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to delete all Departments.',
                    ]
                ],
            ],
            'district' => [
                'label' => 'District',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view District Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to create Districts.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to all Districts.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to update all Districts.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to delete all Districts.',
                    ]
                ],
            ],
            'building' => [
                'label' => 'Building',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view Building Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to create Buildings.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view a single Building.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to update all Buildings.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to delete all Buildings.',
                    ]
                ],
            ],
            'division' => [
                'label' => 'Division',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view Division Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to create Divisions.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view a single Division.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to update all Divisions.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to delete all Divisions.',
                    ]
                ],
            ],
            'hourly_rate' => [
                'label' => 'HourlyRate',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view Hourly Rate Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to create Hourly Rates.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view a single Hourly Rate.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to update all Hourly Rates.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to delete all Hourly Rates.',
                    ]
                ],
            ],
            'job_category' => [
                'label' => 'JobCategory',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view Job Category Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to create Job Categories.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view a single Job Category.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to update all Job Categories.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to delete all Job Categories.',
                    ]
                ],
            ],
            'job_type' => [
                'label' => 'JobType',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view Job Types Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to create Job Types.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view a single Job Type.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to update all Job Types.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to delete all Job Types.',
                    ],
                ],
            ],
            'loaned_inventory' => [
                'label' => 'LoanedInventory',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to view Loaned Inventory Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to create Loaned Inventory.',
                    ],
                    'deleteSelfCreated' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to delete Loaned Inventory they created.',
                    ],
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to view a single Loaned Inventory.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to update all Loaned Inventory.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to delete Loaned Inventory.',
                    ],
                    'return' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to return Loaned Inventory.',
                    ]
                ],
            ],
            'part' => [
                'label' => 'District',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view Part Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to create Parts.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view a single Part.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to update all Parts.',
                    ],
                    'updateForOpenTickets' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to update all Parts associated with Open Tickets.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to delete all Parts.',
                    ],
                    'deleteForOpenTickets' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to delete Parts associated with Open Tickets.',
                    ]
                ],
            ],
            'part_type' => [
                'label' => 'PartType',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to view Part Type Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to create Part Types.',
                    ], 
                    'deleteSelfCreated' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to delete Part Types they created.',
                    ],
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view a single Part Type.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to update all Part Types.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to delete all Part Types.',
                    ],
                ],
            ],
            'ticket' => [
                'label' => 'Ticket',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view Ticket Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to create Tickets.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to delete view a single Ticket.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to update all Tickets.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to delete all Tickets.',
                    ],
                    'deleteSelfCreated' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to delete Tickets they created.',
                    ],
                    'resolve' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to resolve Tickets.',
                    ],
                    'close' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to close Tickets.',
                    ]
                ],
            ],
            'ticket_draft' => [
                'label' => 'TicketDraft',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view Ticket Draft Management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to create Ticket Drafts.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view a single Ticket Draft.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to update all Ticket Drafts.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead', 'tech'],
                        'description' => 'Allows users to delete all Ticket Drafts.',
                    ],
                ],
            ],
            'time_entry' => [
                'label' => 'TimeEntry',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view Time Entry management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to create Time Entries.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view a single Time Entry.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to update all Time Entries.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to delete all Time Entries.',
                    ],
                    'deleteSelfCreated' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users do delete Time Entries created by themselves.',
                    ]
                ],
            ],
            'user' => [
                'label' => 'User',
                'permissions' => [
                    'view' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to view User management.',
                    ],
                    'create' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to create Users.',
                    ], 
                    'read' => [
                        'roles' => ['admin', 'tech-lead', 'tech', 'summer-help'],
                        'description' => 'Allows users to view a single User.',
                    ],
                    'update' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to update all Users.',
                    ],
                    'delete' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to delete all Users.',
                    ],
                    'toggleStatus' => [
                        'roles' => ['admin', 'tech-lead'],
                        'description' => 'Allows users to toggle status of all Users.',
                    ],
                ],
            ]
        ];

        foreach ($entities as $entityName => $entity) {
            $label = $entity['label'];
            $permissions = $entity['permissions'];

            foreach ($permissions as $permissionName => $permission) {
                $permissionRoles = $permission['roles'];

                $authItem = $auth->createPermission($permissionName . $label);
                $authItem->description = $permission['description'];
                $auth->add($authItem);

                foreach ($permissionRoles as $role) {
                    $roles[$role]->addChild($role, $authItem);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        AuthItemChild::deleteAll();
        AuthItem::deleteAll();

        return true;
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
