<?php
/**
 * View/Elements/tabsテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/tabsテスト用Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\test_app\Plugin\UserRoles\Controller
 */
class TestViewElementsTabsController extends AppController {

/**
 * beforeRender
 *
 * @return void
 */
	public function beforeRender() {
		parent::beforeFilter();
		$this->Auth->allow('tabs');
	}

/**
 * tabs
 *
 * @return void
 */
	public function add() {
		$this->autoRender = true;
		$this->view = 'tabs';
		$this->request->params['controller'] = 'user_roles';
	}

/**
 * tabs
 *
 * @return void
 */
	public function edit($controller) {
		$this->autoRender = true;
		$this->view = 'tabs';

		$this->request->params['controller'] = $controller;
		$this->set('roleKey', 'test_user');
	}

}
