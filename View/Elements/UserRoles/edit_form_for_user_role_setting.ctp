<?php
/**
 * UserAttribute edit form template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="form-group">
	<?php echo $this->UserRoleForm->selectBaseUserRoles('UserRoleSetting.default_role_key', array(
			'label' => __d('user_roles', 'Base roles'),
			'value' => $this->data['UserRoleSetting']['default_role_key'],
			'disabled' => ($this->params['action'] === 'edit'),
		)); ?>
</div>
