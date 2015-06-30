<?php
return array(
    'router' => array(
        'routes' => array(
            'hav.rest.signalling' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/signalling[/:signalling_id]',
                    'defaults' => array(
                        'controller' => 'hav\\V1\\Rest\\Signalling\\Controller',
                    ),
                ),
            ),
            'hav.rest.online-users' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/online-users[/:online_users_id]',
                    'defaults' => array(
                        'controller' => 'hav\\V1\\Rest\\OnlineUsers\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'hav.rest.signalling',
            1 => 'hav.rest.online-users',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'hav\\V1\\Rest\\Signalling\\SignallingResource' => 'hav\\V1\\Rest\\Signalling\\SignallingResourceFactory',
            'hav\\V1\\Rest\\OnlineUsers\\OnlineUsersResource' => 'hav\\V1\\Rest\\OnlineUsers\\OnlineUsersResourceFactory',
        ),
    ),
    'zf-rest' => array(
        'hav\\V1\\Rest\\Signalling\\Controller' => array(
            'listener' => 'hav\\V1\\Rest\\Signalling\\SignallingResource',
            'route_name' => 'hav.rest.signalling',
            'route_identifier_name' => 'signalling_id',
            'collection_name' => 'signalling',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'hav\\V1\\Rest\\Signalling\\SignallingEntity',
            'collection_class' => 'hav\\V1\\Rest\\Signalling\\SignallingCollection',
            'service_name' => 'signalling',
        ),
        'hav\\V1\\Rest\\OnlineUsers\\Controller' => array(
            'listener' => 'hav\\V1\\Rest\\OnlineUsers\\OnlineUsersResource',
            'route_name' => 'hav.rest.online-users',
            'route_identifier_name' => 'online_users_id',
            'collection_name' => 'online_users',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'hav\\V1\\Rest\\OnlineUsers\\OnlineUsersEntity',
            'collection_class' => 'hav\\V1\\Rest\\OnlineUsers\\OnlineUsersCollection',
            'service_name' => 'onlineUsers',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'hav\\V1\\Rest\\Signalling\\Controller' => 'HalJson',
            'hav\\V1\\Rest\\OnlineUsers\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'hav\\V1\\Rest\\Signalling\\Controller' => array(
                0 => 'application/vnd.hav.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'hav\\V1\\Rest\\OnlineUsers\\Controller' => array(
                0 => 'application/vnd.hav.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'hav\\V1\\Rest\\Signalling\\Controller' => array(
                0 => 'application/vnd.hav.v1+json',
                1 => 'application/json',
            ),
            'hav\\V1\\Rest\\OnlineUsers\\Controller' => array(
                0 => 'application/vnd.hav.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'hav\\V1\\Rest\\Signalling\\SignallingEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'hav.rest.signalling',
                'route_identifier_name' => 'signalling_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'hav\\V1\\Rest\\Signalling\\SignallingCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'hav.rest.signalling',
                'route_identifier_name' => 'signalling_id',
                'is_collection' => true,
            ),
            'hav\\V1\\Rest\\OnlineUsers\\OnlineUsersEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'hav.rest.online-users',
                'route_identifier_name' => 'online_users_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'hav\\V1\\Rest\\OnlineUsers\\OnlineUsersCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'hav.rest.online-users',
                'route_identifier_name' => 'online_users_id',
                'is_collection' => true,
            ),
        ),
    ),
);
