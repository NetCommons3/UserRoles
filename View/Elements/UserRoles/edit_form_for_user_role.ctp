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

<?php echo $this->Form->hidden('UserRole.' . $index . '.id'); ?>

<?php echo $this->Form->hidden('UserRole.' . $index . '.key'); ?>

<?php echo $this->Form->hidden('UserRole.' . $index . '.language_id'); ?>

<?php echo $this->Form->hidden('UserRole.' . $index . '.type'); ?>

<div class="form-group">
	<?php echo $this->Form->input('UserRole.' . $index . '.name', array(
			'type' => 'text',
			'label' => __d('user_roles', 'User role name') . $this->element('NetCommons.required'),
			'class' => 'form-control',
			'error' => false,
		)); ?>

	<div class="has-error">
		<?php echo $this->Form->error('UserRole.' . $index . '.name', null, array(
				'class' => 'help-block'
			)); ?>
	</div>
</div>
