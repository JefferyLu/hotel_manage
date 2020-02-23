<?php
use backend\models\Admin;
return [
    'adminEmail' => 'admin@example.com',
    'pageSize' => [
        'admin' => 10,
        'auth' => 2,
        'role' => 2,
    ],
    //菜单
    'menu' => [
        [
            'auth_name' => '管理员管理',
            'child' => [
                [
                    'auth_name' => '管理员列表',
                    'auth_controller' => 'admin',
                    'auth_action' => 'index'
                ]
            ]
        ],
        [
            'auth_name' => '角色权限管理',
            'child' => [
                [
                    'auth_name' => '角色列表',
                    'auth_controller' => 'role',
                    'auth_action' => 'index'
                ]
            ]
        ],
        [
            'auth_name' => '客房信息',
            'child' => [
                [
                    'auth_name' => '客房分类列表',
                    'auth_controller' => 'room-cate',
                    'auth_action' => 'index'
                ],
                [
                    'auth_name' => '客房列表',
                    'auth_controller' => 'room',
                    'auth_action' => 'index'
                ],
            ]
        ],
        [
            'auth_name' => '客房预定入住管理',
            'child' => [
                [
                    'auth_name' => '客房预定管理',
                    'auth_controller' => 'reserve-info',
                    'auth_action' => 'index'
                ],
                [
                    'auth_name' => '客房入住管理',
                    'auth_controller' => 'lived-info',
                    'auth_action' => 'index'
                ],
                [
                    'auth_name' => '客房结算列表',
                    'auth_controller' => 'settle-order',
                    'auth_action' => 'index'
                ],
            ]
        ],
        [
            'auth_name' => '会员信息',
            'child' => [
                [
                    'auth_name' => '会员信息列表',
                    'auth_controller' => 'member',
                    'auth_action' => 'index'
                ],
            ]
        ],
        [
            'auth_name' => '操作日志',
            'child' => [
                [
                    'auth_name' => '操作日志列表',
                    'auth_controller' => 'operator-log',
                    'auth_action' => 'index'
                ]
            ]
        ],
    ],
    //权限
    'auth' => [
        '管理员管理' => [
            '列表' => 'admin/index',
            '新增管理员' => 'admin/create',
            '编辑管理员' => 'admin/update',
            '删除管理员' => 'admin/del',
            '修改密码' => 'admin/change-pass',
            '修改信息' => 'admin/change-info',
        ],
        '角色权限管理' => [
            '角色列表' => 'role/index',
            '添加角色' => 'role/create',
            '编辑角色' => 'role/update',
            '删除角色' => 'role/del',
        ],
        '客房信息管理' => [
            '客房列表' => 'room/index',
            '新增客房' => 'room/create',
            '编辑客房' => 'room/update',
            '删除客房' => 'room/del',
        ],
        '客房分类管理' => [
            '分类列表' => 'room-cate/index',
            '新增分类' => 'room-cate/create',
            '编辑分类' => 'room-cate/update',
            '删除分类' => 'room-cate/del',
        ],
        '客房预定管理' => [
            '预定列表' => 'reserve-info/index',
            '添加预定' => 'reserve-info/create',
            '查看预定' => 'reserve-info/show',
            '取消预定' => 'reserve-info/cancel',
            '预定转入住' => 'reserve-info/reserve-to-lived',
        ],
        '客房入住管理' => [
            '入住列表' => 'lived-info/index',
            '新增入住' => 'lived-info/create',
            '查看入住' => 'lived-info/show',
            '换房操作' => 'lived-info/change',
            '结算操作' => 'lived-info/cancel',
        ],
        '客房结算列表' => [
            '结算列表' => 'settle-order/index',
        ],
        '会员信息管理' => [
            '会员列表' => 'member/index',
            '新增会员' => 'member/create',
            '编辑会员' => 'member/update',
            '删除会员' => 'member/del',
        ],
        '操作日志管理' => [
            '日志列表' => 'operator-log/index',
            '删除日志' => 'operator-log/del',
        ],
    ],
];
