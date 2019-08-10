<?php

/*
 * This file is part of PHP CS Fixer.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return
    [
        [
			'name'   => '首页',
			'icon'   => '&#xe68e;',
			'url'    => '/manage/index',
			'hidden' => false,
			'list'   => [],
		],
        [
			'name'   => '节点管理',
			'icon'   => '&#xe857;',
			'url'    => '',
			'self'   => '/node/index',
			'hidden' => false,
			'list'   => [
				[
					'name' => '节点列表',
					'url'  => '/node/index',
				],
			],
		],
        [
			'name'   => '用户管理',
			'icon'   => '&#xe612;',
			'url'    => '',
			'self'   => '/user/index',
			'hidden' => false,
			'list'   => [
				[
					'name' => '用户列表',
					'url'  => '/user/index',
				],
				[
					'name' => '添加用户',
					'url'  => '/user/create',
				],
			],
		],
    ];
