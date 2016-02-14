<?php
/**
 * View/Elements/UserRoles/edit_formテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/UserRoles/edit_formテスト用Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\test_app\Plugin\UserRoles\Controller
 */
class TestViewElementsUserRolesEditFormController extends AppController {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'M17n.SwitchLanguage',
		'UserRoles.UserRoleForm',
	);

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'UserRoles.UserRole',
	);

/**
 * beforeRender
 *
 * @return void
 */
	public function beforeRender() {
		parent::beforeFilter();
		$this->Auth->allow('edit_form');

		$userRoles = $this->UserRole->find('list', array(
			'recursive' => -1,
			'fields' => array('key', 'name'),
			'conditions' => array(
				'language_id' => Current::read('Language.id')
			),
			'order' => array('id' => 'asc')
		));
		unset($userRoles[UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR]);

		$this->set('userRoles', $userRoles);
	}

/**
 * editアクションのedit_formテスト
 *
 * @return void
 */
	public function edit_form_by_edit() {
		$this->autoRender = true;
		$this->view = 'edit_form';

		$this->request->data = array(
			'UserRole' => array(
				array(
					'id' => '8', 'language_id' => '1', 'key' => 'test_user', 'type' => '1', 'name' => 'Test user',
				),
				array(
					'id' => '7', 'language_id' => '2', 'key' => 'test_user', 'type' => '1', 'name' => 'Test user',
				),
			),
			'UserRoleSetting' => array(
				'origin_role_key' => UserRole::USER_ROLE_KEY_COMMON_USER,
			),
		);
	}

/**
 * addアクションのedit_formテスト
 *
 * @return void
 */
	public function edit_form_by_add() {
		$this->autoRender = true;
		$this->view = 'edit_form';

		$this->request->data = array(
			'UserRole' => array(
				0 => array(
					'id' => null, 'language_id' => '1', 'key' => null, 'type' => '1', 'name' => '',
				),
				1 => array(
					'id' => null, 'language_id' => '2', 'key' => null, 'type' => '1', 'name' => '',
				),
				2 => array(
					'id' => null, 'language_id' => '3', 'key' => null, 'type' => '1', 'name' => '',
				)
			),
			'UserRoleSetting' => array(
				'origin_role_key' => UserRole::USER_ROLE_KEY_COMMON_USER,
			),
		);
	}

}
