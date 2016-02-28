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

App::uses('UserFixture', 'Users.Test/Fixture');

/**
 * UserRoleFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Fixture
 */
class User4testFixture extends UserFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'User';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'users';

/**
 * Records
 *
 * @var array
 */
	protected $_records = array(
		array(
			'id' => 8,
			'username' => 'test_user_2',
			'password' => 'test_user_2',
			'key' => 'test_user',
			'handlename' => 'Test User',
			'role_key' => 'test_user_2',
			'status' => 1,
			'is_status_public' => 1,
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		$this->records = Hash::merge($this->records, $this->_records);
		parent::init();
	}

}
