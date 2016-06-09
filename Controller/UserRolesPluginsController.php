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
		//既存データ取得
		$userRole = $this->UserRole->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'key' => $roleKey,
				'language_id' => Current::read('Language.id')
			)
		));
		if (! $userRole) {
			return $this->throwBadRequest();
		}

		if ($this->request->is('put')) {
			//不要パラメータ除去
			unset($this->request->data['save']);

			//登録処理
			if ($this->UserRoleSetting->saveUserRoleSetting($this->request->data)) {
				//正常の場合
				$this->NetCommons->setFlashNotification(
					__d('net_commons', 'Successfully saved.'), array('class' => 'success')
				);
				$this->redirect('/user_roles/user_attributes_roles/edit/' . h($roleKey));
			} else {
				$this->NetCommons->handleValidationError($this->UserRoleSetting->validationErrors);
				$this->redirect('/user_roles/user_role_settings/edit/' . h($roleKey));
			}

		} else {
			$this->request->data = $this->UserRoleSetting->getUserRoleSetting(
				Plugin::PLUGIN_TYPE_FOR_SITE_MANAGER, $roleKey
			);
			$this->request->data = Hash::merge($userRole, $this->request->data);
			$this->set('roleKey', $roleKey);
			$this->set('subtitle', $this->request->data['UserRole']['name']);
		}
	}
}
