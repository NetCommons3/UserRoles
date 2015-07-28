<?php
/**
 * UserRoles Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserRolesAppController', 'UserRoles.Controller');

/**
 * UserRoles Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Controller
 */
class UserRolesController extends UserRolesAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'UserRoles.UserRole',
		'Roles.Role'
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'ControlPanel.ControlPanelLayout'
	);

/**
 * index
 *
 * @return void
 */
	public function index() {
		$roles = $this->UserRole->getUserRoles();
		$this->set('roles', $roles);
	}

/**
 * view
 *
 * @return void
 */
	public function view() {
	}

/**
 * add
 *
 * @return void
 */
	public function add() {
		$this->view = 'edit';

	}

/**
 * edit
 *
 * @return void
 */
	public function edit($roleKey = null) {
		$Role = $this->Role;

		$options = array(
			'recursive' => -1,
			'fields' => array('key', 'name'),
			'conditions' => array(
				'type' => $Role::ROLE_TYPE_USER,
				'is_systemized' => true,
				'language_id' => Configure::read('Config.languageId')
			),
			'order' => array('id' => 'asc')
		);

		$roles = $this->Role->find('list', $options);
		unset($roles[$Role::ROLE_KEY_SYSTEM_ADMINISTRATOR]);

		$this->set('roles', $roles);
	}

/**
 * delete
 *
 * @return void
 */
	public function delete() {
	}
}
