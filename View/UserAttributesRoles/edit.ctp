<?php
/**
 * UserAttributesRoles edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->Html->css(
	array(
		'/user_roles/css/style.css'
	),
	array('plugin' => false)
);
?>

<?php echo $this->element('UserRoles.tabs'); ?>

<?php echo $this->Form->create(null, array('novalidate' => true)); ?>


<?php foreach ($userAttributeLayouts as $layout) : ?>
	<?php $row = $layout['UserAttributeLayout']['id']; ?>

	<?php echo $this->element('UserAttributesRoles/render_edit_row', array('row' => $row, 'layout' => $layout)); ?>
<?php endforeach; ?>

<div class="text-center">
	<?php echo $this->element('UserRoles.edit_btn'); ?>
</div>

<?php echo $this->Form->end();
