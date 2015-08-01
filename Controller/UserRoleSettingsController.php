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
		'UserRoles.UserRole',
		'UserRoles.UserRoleSetting',
		'PluginManager.PluginsRole',
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
		if ($this->request->isPost() || $this->request->isPut()) {
			$data = $this->data;

			//不要パラメータ除去
			unset($data['save']);
			$this->request->data = $data;

			//登録処理
			$this->UserRoleSetting->saveUserRoleSetting($data);
			if ($this->handleValidationError($this->UserRoleSetting->validationErrors)) {
				//正常の場合
				$this->redirect('/user_roles/user_roles/index/');
				return;
			}

		} else {
			//既存データ取得
			$this->request->data = $this->UserRole->getUserRoles('first', array(
				'recursive' => 0,
				'conditions' => array(
					'key' => $roleKey,
					'language_id' => Configure::read('Config.languageId')
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

		if ($plugin = Hash::extract($this->ControlPanelLayout->plugins, '{n}.Plugin[key=rooms]')) {
			$this->set('roomsPluginName', $plugin[0]['name']);
		} else {
			$this->set('roomsPluginName',__d('user_roles', 'Room manager'));
		}
		$this->set('roleKey', $roleKey);
	}
}
