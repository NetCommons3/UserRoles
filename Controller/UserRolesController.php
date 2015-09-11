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
		$userRoles = $this->UserRole->find('all', array(
			'conditions' => array(
				'type' => UserRole::ROLE_TYPE_USER,
				'language_id' => Configure::read('Config.languageId')
			)
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

		if ($this->request->isPost()) {
			//不要パラメータ除去
			$data = $this->data;
			unset($data['save'], $data['active_lang_id']);

			//登録処理
			if ($userRoles = $this->UserRole->saveUserRole($data, true)) {
				//正常の場合
				$userRole = Hash::extract($userRoles, '{n}.UserRole[language_id=' . Configure::read('Config.languageId') . ']');
				if (! $userRole) {
					$userRole[0] = Hash::extract($userRoles, '0.UserRole');
				}
				$this->redirect('/user_roles/user_role_settings/edit/' . $userRole[0]['key'] . '/');
				return;
			}
			$this->NetCommons->handleValidationError($this->UserRole->validationErrors);
			$this->request->data = $data;

		} else {
			//初期値セット
			$UserRole = $this->UserRole;
			$this->request->data['UserRole'] = array();
			foreach (array_keys($this->viewVars['languages']) as $langId) {
				$index = count($this->request->data['UserRole']);

				$userRole = $this->UserRole->create(array(
					'id' => null,
					'language_id' => $langId,
					'key' => '',
					'name' => '',
					'type' => $UserRole::ROLE_TYPE_USER,
				));
				$this->request->data['UserRole'][$index] = $userRole['UserRole'];
			}
			$this->request->data = Hash::merge($this->request->data,
				$this->UserRoleSetting->create(array(
					'id' => null,
					'role_key' => '',
					'origin_role_key' => $UserRole::USER_ROLE_KEY_COMMON_USER,
				))
			);
		}
	}

/**
 * edit
 *
 * @param string $roleKey user_roles.key
 * @return void
 */
	public function edit($roleKey = null) {
		if ($this->request->isPost()) {
			//不要パラメータ除去
			$data = $this->data;
			unset($data['save'], $data['active_lang_id']);

			//登録処理
			if ($this->UserRole->saveUserRole($data, false)) {
				//正常の場合
				$this->redirect('/user_roles/user_roles/index/');
				return;
			}
			$this->NetCommons->handleValidationError($this->UserRole->validationErrors);
			$this->request->data = $data;

		} else {
			//既存データ取得
			$userRole = $this->UserRole->find('all', array(
				'recursive' => -1,
				'conditions' => array(
					'type' => UserRole::ROLE_TYPE_USER,
					'key' => $roleKey
				)
			));
			$this->request->data['UserRole'] = Hash::extract($userRole, '{n}.UserRole');

			$data = $this->UserRoleSetting->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					'role_key' => $roleKey
				),
			));
			$this->request->data = Hash::merge($this->request->data, $data);
		}
		$this->set('roleKey', $roleKey);

		$userRole = Hash::extract($this->request->data['UserRole'], '{n}[language_id=' . Configure::read('Config.languageId') . ']');
		$this->set('isSystemized', $userRole[0]['is_systemized']);
		$this->set('subtitle', $userRole[0]['name']);
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

		$this->UserRole->deleteUserRole($this->data['UserRole'][0]);
		$this->redirect('/user_roles/user_roles/index/');
	}
}
