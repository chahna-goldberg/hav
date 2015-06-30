<?php
return array(
    'router' => array(
        'routes' => array(
            'authenticate.rpc.login' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/auth/login',
                    'defaults' => array(
                        'controller' => 'Authenticate\\V1\\Rpc\\Login\\Controller',
                        'action' => 'login',
                    ),
                ),
            ),
            'authenticate.rpc.logout' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/auth/logout',
                    'defaults' => array(
                        'controller' => 'Authenticate\\V1\\Rpc\\Logout\\Controller',
                        'action' => 'logout',
                    ),
                ),
            ),
            'authenticate.rpc.set-garden' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/auth/set_garden',
                    'defaults' => array(
                        'controller' => 'Authenticate\\V1\\Rpc\\SetGarden\\Controller',
                        'action' => 'setGarden',
                    ),
                ),
            ),
            'authenticate.rpc.set-password' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/auth/password/update',
                    'defaults' => array(
                        'controller' => 'Authenticate\\V1\\Rpc\\SetPassword\\Controller',
                        'action' => 'setPassword',
                    ),
                ),
            ),
            'authenticate.rpc.send-password-reset' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/auth/password/send-reset',
                    'defaults' => array(
                        'controller' => 'Authenticate\\V1\\Rpc\\SendPasswordReset\\Controller',
                        'action' => 'sendPasswordReset',
                    ),
                ),
            ),
            'authenticate.rpc.reset-password' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/auth/password/reset',
                    'defaults' => array(
                        'controller' => 'Authenticate\\V1\\Rpc\\ResetPassword\\Controller',
                        'action' => 'resetPassword',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'authenticate.rpc.login',
            1 => 'authenticate.rpc.logout',
            2 => 'authenticate.rpc.set-garden',
            3 => 'authenticate.rpc.set-password',
            4 => 'authenticate.rpc.send-password-reset',
            5 => 'authenticate.rpc.reset-password',
        ),
    ),
    'service_manager' => array(
        'factories' => array(),
    ),
    'zf-rest' => array(),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Authenticate\\V1\\Rpc\\Login\\Controller' => 'Json',
            'Authenticate\\V1\\Rpc\\Logout\\Controller' => 'Json',
            'Authenticate\\V1\\Rpc\\SetGarden\\Controller' => 'Json',
            'Authenticate\\V1\\Rpc\\SetPassword\\Controller' => 'Json',
            'Authenticate\\V1\\Rpc\\SendPasswordReset\\Controller' => 'Json',
            'Authenticate\\V1\\Rpc\\ResetPassword\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'Authenticate\\V1\\Rpc\\Login\\Controller' => array(
                0 => 'application/vnd.authenticate.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Authenticate\\V1\\Rpc\\Logout\\Controller' => array(
                0 => 'application/vnd.authenticate.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Authenticate\\V1\\Rpc\\SetGarden\\Controller' => array(
                0 => 'application/vnd.authenticate.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Authenticate\\V1\\Rpc\\SetPassword\\Controller' => array(
                0 => 'application/vnd.authenticate.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Authenticate\\V1\\Rpc\\SendPasswordReset\\Controller' => array(
                0 => 'application/vnd.authenticate.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Authenticate\\V1\\Rpc\\ResetPassword\\Controller' => array(
                0 => 'application/vnd.authenticate.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'Authenticate\\V1\\Rpc\\Login\\Controller' => array(
                0 => 'application/vnd.authenticate.v1+json',
                1 => 'application/json',
            ),
            'Authenticate\\V1\\Rpc\\Logout\\Controller' => array(
                0 => 'application/vnd.authenticate.v1+json',
                1 => 'application/json',
            ),
            'Authenticate\\V1\\Rpc\\SetGarden\\Controller' => array(
                0 => 'application/vnd.authenticate.v1+json',
                1 => 'application/json',
            ),
            'Authenticate\\V1\\Rpc\\SetPassword\\Controller' => array(
                0 => 'application/vnd.authenticate.v1+json',
                1 => 'application/json',
            ),
            'Authenticate\\V1\\Rpc\\SendPasswordReset\\Controller' => array(
                0 => 'application/vnd.authenticate.v1+json',
                1 => 'application/json',
            ),
            'Authenticate\\V1\\Rpc\\ResetPassword\\Controller' => array(
                0 => 'application/vnd.authenticate.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(),
    ),
    'zf-content-validation' => array(
        'Authenticate\\V1\\Rpc\\Login\\Controller' => array(
            'input_filter' => 'Authenticate\\V1\\Rpc\\Login\\Validator',
        ),
        'Authenticate\\V1\\Rpc\\SetGarden\\Controller' => array(
            'input_filter' => 'Authenticate\\V1\\Rpc\\SetGarden\\Validator',
        ),
        'Authenticate\\V1\\Rpc\\SetPassword\\Controller' => array(
            'input_filter' => 'Authenticate\\V1\\Rpc\\SetPassword\\Validator',
        ),
        'Authenticate\\V1\\Rpc\\SendPasswordReset\\Controller' => array(
            'input_filter' => 'Authenticate\\V1\\Rpc\\SendPasswordReset\\Validator',
        ),
        'Authenticate\\V1\\Rpc\\ResetPassword\\Controller' => array(
            'input_filter' => 'Authenticate\\V1\\Rpc\\ResetPassword\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'Authenticate\\V1\\Rest\\Login\\Validator' => array(
            0 => array(
                'name' => 'username',
                'required' => true,
                'filters' => array(),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\Alnum',
                        'options' => array(),
                    ),
                ),
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
            1 => array(
                'name' => 'gardenName',
                'required' => true,
                'filters' => array(),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\Alnum',
                        'options' => array(),
                    ),
                ),
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
            2 => array(
                'name' => 'password',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
        ),
        'Authenticate\\V1\\Rpc\\Login\\Validator' => array(
            0 => array(
                'name' => 'username',
                'required' => true,
                'filters' => array(),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\Alnum',
                        'options' => array(),
                    ),
                ),
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
            1 => array(
                'name' => 'password',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
        ),
        'Authenticate\\V1\\Rpc\\SetGarden\\Validator' => array(
            0 => array(
                'name' => 'id',
                'required' => true,
                'filters' => array(),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Digits',
                        'options' => array(),
                    ),
                ),
            ),
        ),
        'Authenticate\\V1\\Rpc\\SetPassword\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => '6',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'oldPassword',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => '6',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'newPassword',
            ),
        ),
        'Authenticate\\V1\\Rpc\\SendPasswordReset\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'ZF\\ContentValidation\\Validator\\DbRecordExists',
                        'options' => array(
                            'adapter' => 'dbAdapter',
                            'table' => 'ign_users',
                            'field' => 'username',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'username',
            ),
        ),
        'Authenticate\\V1\\Rpc\\ResetPassword\\Validator' => array(
            0 => array(
                'name' => 'token',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                        'options' => array(),
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                            'min' => '32',
                        ),
                    ),
                ),
                'description' => '32 characters token',
                'allow_empty' => false,
                'continue_if_empty' => false,
            ),
            1 => array(
                'name' => 'newPassword',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                        'options' => array(),
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => '6',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Authenticate\\V1\\Rpc\\Login\\Controller' => 'Authenticate\\V1\\Rpc\\Login\\LoginControllerFactory',
            'Authenticate\\V1\\Rpc\\Logout\\Controller' => 'Authenticate\\V1\\Rpc\\Logout\\LogoutControllerFactory',
            'Authenticate\\V1\\Rpc\\SetGarden\\Controller' => 'Authenticate\\V1\\Rpc\\SetGarden\\SetGardenControllerFactory',
            'Authenticate\\V1\\Rpc\\SetPassword\\Controller' => 'Authenticate\\V1\\Rpc\\SetPassword\\SetPasswordControllerFactory',
            'Authenticate\\V1\\Rpc\\SendPasswordReset\\Controller' => 'Authenticate\\V1\\Rpc\\SendPasswordReset\\SendPasswordResetControllerFactory',
            'Authenticate\\V1\\Rpc\\ResetPassword\\Controller' => 'Authenticate\\V1\\Rpc\\ResetPassword\\ResetPasswordControllerFactory',
        ),
    ),
    'zf-rpc' => array(
        'Authenticate\\V1\\Rpc\\Login\\Controller' => array(
            'service_name' => 'Login',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'authenticate.rpc.login',
        ),
        'Authenticate\\V1\\Rpc\\Logout\\Controller' => array(
            'service_name' => 'Logout',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'authenticate.rpc.logout',
        ),
        'Authenticate\\V1\\Rpc\\SetGarden\\Controller' => array(
            'service_name' => 'SetGarden',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'authenticate.rpc.set-garden',
        ),
        'Authenticate\\V1\\Rpc\\SetPassword\\Controller' => array(
            'service_name' => 'SetPassword',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'authenticate.rpc.set-password',
        ),
        'Authenticate\\V1\\Rpc\\SendPasswordReset\\Controller' => array(
            'service_name' => 'SendPasswordReset',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'authenticate.rpc.send-password-reset',
        ),
        'Authenticate\\V1\\Rpc\\ResetPassword\\Controller' => array(
            'service_name' => 'ResetPassword',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'authenticate.rpc.reset-password',
        ),
    ),
);
