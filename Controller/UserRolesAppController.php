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
 * ウィザード定数(user_role_settings)
 *
 * @var string
 */
	const WIZARD_USER_ROLE_SETTINGS = 'user_role_settings';

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
			'type' => PermissionComponent::CHECK_TYEP_SYSTEM_PLUGIN,
			'allow' => array()
		),
		'Security',
		'UserRoles.UserRoleForm',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Wizard' => array(
			'navibar' => array(
				self::WIZARD_USER_ROLES => array(
					'url' => array(
						'controller' => 'user_roles',
						'action' => 'add',
					),
					'label' => array('user_roles', 'General setting'),
				),
				self::WIZARD_USER_ROLE_SETTINGS => array(
					'url' => array(
						'controller' => 'user_role_settings',
						'action' => 'edit',
					),
					'label' => array('user_roles', 'Details setting'),
				),
				self::WIZARD_USER_ATTRIBUTES_ROLES => array(
					'url' => array(
						'controller' => 'user_attributes_roles',
						'action' => 'edit',
					),
					'label' => array('user_roles', 'Information Policy'),
				),
			),
			'cancelUrl' => array('controller' => 'user_roles', 'action' => 'index'),
		),
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny('index', 'view');

		if ($this->params['action'] === 'edit') {
			$navibar = Hash::insert(
				$this->helpers['NetCommons.Wizard']['navibar'], '{s}.url.key', $this->params['pass'][0]
			);
			$navibar[self::WIZARD_USER_ROLES]['url']['action'] = $this->params['action'];
			$this->helpers['NetCommons.Wizard']['navibar'] = $navibar;
		}
	}
}
