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
 * 「他人の会員情報を閲覧させない」定数
 *
 * @var const
 */
	const OTHER_NOT_READABLE = 'other_not_readable';

/**
 * 「他人の会員情報を閲覧させる」定数
 *
 * @var const
 */
	const OTHER_READABLE = 'other_readable';

/**
 * 「他人の会員情報を編集させる」定数
 *
 * @var const
 */
	const OTHER_EDITABLE = 'other_editable';

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'UserAttributes.UserAttributeLayout',
	);

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'PluginManager.Plugin',
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
		'UserAttributes.UserAttributeLayout',
	);

/**
 * edit
 *
 * @param string $roleKey user_roles.key
 * @return void
 */
	public function edit($roleKey = null) {
		if ($this->request->is('put')) {
			if (! Hash::get($this->request->data, 'UserAttributesRole')) {
				return $this->throwBadRequest();
			}

			//不要パラメータ除去
			unset($this->request->data['save']);

			//リクエストの整形
			$ids = array_keys($this->request->data['UserAttributesRole']);
			foreach ($ids as $id) {
				$otherRole = Hash::get($this->request->data,
						'UserAttributesRole.' . $id . '.UserAttributesRole.other_user_attribute_role', false);
				if ($otherRole === false) {
					$this->request->data = Hash::remove($this->request->data, 'UserAttributesRole.' . $id);
					continue;
				}

				if (! in_array($otherRole, [self::OTHER_READABLE, self::OTHER_NOT_READABLE], true)) {
					return $this->throwBadRequest();
				}

				$this->request->data['UserAttributesRole'][$id]['UserAttributesRole']['other_readable'] = false;
				$this->request->data['UserAttributesRole'][$id]['UserAttributesRole']['other_editable'] = false;

				if ($otherRole === self::OTHER_READABLE) {
					$this->request->data['UserAttributesRole'][$id]['UserAttributesRole']['other_readable'] = true;
				}
			}

			//登録処理
			if ($this->UserAttributesRole->saveUserAttributesRoles($this->request->data)) {
				//正常の場合
				$this->redirect('/user_roles/user_roles/index/');
			} else {
				$this->NetCommons->handleValidationError($this->UserAttributesRole->validationErrors);
				$this->redirect('/user_roles/user_attributes_roles/edit/' . h($roleKey));
			}

		} else {
			//既存データ取得
			$this->request->data = $this->UserRoleSetting->getUserRoleSetting(Plugin::PLUGIN_TYPE_FOR_SITE_MANAGER, $roleKey);
			$this->request->data['UserAttributesRole'] = $this->UserAttributesRole->getUserAttributesRole($roleKey);
			$this->request->data['UserAttribute'] = $this->viewVars['userAttributes'];
			$userRole = $this->UserRole->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					'key' => $roleKey,
					'language_id' => Current::read('Language.id')
				)
			));
			$this->request->data = Hash::merge($userRole, $this->request->data);

			$this->set('roleKey', $roleKey);
			$this->set('subtitle', $this->request->data['UserRole']['name']);
		}
	}

}
