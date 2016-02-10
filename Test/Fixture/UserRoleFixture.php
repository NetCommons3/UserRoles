<?php
/**
 * UserRoleFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('RoleFixture', 'Roles.Test/Fixture');

/**
 * UserRoleFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Fixture
 */
class UserRoleFixture extends RoleFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'Role';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'roles';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		//会員の権限
		array(
			'language_id' => 2, 'key' => 'system_administrator', 'type' => 1, 'name' => 'System administrator', 'is_system' => 1,
		),
		array(
			'language_id' => 2,	'key' => 'administrator', 'type' => 1, 'name' => 'Site administrator', 'is_system' => 1,
		),
		array(
			'language_id' => 2, 'key' => 'common_user', 'type' => 1, 'name' => 'Common user', 'is_system' => 1,
		),
		array(
			'language_id' => 2, 'key' => 'test_user', 'type' => 1, 'name' => 'Test user', 'is_system' => 0,
		),
	);

}
