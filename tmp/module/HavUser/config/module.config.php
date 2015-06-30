<?php
return array(

    'acl' => array(
        'resources' => array(
            'user_login'                        => null,
            'user_login_process'                => null,
            'user_logout'                       => null,
            'list_accounts'                     => null,
            'account_view'                      => null,
            'account_update'                    => null,
            'account_add'                       => null,
            'account_delete'                    => null,
            'account_clean'                     => null,
            'group_view'                        => null,
            'group_update'                      => null,
            'group_add'                         => null,
            'group_delete'                      => null,
            'user_view'                         => null,
            'user_add'                          => null,
            'user_update'                       => null,
            'user_update_password'              => null,
            'user_delete'                       => null,
            /* ------ API Resources ------ */
            'api_group_subscribe_users'         => null,
            'api_group_unsubscribe_users'       => null,
            'api_user_login_process'            => null,
            'api_user_logout'                   => null,
            'api_list_shareable_users'          => null,
        ),
        'rules' => array(
            'allow' => array(
                'user_login'                    => 'guest',
                'user_login_process'            => 'guest',
                'user_logout'                   => 'guest',
                'list_accounts'                 => 'bp admin',
                'account_view'                  => 'bp admin',
                'account_update'                => 'bp admin',
                'account_add'                   => 'bp admin',
                'account_delete'                => 'bp admin',
                'account_clean'                 => 'bp admin',
                'group_view'                    => 'bp admin',
                'group_update'                  => 'bp admin',
                'group_add'                     => 'bp admin',
                'group_delete'                  => 'bp admin',
                'user_view'                     => 'bp admin',
                'user_add'                      => 'bp admin',
                'user_update'                   => 'bp admin',
                'user_update_password'          => 'bp admin',
                'user_delete'                   => 'bp admin',
                /* ------ API Rules ------ */
                'api_group_subscribe_users'     => 'bp admin',
                'api_group_unsubscribe_users'   => 'bp admin',
                'api_user_login_process'        => 'guest',
                'api_user_logout'               => 'guest',
                'api_list_shareable_users'      => 'bp admin',
            ),
        ),
    ),

    'breadcrumb_trackable_pages' => array (
        'list_accounts' => 'Accounts',
        'account_view'  => 'Account',
        'group_view'    => 'Group',
        'user_view'     => 'User',
        'user_add'      => 'Add User',
    ),

    'view_manager' => array(
        'template_map' => array(
            'brainpop-user/user/login'              => __DIR__ . '/../view/user/login.phtml',
            'brainpop-user/user/list'               => __DIR__ . '/../view/user/list.phtml',
            'brainpop-user/user/group_association'  => __DIR__ . '/../view/user/group_association.phtml',
            'brainpop-user/user/view'               => __DIR__ . '/../view/user/view.phtml',
            'brainpop-user/user/add'                => __DIR__ . '/../view/user/add.phtml',
            'brainpop-user/user/update-password'    => __DIR__ . '/../view/user/update_password.phtml',
            'brainpop-user/account/list'            => __DIR__ . '/../view/account/list.phtml',
            'brainpop-user/account/view'            => __DIR__ . '/../view/account/view.phtml',
            'brainpop-user/account/add'             => __DIR__ . '/../view/account/add.phtml',
            'brainpop-user/group/list'              => __DIR__ . '/../view/group/list.phtml',
            'brainpop-user/group/view'              => __DIR__ . '/../view/group/view.phtml',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'account'   => 'HavUser\Controller\AccountController',
            'group'     => 'HavUser\Controller\GroupController',
            'user'      => 'HavUser\Controller\UserController',
            'api_group' => 'HavUser\Controller\ApiGroupController',
            'api_user'  => 'HavUser\Controller\ApiUserController',
        ),
    ),

    'router' => array(
        'routes' => array(

            'user_login' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/edit/login',
                    'defaults' => array(
                        'controller' => 'user',
                        'action'     => 'login',
                    ),
                ),
            ), // End of user_login route

            'user_login_process' => array(
                'type'      => 'Literal',
                'options'   => array(
                    'route' => '/user/login/process',
                    'defaults' => array(
                        'controller' => 'user',
                        'action'     => 'loginProcess',
                    ),
                ),
            ), // End of user_login_process route

            'user_logout' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/user/logout',
                    'defaults' => array(
                        'controller' => 'user',
                        'action'     => 'logout',
                    ),
                ),
            ), // End of user_logout route

            'list_accounts' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/accounts/list',
                    'defaults' => array(
                        'controller' => 'account',
                        'action'     => 'list',
                    ),
                ),
            ), // End of list_accounts route

            'account_view' => array(
                'type'    => 'Segment',
                    'options' => array(
                    'route' => '/account/view/:account_id',
                    'defaults' => array(
                        'controller' => 'account',
                        'action'     => 'view',
                    ),
                ),
            ), // End of account_view route

            'account_update' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/account/update',
                    'defaults' => array(
                        'controller' => 'account',
                        'action'     => 'update',
                    ),
                ),
            ), // End of account_update route

            'account_add' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/account/add',
                    'defaults' => array(
                        'controller' => 'account',
                        'action'     => 'add',
                    ),
                ),
            ), // End of account_add route

            'account_delete' => array(
                'type'    => 'Segment',
                    'options' => array(
                    'route' => '/account/delete/:account_id',
                    'defaults' => array(
                        'controller' => 'account',
                        'action'     => 'delete',
                    ),
                ),
            ), // End of account_delete route

            'account_clean' => array(
                'type'    => 'Segment',
                    'options' => array(
                    'route' => '/account/clean/:account_id',
                    'defaults' => array(
                        'controller' => 'account',
                        'action'     => 'clean',
                    ),
                ),
            ), // End of account_clean route

            'group_view' => array(
                'type'    => 'Segment',
                    'options' => array(
                    'route' => '/group/view/:group_id',
                    'defaults' => array(
                        'controller' => 'group',
                        'action'     => 'view',
                    ),
                ),
            ), // End of group_view route

            'group_update' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/group/update',
                    'defaults' => array(
                        'controller' => 'group',
                        'action'     => 'update',
                    ),
                ),
            ), // End of group_update route

            'group_add' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/group/add',
                    'defaults' => array(
                        'controller' => 'group',
                        'action'     => 'add',
                    ),
                ),
            ), // End of group_add route

            'group_delete' => array(
                'type'    => 'Segment',
                    'options' => array(
                    'route' => '/group/delete/:group_id',
                    'defaults' => array(
                        'controller' => 'group',
                        'action'     => 'delete',
                    ),
                ),
            ), // End of group_delete route

            'user_view' => array(
                'type'    => 'Segment',
                    'options' => array(
                    'route' => '/user/view/:id',
                    'defaults' => array(
                        'controller' => 'user',
                        'action'     => 'view',
                    ),
                ),
            ), // End of user_view route

            'user_add' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/user/add',
                    'defaults' => array(
                        'controller' => 'user',
                        'action'     => 'add',
                    ),
                ),
            ), // End of user_add route

            'user_update' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/user/update',
                    'defaults' => array(
                        'controller' => 'user',
                        'action'     => 'update',
                    ),
                ),
            ), // End of user_update route

            'user_update_password' => array(
                'type'    => 'Segment',
                    'options' => array(
                    'route' => '/user/update/password/:id',
                    'defaults' => array(
                        'controller' => 'user',
                        'action'     => 'updatePassword',
                    ),
                ),
            ), // End of user_update_password route

            'user_delete' => array(
                'type'    => 'Segment',
                    'options' => array(
                    'route' => '/user/delete/:id',
                    'defaults' => array(
                        'controller' => 'user',
                        'action'     => 'delete',
                    ),
                ),
            ), // End of user_delete route

            /* ----------- API routes ----------- */

            'api_group_subscribe_users' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/api/group/subscribe/users',
                    'defaults' => array(
                        'controller' => 'api_group',
                        'action'     => 'subscribeUsers',
                    ),
                ),
            ), // End of api_group_subscribe_users route

            'api_group_unsubscribe_users' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/api/group/unsubscribe/users',
                    'defaults' => array(
                        'controller' => 'api_group',
                        'action'     => 'unsubscribeUsers',
                    ),
                ),
            ), // End of api_group_unsubscribe_users route

            'api_user_login_process' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/api/user/login/process',
                    'defaults' => array(
                        'controller' => 'api_user',
                        'action'     => 'loginProcess',
                    ),
                ),
            ), // End of user_login route

            'api_user_logout' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/api/user/logout',
                    'defaults' => array(
                        'controller' => 'api_user',
                        'action'     => 'logout',
                    ),
                ),
            ), // End of user_logout route

            'api_list_shareable_users' => array(
                'type'    => 'Literal',
                    'options' => array(
                    'route' => '/api/user/shareable_users',
                    'defaults' => array(
                        'controller' => 'api_user',
                        'action'     => 'listShareableUsers',
                    ),
                ),
            ), // End of api_list_shareable_users route

        ), // End of routes
    ), // End of router
);
