<?php
/**
 * 権限の作成(ウィザード形式) Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserRolesAppController', 'UserRoles.Controller');
App::uses('UserAttributesRolesController', 'UserRoles.Controller');

/**
 * 権限の作成(ウィザード形式) Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Controller
 */
class UserRoleAddController extends UserRolesAppController {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		//一般設定
		'M17n.SwitchLanguage' => array(
			'fields' => array(
				'UserRole.name', 'UserRole.description'
			)
		),
		//個人情報設定で使用
		'UserAttributes.UserAttributeLayout',
	);

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'PluginManager.PluginsRole',
		'Roles.DefaultRolePermission',
		'UserRoles.UserAttributesRole',
		'UserRoles.UserRole',
		'UserRoles.UserRoleSetting',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Wizard' => array(
			'navibar' => array(
				parent::WIZARD_USER_ROLES => array(
					'url' => array(
						'controller' => 'user_role_add',
						'action' => 'basic',
					),
					'label' => array('user_roles', 'General setting'),
				),
				parent::WIZARD_USER_ROLES_PLUGINS => array(
					'url' => array(
						'controller' => 'user_role_add',
						'action' => 'user_roles_plugins',
					),
					'label' => array('user_roles', 'Select site-manager plugin to use'),
				),
				parent::WIZARD_USER_ATTRIBUTES_ROLES => array(
					'url' => array(
						'controller' => 'user_role_add',
						'action' => 'user_attributes_roles',
					),
					'label' => array('user_roles', 'Information Policy'),
				),
			),
			'cancelUrl' => array('controller' => 'user_roles', 'action' => 'index'),
		),
		//個人情報設定で使用
		'UserAttributes.UserAttributeLayout',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		if ($this->params['action'] === 'basic') {
			return;
		}

		if (! $this->Session->read('UserRoleAdd')) {
			return $this->redirect('/user_roles/user_role_add/basic');
		}

		if ($this->params['action'] === 'user_attributes_roles' &&
					! $this->Session->read('UserRoleAdd.PluginsRole')) {

			$navibar = $this->helpers['NetCommons.Wizard']['navibar'];
			$navibar[parent::WIZARD_USER_ROLES_PLUGINS]['url'] = null;
			$this->helpers['NetCommons.Wizard']['navibar'] = $navibar;
		}
	}

/**
 * 一般設定
 *
 * @return void
 */
	public function basic() {
		if ($this->request->is('post')) {
			//不要パラメータ除去
			unset($this->request->data['save'], $this->request->data['active_lang_id']);

			//他言語が入力されていない場合、表示されている言語データをセット
			$this->SwitchLanguage->setM17nRequestValue();

			//登録処理
			if ($this->UserRole->validateUserRole($this->request->data)) {
				$this->Session->write('UserRoleAdd', $this->request->data);
				$count = $this->PluginsRole->find('count', array(
					'recursive' => -1,
					'conditions' => array(
						'role_key' => $this->request->data['UserRoleSetting']['origin_role_key']
					)
				));

				if ($count > 0) {
					return $this->redirect('/user_roles/user_role_add/user_roles_plugins');
				} else {
					$this->Session->write(
						'UserRoleAdd.UserRoleSetting.is_usable_user_manager', false
					);
					return $this->redirect('/user_roles/user_role_add/user_attributes_roles');
				}
			}
			$this->NetCommons->handleValidationError($this->UserRole->validationErrors);

		} else {
			//初期値セット
			$pattern = preg_quote('/user_roles/user_role_add/', '/') .
						'(user_attributes_roles|user_roles_plugins)';
			if (preg_match('/' . $pattern . '/', $this->referer())) {
				$this->request->data = $this->Session->read('UserRoleAdd');
			} else {
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
						'use_private_room' => true
					))
				);
			}

			$defaultPermission = $this->DefaultRolePermission->create(array(
				'id' => null,
				'type' => DefaultRolePermission::TYPE_USER_ROLE,
				'permission' => 'group_creatable',
				'value' => true,
				'fixed' => false,
			))['DefaultRolePermission'];
			$this->request->data['DefaultRolePermission']['group_creatable'] = $defaultPermission;
		}

		$result = $this->UserRole->find('all', array(
			'recursive' => -1,
			'fields' => array('key', 'name', 'description'),
			'conditions' => array(
				'language_id' => Current::read('Language.id')
			),
			'order' => array('id' => 'asc')
		));

		$userRoles = Hash::combine($result, '{n}.UserRole.key', '{n}.UserRole.name');
		unset($userRoles[UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR]);
		$this->set('userRoles', $userRoles);

		$userRoles = Hash::combine($result, '{n}.UserRole.key', '{n}.UserRole.description');
		unset($userRoles[UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR]);
		$this->set('userRolesDescription', $userRoles);
	}

/**
 * サイト運営プラグインの選択
 *
 * @return void
 */
	public function user_roles_plugins() {
		$baseUserRole = $this->Session->read('UserRoleAdd');

		if ($this->request->is('post')) {
			//不要パラメータ除去
			unset($this->request->data['save']);

			$isUsableUserManager = Hash::extract(
				$this->request->data['PluginsRole'], '{n}.PluginsRole[plugin_key=user_manager]'
			);
			$isUsableUserManager = (bool)Hash::get($isUsableUserManager, '0', false);
			$this->Session->write(
				'UserRoleAdd.UserRoleSetting.is_usable_user_manager', $isUsableUserManager
			);

			$this->Session->write('UserRoleAdd.PluginsRole', $this->request->data['PluginsRole']);
			return $this->redirect('/user_roles/user_role_add/user_attributes_roles');

		} else {
			//PluginsRoleデータ取得
			$this->request->data['PluginsRole'] = $this->PluginsRole->getPlugins(
				Plugin::PLUGIN_TYPE_FOR_SITE_MANAGER,
				Hash::get($baseUserRole, 'UserRoleSetting.origin_role_key')
			);

			$pattern = preg_quote('/user_roles/user_role_add/', '/') . 'user_attributes_roles';
			if (preg_match('/' . $pattern . '/', $this->referer())) {
				$this->request->data = Hash::merge($this->request->data, $baseUserRole);
			} else {
				$this->request->data = Hash::merge($baseUserRole, $this->request->data);
			}
		}
	}

/**
 * 個人情報設定
 *
 * @return void
 */
	public function user_attributes_roles() {
		$baseUserRole = $this->Session->read('UserRoleAdd');

		$rolekey = $baseUserRole['UserRoleSetting']['origin_role_key'];
		$userAttributesRoles = $this->UserAttributesRole->getUserAttributesRole($rolekey);

		if ($this->request->is('post')) {
			//不要パラメータ除去
			unset($this->request->data['save']);

			//リクエストの整形
			if ($this->Session->read('UserRoleAdd.UserRoleSetting.is_usable_user_manager')) {
				$this->request->data['UserAttributesRole'] = $userAttributesRoles;
			} else {
				foreach ($userAttributesRoles as $id => $userAttributesRole) {
					$otherRole = Hash::get(
						$this->request->data,
						'UserAttributesRole.' . $id . '.UserAttributesRole.other_user_attribute_role',
						false
					);

					if ($otherRole === false) {
						$this->request->data['UserAttributesRole'][$id] = $userAttributesRole;
						continue;
					}

					$choices = array(
						UserAttributesRolesController::OTHER_READABLE,
						UserAttributesRolesController::OTHER_NOT_READABLE
					);
					if (! in_array($otherRole, $choices, true)) {
						return $this->throwBadRequest();
					}

					$userAttributesRole['UserAttributesRole']['other_readable'] = false;
					$userAttributesRole['UserAttributesRole']['other_editable'] = false;
					if ($otherRole === UserAttributesRolesController::OTHER_READABLE) {
						$userAttributesRole['UserAttributesRole']['other_readable'] = true;
					}
					$this->request->data['UserAttributesRole'][$id] = $userAttributesRole;
				}
			}
			$baseUserRole['UserAttributesRole'] = $this->request->data['UserAttributesRole'];

			//登録処理
			if ($this->UserRole->saveUserRole($baseUserRole)) {
				//正常の場合
				$this->Session->delete('UserRoleAdd');
				$this->NetCommons->setFlashNotification(
					__d('net_commons', 'Successfully saved.'), array('class' => 'success')
				);
				return $this->redirect('/user_roles/user_roles/index');
			}
			$this->NetCommons->handleValidationError($this->UserRole->validationErrors);
		}
		//既存データ取得
		$this->request->data['UserAttributesRole'] = $userAttributesRoles;
		$this->request->data['UserAttribute'] = $this->viewVars['userAttributes'];
		$this->request->data = Hash::merge($baseUserRole, $this->request->data);
	}
}
