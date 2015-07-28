<?php
/**
 * Setting tabs template
 *   - $active: Active tab key. Value is 'block_index or 'frame_settings' or 'role_permissions'.
 *   - $disabled: Disabled tab
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

if ($this->params['action'] === 'edit') {
	$disabled = '';
} else {
	$disabled = 'disabled';
}

if (isset($userRole['Role']['key'])) {
	$roleKey = $userRole['Role']['key'] . '/';
} else {
	$roleKey = '';
}
?>

<ul class="nav nav-tabs" role="tablist">
	<li class="<?php echo ($this->params['controller'] === 'user_roles' ? 'active' : ''); ?>">
		<a href="<?php echo $this->Html->url('/user_roles/user_roles/' . $this->params['action'] . '/' . $roleKey); ?>">
			<?php echo __d('user_roles', 'General setting'); ?>
		</a>
	</li>
	<li class="<?php echo ($this->params['controller'] === 'user_role_settings' ? 'active' : $disabled); ?>">
		<a href="<?php echo ($this->params['action'] === 'edit' ? $this->Html->url('/user_role_settings/user_role_settings/edit/' . $roleKey) : ''); ?>">
			<?php echo __d('user_roles', 'Details setting'); ?>
		</a>
	</li>
	<li class="<?php echo ($this->params['controller'] === 'user_attributes_roles' ? 'active' : $disabled); ?>">
		<a href="<?php echo ($this->params['action'] === 'edit' ?
										$this->Html->url('/user_attributes_roles/user_attributes_roles/edit/' . $roleKey) : ''); ?>">
			<?php echo __d('user_roles', 'Information Policy'); ?>
		</a>
	</li>
</ul>

<br>
