<?php
/**
 * View/Elements/subtitleテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/subtitleテスト用Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\test_app\Plugin\UserRoles\Controller
 */
class TestViewElementsSubtitleController extends AppController {

/**
 * beforeRender
 *
 * @return void
 */
	public function beforeRender() {
		parent::beforeFilter();
		$this->Auth->allow('subtitle');
	}

/**
 * subtitle
 *
 * @return void
 */
	public function subtitle() {
		$this->autoRender = true;
		$this->set('subtitle', 'TestSubtitle');
	}

/**
 * subtitleなしテスト
 *
 * @return void
 */
	public function no_subtitle() {
		$this->autoRender = true;
		$this->view = 'subtitle';
	}

}
