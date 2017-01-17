<?php
/**
 * UserRolesNetCommonsControllerTestCase
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

//@codeCoverageIgnoreStart;
App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');
//@codeCoverageIgnoreEnd;

/**
 * UserRolesNetCommonsControllerTestCase
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\View\Elements\UserAttributesRoles\RenderEditCol
 */
class UserRolesNetCommonsHelperTestCase extends NetCommonsHelperTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	private $__fixtures = array(
		'plugin.user_attributes.user_attribute4edit',
		'plugin.user_attributes.user_attribute_choice4edit',
		'plugin.user_attributes.user_attribute_layout',
		'plugin.user_attributes.user_attribute_setting4edit',
		'plugin.user_roles.plugin4test',
		'plugin.user_roles.plugins_role4test',
		'plugin.user_roles.user_attributes_role4edit',
		'plugin.user_roles.user_role4test',
		'plugin.user_roles.user_role_setting',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'user_roles';

/**
 * Fixtures load
 *
 * @param string $name The name parameter on PHPUnit_Framework_TestCase::__construct()
 * @param array  $data The data parameter on PHPUnit_Framework_TestCase::__construct()
 * @param string $dataName The dataName parameter on PHPUnit_Framework_TestCase::__construct()
 * @return void
 */
	public function __construct($name = null, array $data = array(), $dataName = '') {
		if (! isset($this->fixtures)) {
			$this->fixtures = array();
		}
		$this->fixtures = array_merge($this->__fixtures, $this->fixtures);
		parent::__construct($name, $data, $dataName);
	}

}
