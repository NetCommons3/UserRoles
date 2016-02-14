<?php
/**
 * beforeFind()とafterFind()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UserRoleFixture', 'UserRoles.Test/Fixture');

/**
 * beforeFind()とafterFind()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Model\UserRole
 */
class UserRoleFindTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_roles.user_attributes_role',
		'plugin.user_roles.user_role_setting',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'user_roles';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'UserRole';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'find';

/**
 * find('all')のテスト
 *
 * @return void
 */
	public function testFindAll() {
		//テスト実施
		$model = $this->_modelName;
		$result = $this->$model->find('all', array(
			'recursive' => -1,
			'fields' => array('id', 'key', 'type'),
			'order' => array('id' => 'asc'),
		));

		//チェック
		$expected = array(
			array('UserRole' => array('id' => '1', 'key' => 'system_administrator', 'type' => '1')),
			array('UserRole' => array('id' => '2', 'key' => 'administrator', 'type' => '1')),
			array('UserRole' => array('id' => '3', 'key' => 'common_user', 'type' => '1'))
		);
		$this->assertEquals($expected, $result);
	}

/**
 * find('list')のテスト
 *
 * @return void
 */
	public function testFindList() {
		//テスト実施
		$model = $this->_modelName;
		$result = $this->$model->find('list', array(
			'recursive' => -1,
			'fields' => array('id', 'key'),
			'order' => array('id' => 'asc'),
		));

		//チェック
		$expected = array(
			'1' => 'system_administrator',
			'2' => 'administrator',
			'3' => 'common_user',
		);
		$this->assertEquals($expected, $result);
	}

/**
 * find('first')のテスト
 *
 * @return void
 */
	public function testFindFirst() {
		//テスト実施
		$model = $this->_modelName;
		$result = $this->$model->find('first', array(
			'recursive' => -1,
			'fields' => array('id', 'key', 'type'),
			'order' => array('id' => 'asc'),
		));

		//チェック
		$expected = array(
			'UserRole' => array('id' => '1', 'key' => 'system_administrator', 'type' => '1'),
		);
		$this->assertEquals($expected, $result);
	}

}
