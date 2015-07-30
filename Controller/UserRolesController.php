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
		$userRoles = $this->UserRole->getUserRoles();
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
			unset($data['save'], $data['active_lang_code']);

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
				$this->request->data[$langId] = $this->UserRole->create(array(
					'id' => null,
					'language_id' => $langId,
					'key' => '',
					'name' => '',
					'type' => $UserRole::ROLE_TYPE_USER,
				));
			}
		}
	}

/**
 * edit
 *
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
			$userRoles = $this->UserRole->getUserRoles('all', $roleKey);

			//デフォルトのデータ取得
			$defaultRole = Hash::extract($userRoles, '{n}.UserRole[language_id=' . Configure::read('Config.languageId') . ']');
			if (! $defaultRole) {
				$defaultRole[0] = Hash::extract($userRoles, '0.UserRole');
			}

			$this->set('roleKey', $roleKey);
			$this->set('isSystemized', $defaultRole[0]['is_systemized']);

			//request->dataにセット
			foreach (array_keys($this->viewVars['languages']) as $langId) {
				$userRole = Hash::extract($userRoles, '{n}.UserRole[language_id=' . $langId . ']');
				if (! $userRole) {
					$this->request->data[$langId]['UserRole'] = $defaultRole[0];
					$this->request->data[$langId]['UserRole']['id'] = null;
					$this->request->data[$langId]['UserRole']['language_id'] = $langId;
				} else {
					$this->request->data[$langId]['UserRole'] = $userRole[0];
				}
			}
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

		$this->UserRole->deleteUserRole($this->data[Configure::read('Config.languageId')]);
		$this->redirect('/user_roles/user_roles/index/');
	}

/**
 * prepare
 *
 * @return void
 */
	private function __prepare() {
		//ベース権限の取得
		$Role = $this->UserRole;

		$options = array(
			'fields' => array('key', 'name'),
			'conditions' => array(
				'is_systemized' => true,
				'language_id' => Configure::read('Config.languageId')
			),
			'order' => array('id' => 'asc')
		);

		$userRoles = $this->UserRole->getUserRoles('list', null, $options);
		unset($userRoles[$Role::ROLE_KEY_SYSTEM_ADMINISTRATOR]);

		$this->set('baseRoles', $userRoles);
	}

}
