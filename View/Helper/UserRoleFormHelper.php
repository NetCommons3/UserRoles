<?php
/**
 * EdumapHelper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FormHelper', 'View/Helper');

/**
 * DataTypesHelper
 *
 * @package NetCommons\UserAttributes\View\Helper
 */
class UserRoleFormHelper extends FormHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array('Form');

/**
 * Default Constructor
 *
 * @param View $View The View this helper is being attached to.
 * @param array $settings Configuration settings for the helper.
 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		$this->UserRole = ClassRegistry::init('UserRoles.UserRole');
	}

/**
 * Outputs base roles list
 *
 * @param string $fieldName Name attribute of the SELECT
 * @param array $attributes The HTML attributes of the select element.
 * @return string Formatted SELECT element
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function selectBaseRoles($fieldName, $attributes = array()) {
		$baseRoles = $this->UserRole->getUserRoles('list', array(
			'fields' => array('key', 'name'),
			'conditions' => array(
				'is_systemized' => true,
				'language_id' => Configure::read('Config.languageId')
			),
			'order' => array('id' => 'asc')
		));
		unset($baseRoles[UserRole::ROLE_KEY_SYSTEM_ADMINISTRATOR]);

		$html = '<div class="form-group">';

		$options = Hash::merge(array(
			'type' => 'select',
			'class' => 'form-control',
			'options' => $baseRoles
		), $attributes);
		$html .= $this->Form->input($fieldName, $options);

		$html .= '</div>';

		return $html;
	}
}
