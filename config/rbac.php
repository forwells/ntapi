<?php

/**
 * RBAC Configuration
 */

return [
    /** rbac tables */
    'tables' => [
        'users' => 'admin_users',
        'roles' => 'admin_roles',
        'permissions' => 'admin_permissions',
        'menus' => 'admin_menus',
        'user_roles' => 'admin_user_roles',
        'role_permissions' => 'admin_role_permissions',
        'role_menus' => 'admin_role_menus',
    ],
    /** auth */
    'auth' => [
        'except' => [
            'api/be/auth/login'
        ]
    ]
];
