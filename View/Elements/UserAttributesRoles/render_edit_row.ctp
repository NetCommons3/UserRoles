<?php
/**
 * 会員権限の個人情報設定のElement
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

$row = $layout['UserAttributeLayout']['id'];
$col = $layout['UserAttributeLayout']['col'];
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<strong><?php echo sprintf(__d('user_attributes', '%s row'), $row); ?></strong>
	</div>

	<div class="panel-body">
		<div class="row">
			<?php echo $this->UserAttributeLayout->renderCol('UserAttributesRoles/render_edit_col', $layout); ?>
		</div>
	</div>
</div>
