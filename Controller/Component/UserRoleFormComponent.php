<?php
/**
 * UserRoleForm Component
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Component', 'Controller');

/**
 * UserRoleForm Component
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Controller\Component
 */
class UserRoleFormComponent extends Component {

/**
 * Called after the Controller::beforeFilter() and before the controller action
 *
 * @param Controller $controller Controller with components to startup
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::startup
 */
	public function startup(Controller $controller) {
		//RequestActionの場合、スキップする
		if (! empty($controller->request->params['requested'])) {
			return;
		}
		$controller->helpers[] = 'UserRoles.UserRoleForm';

		$this->Role = ClassRegistry::init('Roles.Role');
		$defaultRoles = $this->Role->find('list', array(
			'recursive' => -1,
			'fields' => array('key', 'name'),
			'conditions' => array(
				'is_system' => true,
				'language_id' => Current::read('Language.id'),
				'type' => Role::ROLE_TYPE_ROOM
			),
			'order' => array('id' => 'asc')
		));
		$controller->set('defaultRoleOptions', $defaultRoles);
	}

}
