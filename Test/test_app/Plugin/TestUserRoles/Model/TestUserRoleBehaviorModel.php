<?php
/**
 * UserRoleBehaviorテスト用Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppModel', 'Model');

/**
 * UserRoleBehaviorテスト用Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\test_app\Plugin\UserRoles\Model
 */
class TestUserRoleBehaviorModel extends AppModel {

/**
 * Name of the model.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#name
 */
	public $name = 'UserRole';

/**
 * Alias name for model.
 *
 * @var string
 */
	public $alias = 'UserRole';

/**
 * テーブル名
 *
 * @var mixed
 */
	public $useTable = 'roles';

/**
 * 使用ビヘイビア
 *
 * @var array
 */
	public $actsAs = array(
		'UserRoles.UserRole'
	);

}
