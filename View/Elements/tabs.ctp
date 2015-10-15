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

if (! isset($roleKey)) {
	$roleKey = '';
}
?>

<ul class="nav nav-tabs" role="tablist">
	<li class="<?php echo ($this->params['controller'] === 'user_roles' ? 'active' : ''); ?>">
		<a href="<?php echo $this->NetCommonsHtml->url(array('controller' => 'user_roles', 'action' => $this->params['action'], h($roleKey))); ?>">
			<?php echo __d('user_roles', 'General setting'); ?>
		</a>
	</li>
	<li class="<?php echo ($this->params['controller'] === 'user_role_settings' ? 'active' : $disabled); ?>">
		<a href="<?php echo ($this->params['action'] === 'edit' ?
								$this->NetCommonsHtml->url(array('controller' => 'user_role_settings', 'action' => 'edit', h($roleKey))) : ''); ?>">
			<?php echo __d('user_roles', 'Details setting'); ?>
		</a>
	</li>
	<li class="<?php echo ($this->params['controller'] === 'user_attributes_roles' ? 'active' : $disabled); ?>">
		<a href="<?php echo ($this->params['action'] === 'edit' ?
								$this->NetCommonsHtml->url(array('controller' => 'user_attributes_roles', 'action' => 'edit', h($roleKey))) : ''); ?>">
			<?php echo __d('user_roles', 'Information Policy'); ?>
		</a>
	</li>
</ul>

<br>
