<?php
/**
 * View/Elements/UserAttributesRoles/render_edit_colテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');
App::uses('UserAttributeLayoutFixture', 'UserAttributes.Test/Fixture');
App::uses('UserAttributeSetting4editFixture', 'UserAttributes.Test/Fixture');
App::uses('UserAttribute4editFixture', 'UserAttributes.Test/Fixture');
App::uses('UserAttributesRolesController', 'UserRoles.Controller');

/**
 * View/Elements/UserAttributesRoles/render_edit_colテスト用Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\test_app\Plugin\UserRoles\Controller
 */
class TestViewElementsUserAttributesRolesRenderEditColController extends AppController {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'UserAttributes.UserAttributeLayout',
	);

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'UserRoles.UserAttributesRole',
		'UserRoles.UserRoleSetting'
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'UserAttributes.UserAttribute',
		'UserAttributes.UserAttributeLayout',
		'UserRoles.UserRoleForm',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		//テストデータ生成
		Current::$current['User']['role_key'] = UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR;
	}

/**
 * render_edit_col
 *
 * @return void
 */
	public function render_edit_col() {
		$this->autoRender = true;
		$this->view = 'render_edit_col';

		//テストデータ生成
		$this->request->data = $this->UserRoleSetting->getUserRoleSetting('2', 'common_user');
		$this->request->data['UserAttributesRole'] = $this->UserAttributesRole->getUserAttributesRole('common_user');

		$this->set('data', array(
			'layout' => array('UserAttributeLayout' => (new UserAttributeLayoutFixture())->records[0]),
			'userAttribute' => array(
				'UserAttributeSetting' => (new UserAttributeSetting4editFixture())->records[0],
				'UserAttribute' => (new UserAttribute4editFixture())->records[1],
			),
		));

		Current::$current['User']['role_key'] = null;
	}

}
