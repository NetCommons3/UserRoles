<?php
/**
 * UserRoleSettingFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * UserRoleSettingFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Model
 */
class UserRoleSettingFixture extends CakeTestFixture {

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array('id' => '1', 'role_key' => 'system_administrator', 'origin_role_key' => 'system_administrator', 'use_private_room' => '1', ),
		array('id' => '2', 'role_key' => 'administrator', 'origin_role_key' => 'administrator', 'use_private_room' => '1', ),
		array('id' => '3', 'role_key' => 'common_user', 'origin_role_key' => 'common_user', 'use_private_room' => '1', ),
		array('id' => '4', 'role_key' => 'test_user', 'origin_role_key' => 'common_user', 'use_private_room' => '1', ),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('UserRoles') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new UserRolesSchema())->tables['user_role_settings'];
		parent::init();
	}

}
