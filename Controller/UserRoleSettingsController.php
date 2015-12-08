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
		'PluginManager.Plugin',
		'UserRoles.UserRole',
		'UserRoles.UserRoleSetting',
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
			unset($this->request->data['save']);

			//登録処理
			if ($this->UserRoleSetting->saveUserRoleSetting($this->request->data)) {
				//正常の場合
				$this->redirect('/user_roles/user_attributes_roles/edit/' . h($roleKey));
				return;
			}
			$this->NetCommons->handleValidationError($this->UserRoleSetting->validationErrors);

		} else {
			$this->request->data = $this->UserRoleSetting->getUserRoleSetting($roleKey);
		}

		//既存データ取得
		$userRole = $this->UserRole->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'key' => $roleKey,
				'language_id' => Current::read('Language.id')
			)
		));
		$this->request->data = Hash::merge($userRole, $this->request->data);

		//プラグイン名取得
		$plugin = $this->Plugin->getPlugins(Plugin::PLUGIN_TYPE_FOR_CONTROL_PANEL, 'rooms');
		$this->set('roomsPluginName', Hash::get($plugin, '0.Plugin.name', __d('user_roles', 'Room manager')));

		$this->set('roleKey', $roleKey);
		$this->set('subtitle', $this->request->data['UserRole']['name']);
	}
}
