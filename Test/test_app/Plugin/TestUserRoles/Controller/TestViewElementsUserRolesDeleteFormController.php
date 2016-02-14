<?php
/**
 * View/Elements/UserRoles/delete_formテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/UserRoles/delete_formテスト用Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\test_app\Plugin\UserRoles\Controller
 */
class TestViewElementsUserRolesDeleteFormController extends AppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'UserRoles.UserRole',
	);

/**
 * delete_form
 *
 * @return void
 */
	public function delete_form() {
		$this->autoRender = true;
		$this->request->params['controller'] = 'user_roles';
		$this->request->params['action'] = 'delete';
		$this->view = 'delete_form';

		$this->request->data = array(
			'UserRole' => array(
				array('id' => '8', 'key' => 'test_user'),
			),
		);
		$this->set('isDeletable', true);
	}

/**
 * delete_form
 *
 * @return void
 */
	public function delete_form_is_not_deletable() {
		$this->delete_form();

		$this->UserRole->validationErrors['key'][] = __d('user_roles', 'Can not be deleted because it has this authority is used.');
		$this->set('isDeletable', false);
	}

}
