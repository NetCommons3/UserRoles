<?php
/**
 * UserRoleFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('RoleFixture', 'Roles.Test/Fixture');

/**
 * UserRoleFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Fixture
 */
class UserRoleFixture extends RoleFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'Role';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'roles';

}
