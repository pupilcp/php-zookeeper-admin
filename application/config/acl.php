<?php

return [
	[
		'title' => '节点管理',
		'url' => '/node/index',
		'action' => [
			[
				'title' => '查看',
				'acl' => 'node_index',
			],
			[
				'title' => '添加',
				'acl' => 'node_createnode',
			],
			[
				'title' => '编辑',
				'acl' => 'node_updatenode',
			],
			[
				'title' => '删除',
				'acl' => 'node_deletenode',
			],
		],
	],
	[
		'title' => '用户管理',
		'url' => '/user/index',
		'action' => [
			[
				'title' => '查看',
				'acl' => 'user_index',
			],
			[
				'title' => '添加',
				'acl' => 'user_create',
			],
			[
				'title' => '编辑',
				'acl' => 'user_update',
			],
			[
				'title' => '删除',
				'acl' => 'user_delete',
			],
			[
				'title' => '启用',
				'acl' => 'user_active',
			],
			[
				'title' => '禁用',
				'acl' => 'user_forbid',
			],
		],
	],
	[
		'title' => '角色管理',
		'url' => '/role/index',
		'action' => [
			[
				'title' => '查看',
				'acl' => 'role_index',
			],
			[
				'title' => '添加',
				'acl' => 'role_create',
			],
			[
				'title' => '编辑',
				'acl' => 'role_update',
			],
			[
				'title' => '删除',
				'acl' => 'role_delete',
			],
			[
				'title' => '启用',
				'acl' => 'role_active',
			],
			[
				'title' => '禁用',
				'acl' => 'role_forbid',
			],
		],
	],
	[
		'title' => '配置管理',
		'url' => '/config/index',
		'action' => [
			[
				'title' => '查看',
				'acl' => 'config_index',
			],
			[
				'title' => '添加',
				'acl' => 'config_create',
			],
			[
				'title' => '编辑',
				'acl' => 'config_update',
			],
		],
	],
];