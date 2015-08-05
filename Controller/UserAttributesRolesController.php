<?php
/**
 * UserAttributesRoles Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserRolesAppController', 'UserRoles.Controller');

/**
 * UserAttributesRoles Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Controller
 */
class UserAttributesRolesController extends UserRolesAppController {

/**
 * constant UserAttributesRoles
 */
	const
		OTHER_NOT_READABLE = 'other_not_readable',
		OTHER_READABLE = 'other_readable',
		OTHER_EDITABLE = 'other_editable';

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'PluginManager.PluginsRole',
		'UserRoles.UserAttributesRole',
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
		'UserAttributes.UserAttributeLayouts',
	);

/**
 * edit
 *
 * @param string $roleKey user_roles.key
 * @return void
 */
	public function edit($roleKey = null) {
		if ($this->request->isPost()) {
			$data = $this->data;

			//不要パラメータ除去
			unset($data['save']);
			$this->request->data = $data;

			//リクエストの整形
			foreach (array_keys($data['UserAttributesRole']) as $id) {
				$data['UserAttributesRole'][$id]['UserAttributesRole']['other_readable'] = false;
				$data['UserAttributesRole'][$id]['UserAttributesRole']['other_editable'] = false;

				if ($data['UserAttributesRole'][$id]['UserAttributesRole']['other_user_attribute_role'] === self::OTHER_EDITABLE) {
					$data['UserAttributesRole'][$id]['UserAttributesRole']['other_readable'] = true;
					$data['UserAttributesRole'][$id]['UserAttributesRole']['other_editable'] = true;
				} elseif ($data['UserAttributesRole'][$id]['UserAttributesRole']['other_user_attribute_role'] === self::OTHER_READABLE) {
					$data['UserAttributesRole'][$id]['UserAttributesRole']['other_readable'] = true;
				}
			}

			//登録処理
			$this->UserAttributesRole->saveUserAttributesRoles($data);
			if ($this->handleValidationError($this->UserAttributesRole->validationErrors)) {
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
			$this->request->data['UserRoleSetting']['is_usable_user_manager'] = (bool)$this->PluginsRole->find('count', array(
				'recursive' => -1,
				'conditions' => array(
					'role_key' => $roleKey,
					'plugin_key' => 'user_manager',
				)
			));
			$this->request->data['UserAttributesRole'] = $this->UserAttributesRole->getUserAttributesRole($roleKey);
			$this->request->data['UserAttribute'] = $this->viewVars['userAttributes'];
		}

		if ($plugin = Hash::extract($this->ControlPanelLayout->plugins, '{n}.Plugin[key=user_manager]')) {
			$this->set('userManagerPluginName', $plugin[0]['name']);
		} else {
			$this->set('userManagerPluginName',__d('user_roles', 'User manager'));
		}
		$this->set('roleKey', $roleKey);
		$this->set('subtitle', $this->request->data['UserRole']['name']);
	}

/**
 * user_manager
 *
 * @return void
 */
	public function user_manager() {
		if (! $this->request->isPut()) {
			$this->throwBadRequest();
			return;
		}

		$this->UserRoleSetting->saveUsableUserManager($this->data);
		$this->setFlashNotification(__d('net_commons', 'Successfully saved.'), array('class' => 'success'));

		$this->redirect('/user_roles/user_attributes_roles/edit/' . h($this->data['UserRoleSetting']['role_key']));
	}

}
