<?php
/**
 * UserRoleSetting Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserRoleSetting', 'UserRoles.Model');

/**
 * Summary for UserRoleSetting Test Case
 */
class UserRoleSettingTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_roles.user_role_setting',
		'plugin.user_roles.user',
		'plugin.user_roles.role',
		'plugin.user_roles.language',
		'plugin.user_roles.plugin',
		'plugin.user_roles.plugins_role',
		'plugin.user_roles.room',
		'plugin.user_roles.roles_room'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UserRoleSetting = ClassRegistry::init('UserRoles.UserRoleSetting');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UserRoleSetting);

		parent::tearDown();
	}

}
