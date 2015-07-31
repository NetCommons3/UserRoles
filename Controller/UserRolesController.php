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
		'UserRoles.UserRoleSetting',
		'UserRoles.UserAttributesRole',
		//'Roles.Role'
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'ControlPanel.ControlPanelLayout',
		'M17n.SwitchLanguage'
	);

/**
 * index
 *
 * @return void
 */
	public function index() {
		$userRoles = $this->UserRole->getUserRoles('all', array(
			'conditions' => array('language_id' => Configure::read('Config.languageId'))
		));
		$this->set('userRoles', $userRoles);
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
		$this->__prepare();

		if ($this->request->isPost()) {
			$data = $this->data;

			//不要パラメータ除去
			unset($data['save'], $data['active_lang_id']);
			$this->request->data = $data;

			//UserRoleSettingデフォルトのデータ取得
			$userRoleSetting = $this->UserRoleSetting->find('first', array(
				'recursive' => -1,
				'conditions' => array('role_key' => $data[0]['UserRoleSetting']['default_role_key'])
			));
			foreach (['id', 'created', 'created_user', 'modified', 'modified_user'] as $field) {
				$userRoleSetting = Hash::remove($userRoleSetting, 'UserRoleSetting.' . $field);
			}
			//UserAttributesRoleデフォルトのデータ取得
			$userAttributesRole['UserAttributesRole'] = $this->UserAttributesRole->find('all', array(
				'recursive' => -1,
				'conditions' => array('role_key' => $data[0]['UserRoleSetting']['default_role_key'])
			));
			foreach (['id', 'created', 'created_user', 'modified', 'modified_user'] as $field) {
				$userAttributesRole = Hash::remove($userAttributesRole, 'UserAttributesRole.{n}.UserAttributesRole.' . $field);
			}

			$data[0] = Hash::merge($data[0], $userRoleSetting, $userAttributesRole);
			//登録処理
			$userRoles = $this->UserRole->saveUserRole($data);
			if ($this->handleValidationError($this->UserRole->validationErrors)) {
				//正常の場合
				$userRole = Hash::extract($userRoles, '{n}.UserRole[language_id=' . Configure::read('Config.languageId') . ']');
				if (! $userRole) {
					$userRole[0] = Hash::extract($userRoles, '0.UserRole');
				}
				$this->redirect('/user_roles/user_role_settings/edit/' . $userRole[0]['key'] . '/');
				return;
			}

		} else {
			//初期値セット
			$UserRole = $this->UserRole;

			foreach (array_keys($this->viewVars['languages']) as $langId) {
				$index = count($this->request->data);

				$this->request->data[$index] = Hash::merge(
					$this->UserRole->create(array(
						'id' => null,
						'language_id' => $langId,
						'key' => '',
						'name' => '',
						'type' => $UserRole::ROLE_TYPE_USER,
					)),
					$this->UserRoleSetting->create(array(
						'id' => null,
						'role_key' => '',
						'default_role_key' => $UserRole::USER_ROLE_KEY_COMMON_USER,
					))
				);
			}
		}
	}

/**
 * edit
 *
 * @param string $roleKey user_roles.key
 * @return void
 */
	public function edit($roleKey = null) {
		$this->__prepare();

		if ($this->request->isPost()) {
			$data = $this->data;

			//不要パラメータ除去
			unset($data['save'], $data['active_lang_code']);

			//登録処理
			$this->UserRole->saveUserRole($data);
			if ($this->handleValidationError($this->UserRole->validationErrors)) {
				//正常の場合
				$this->redirect('/user_roles/user_roles/index/');
				return;
			}

		} else {
			//既存データ取得
			$this->request->data = $this->UserRole->getUserRoles('all', array(
				'recursive' => 0,
				'conditions' => array('key' => $roleKey)
			));

			$this->set('roleKey', $roleKey);
			$this->set('isSystemized', $this->request->data[0]['UserRole']['is_systemized']);
		}
	}

/**
 * delete
 *
 * @return void
 */
	public function delete() {
		if (! $this->request->isDelete()) {
			$this->throwBadRequest();
			return;
		}

		$this->UserRole->deleteUserRole($this->data[0]);
		$this->redirect('/user_roles/user_roles/index/');
	}

/**
 * prepare
 *
 * @return void
 */
	private function __prepare() {
		////ベース権限の取得
		//$Role = $this->UserRole;
		//
		//$userRoles = $this->UserRole->getUserRoles('list', array(
		//	'fields' => array('key', 'name'),
		//	'conditions' => array(
		//		'is_systemized' => true,
		//		'language_id' => Configure::read('Config.languageId')
		//	),
		//	'order' => array('id' => 'asc')
		//));
		//unset($userRoles[$Role::ROLE_KEY_SYSTEM_ADMINISTRATOR]);
		//
		//$this->set('baseRoles', $userRoles);
	}

}
