<?php
/**
 * UserAttribute index row template
 *   - $row: UserAttributeLayout.row
 *   - $layout: UserAttributeLayout
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<strong><?php echo sprintf(__d('user_attributes', '%s row'), $row); ?></strong>
	</div>

	<div class="panel-body">
		<div class="row">
			<?php for($col = 1; $col <= $layout['UserAttributeLayout']['col']; $col++) : ?>
				<?php echo $this->element('UserAttributesRoles/render_edit_col', array('row' => $row, 'col' => $col, 'layout' => $layout)); ?>
			<?php endfor; ?>
		</div>
	</div>
</div>
