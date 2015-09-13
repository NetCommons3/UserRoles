<?php
/**
 * UserRoleSettings Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserRolesAppController', 'UserRoles.Controller');

/**
 * UserRoleSettings Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Controller
 */
class UserRoleSettingsController extends UserRolesAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'PluginManager.PluginsRole',
		'UserRoles.UserRole',
		'UserRoles.UserRoleSetting',
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
 * edit
 *
 * @param string $roleKey user_roles.key
 * @return void
 */
	public function edit($roleKey = null) {
		if ($this->request->isPut()) {
			//不要パラメータ除去
			$data = $this->data;
			unset($data['save']);

			//登録処理
			if ($this->UserRoleSetting->saveUserRoleSetting($data)) {
				//正常の場合
				$this->redirect('/user_roles/user_roles/index/');
				return;
			}
			$this->NetCommons->handleValidationError($this->UserRoleSetting->validationErrors);
			$this->request->data = $data;

		} else {
			$this->request->data = $this->UserRoleSetting->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					'role_key' => $roleKey,
				)
			));
			$this->request->data['UserRoleSetting']['is_usable_room_manager'] = (bool)$this->PluginsRole->find('count', array(
				'recursive' => -1,
				'conditions' => array(
					'role_key' => $roleKey,
					'plugin_key' => 'rooms',
				)
			));
		}

		//既存データ取得
		$userRole = $this->UserRole->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'type' => UserRole::ROLE_TYPE_USER,
				'key' => $roleKey,
				'language_id' => Configure::read('Config.languageId')
			)
		));
		$this->request->data = Hash::merge($userRole, $this->request->data);

		if ($plugin = Hash::extract($this->ControlPanelLayout->plugins, '{n}.Plugin[key=rooms]')) {
			$this->set('roomsPluginName', $plugin[0]['name']);
		} else {
			$this->set('roomsPluginName', __d('user_roles', 'Room manager'));
		}
		$this->set('roleKey', $roleKey);
		$this->set('subtitle', $this->request->data['UserRole']['name']);
	}
}
