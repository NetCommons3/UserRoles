<?php
/**
 * UserRolesApp Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * UserRolesApp Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Controller
 */
class UserRolesAppController extends AppController {

/**
 * ウィザード定数(user_roles)
 *
 * @var string
 */
	const WIZARD_USER_ROLES = 'user_roles';

/**
 * ウィザード定数(user_roles_plugins)
 *
 * @var string
 */
	const WIZARD_USER_ROLES_PLUGINS = 'user_roles_plugins';

/**
 * ウィザード定数(user_attributes_roles)
 *
 * @var string
 */
	const WIZARD_USER_ATTRIBUTES_ROLES = 'user_attributes_roles';

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'ControlPanel.ControlPanelLayout',
		'NetCommons.Permission' => array(
			'type' => PermissionComponent::CHECK_TYPE_SYSTEM_PLUGIN,
			'allow' => array()
		),
		'Security',
		'UserRoles.UserRoleForm',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny('index', 'view');
	}
}
