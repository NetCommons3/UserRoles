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
		'M17n.SwitchLanguage'
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		if (! in_array($this->params['action'], ['add', 'edit'], true)) {
			return;
		}
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

		if ($this->request->is('post')) {
			//不要パラメータ除去
			unset($this->request->data['save'], $this->request->data['active_lang_id']);

			//登録処理
			$userRoles = $this->UserRole->saveUserRole($this->request->data);
			if ($userRoles) {
				//正常の場合
				$this->NetCommons->setFlashNotification(
					__d('net_commons', 'Successfully saved.'), array('class' => 'success')
				);
				$userRoleKey = Hash::extract(
					$userRoles, '{n}.UserRole[language_id=' . Current::read('Language.id') . '].key'
				);
				return $this->redirect(
					'/user_roles/user_role_settings/edit/' . Hash::get($userRoleKey, '0') . '/'
				);
			}
			$this->NetCommons->handleValidationError($this->UserRole->validationErrors);

		} else {
			//初期値セット
			$this->request->data['UserRole'] = array();
			foreach (array_keys($this->viewVars['languages']) as $langId) {
				$index = count($this->request->data['UserRole']);

				$userRole = $this->UserRole->create(array(
					'id' => null,
					'language_id' => (string)$langId,
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
		if ($roleKey === UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR) {
			return $this->throwBadRequest();
		}
		//既存データ取得
		$userRole = $this->UserRole->find('all', array(
			'recursive' => -1,
			'conditions' => array('key' => $roleKey)
		));
		if (! $userRole) {
			return $this->throwBadRequest();
		}

		if ($this->request->is('put')) {
			//不要パラメータ除去
			unset($this->request->data['save'], $this->request->data['active_lang_id']);

			//登録処理
			$result = $this->UserRole->saveUserRole($this->request->data);
			if ($result) {
				//正常の場合
				$this->NetCommons->setFlashNotification(
					__d('net_commons', 'Successfully saved.'), array('class' => 'success')
				);

				$userRoleKey = Hash::extract(
					$result, '{n}.UserRole[language_id=' . Current::read('Language.id') . '].key'
				);
				return $this->redirect(
					'/user_roles/user_role_settings/edit/' . Hash::get($userRoleKey, '0') . '/'
				);
			}
			$this->NetCommons->handleValidationError($this->UserRole->validationErrors);

			$extract = Hash::extract(
				$userRole, '{n}.UserRole[language_id=' . Current::read('Language.id') . ']'
			);
			$this->request->data['UserRole'] = Hash::insert(
				$this->request->data['UserRole'], '{n}.is_system', Hash::get($extract, '0.is_system')
			);

		} else {
			$this->request->data['UserRole'] = Hash::extract($userRole, '{n}.UserRole');
		}

		$result = $this->UserRoleSetting->find('first', array(
			'recursive' => -1,
			'conditions' => array('role_key' => $roleKey),
		));
		$this->request->data = Hash::merge($this->request->data, $result);

		$this->set('roleKey', $roleKey);

		$this->set('subtitle', Hash::get($this->viewVars['userRoles'], $roleKey, ''));

		$this->set('isDeletable', $this->UserRole->verifyDeletable($roleKey));
	}

/**
 * delete
 *
 * @return void
 */
	public function delete() {
		if (! $this->request->is('delete')) {
			return $this->throwBadRequest();
		}
		if (! $this->UserRole->deleteUserRole($this->data['UserRole'][0])) {
			$message = Hash::get($this->UserRole->validationErrors, 'key.0');
			$this->NetCommons->handleValidationError($this->UserRole->validationErrors, $message);
			return $this->redirect('/user_roles/user_roles/edit/' . $this->data['UserRole'][0]['key']);
		} else {
			return $this->redirect('/user_roles/user_roles/index/');
		}
	}
}
