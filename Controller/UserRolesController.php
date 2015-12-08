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
//App::uses('UserRole', 'UserRoles.Model');

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
		'M17n.SwitchLanguage'
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		if (in_array($this->params['action'], ['add', 'edit'], true)) {
			$userRoles = $this->UserRole->find('list', array(
				'recursive' => -1,
				'fields' => array('key', 'name'),
				'conditions' => array(
					'type' => UserRole::ROLE_TYPE_USER,
					'language_id' => Current::read('Language.id')
				),
				'order' => array('id' => 'asc')
			));
			unset($userRoles[UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR]);

			$this->set('userRoles', $userRoles);
		}
	}

/**
 * index
 *
 * @return void
 */
	public function index() {
		$userRoles = $this->UserRole->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				$this->UserRole->alias . '.language_id' => Current::read('Language.id')
			)
		));
		$this->set('userRoles', $userRoles);
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
			unset($this->request->data['save'], $this->request->data['active_lang_id']);

			//登録処理
			$userRoles = $this->UserRole->saveUserRole($this->request->data, true);
			if ($userRoles) {
				//正常の場合
				$userRoleKey = Hash::extract($userRoles, '{n}.UserRole[language_id=' . Current::read('Language.id') . '].key');
				$this->redirect('/user_roles/user_role_settings/edit/' . Hash::get($userRoleKey, '0') . '/');
				return;
			}
			$this->NetCommons->handleValidationError($this->UserRole->validationErrors);

		} else {
			//初期値セット
			$this->request->data['UserRole'] = array();
			foreach (array_keys($this->viewVars['languages']) as $langId) {
				$index = count($this->request->data['UserRole']);

				$userRole = $this->UserRole->create(array(
					'id' => null,
					'language_id' => $langId,
					'type' => UserRole::ROLE_TYPE_USER,
				));
				$this->request->data['UserRole'][$index] = $userRole['UserRole'];
			}
			$this->request->data = Hash::merge($this->request->data,
				$this->UserRoleSetting->create(array(
					'id' => null,
					'origin_role_key' => UserRole::USER_ROLE_KEY_COMMON_USER,
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
			unset($this->request->data['save'], $this->request->data['active_lang_id']);

			//登録処理
			if ($this->UserRole->saveUserRole($this->request->data, false)) {
				//正常の場合
				$this->redirect('/user_roles/user_roles/index/');
				return;
			}
			$this->NetCommons->handleValidationError($this->UserRole->validationErrors);

		} else {
			//既存データ取得
			$userRole = $this->UserRole->find('all', array(
				'recursive' => -1,
				'conditions' => array('key' => $roleKey)
			));
			$this->request->data['UserRole'] = Hash::extract($userRole, '{n}.UserRole');

			$data = $this->UserRoleSetting->find('first', array(
				'recursive' => -1,
				'conditions' => array('role_key' => $roleKey),
			));
			$this->request->data = Hash::merge($this->request->data, $data);
		}
		$this->set('roleKey', $roleKey);

		$userRole = Hash::extract($this->request->data['UserRole'], '{n}[language_id=' . Current::read('Language.id') . ']');
		$this->set('subtitle', Hash::get($userRole, '0.name', ''));
		$this->set('isDeletable', $this->UserRole->verifyDeletable($roleKey));
	}

/**
 * delete
 *
 * @return void
 */
	public function delete() {
		if (! $this->request->isDelete() || ! $this->UserRole->verifyDeletable($this->data['UserRole'][0]['key'])) {
			$this->throwBadRequest();
			return;
		}
		if (! $this->UserRole->deleteUserRole($this->data['UserRole'][0])) {
			$this->NetCommons->handleValidationError($this->UserRole->validationErrors);
		}
		$this->redirect('/user_roles/user_roles/index/');
	}
}
