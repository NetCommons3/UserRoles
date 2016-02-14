<?php
/**
 * View/Elements/UserAttributesRoles/render_edit_rowテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');
App::uses('UserAttributeLayoutFixture', 'UserAttributes.Test/Fixture');

/**
 * View/Elements/UserAttributesRoles/render_edit_rowテスト用Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\test_app\Plugin\UserRoles\Controller
 */
class TestViewElementsUserAttributesRolesRenderEditRowController extends AppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'UserAttributes.UserAttributeLayout',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'UserAttributes.UserAttribute',
		'UserAttributes.UserAttributeLayout',
	);

/**
 * render_edit_row
 *
 * @return void
 */
	public function render_edit_row() {
		$this->autoRender = true;

		$this->set('data', array(
			'layout' => array('UserAttributeLayout' => (new UserAttributeLayoutFixture())->records[0]),
		));
	}

}
