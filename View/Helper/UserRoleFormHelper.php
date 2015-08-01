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
App::uses('CakeNumber', 'Utility');

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
 * Option is_usable
 *
 * @var array
 */
	public $isUsableOptions;

/**
 * Option is_allow
 *
 * @var array
 */
	public $isPermittedOptions;

/**
 * Radio attributes
 *
 * @var array
 */
	public $radioAttributes = array(
		'legend' => false,
		'separator' => '<span class="radio-separator"> </span>',
	);

/**
 * Radio attributes
 *
 * @var array
 */
	public $optionsMaxSize = array(
		5242880,
		10485760,
		20971520,
		52428800,
		104857600,
		209715200,
		524288000,
		1073741824
	);

/**
 * Default Constructor
 *
 * @param View $View The View this helper is being attached to.
 * @param array $settings Configuration settings for the helper.
 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		$this->UserRole = ClassRegistry::init('UserRoles.UserRole');
		$this->Role = ClassRegistry::init('Roles.Role');

		$this->isUsableOptions = array(
			'1' => __d('user_roles', 'Use'),
			'0' => __d('user_roles', 'Not use'),
		);

		$this->isPermittedOptions = array(
			'1' => __d('user_roles', 'Permitted'),
			'0' => __d('user_roles', 'Not permitted'),
		);

	}

/**
 * Outputs base user roles select
 *
 * @param string $fieldName Name attribute of the SELECT
 * @param array $attributes The HTML attributes of the select element.
 * @return string Formatted SELECT element
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function selectBaseUserRoles($fieldName, $attributes = array()) {
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

/**
 * Outputs default room roles select
 *
 * @param string $fieldName Name attribute of the SELECT
 * @param array $attributes The HTML attributes of the select element.
 * @return string Formatted SELECT element
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function selectDefaultRoomRoles($fieldName, $attributes = array()) {
		$defaultRoles = $this->Role->find('list', array(
			'fields' => array('key', 'name'),
			'conditions' => array(
				'is_systemized' => true,
				'language_id' => Configure::read('Config.languageId'),
				'type' => Role::ROLE_TYPE_ROOM
			),
			'order' => array('id' => 'asc')
		));

		$html = '<div class="form-inline"><div class="form-group" style="margin-bottom: 15px;">';
		if (isset($attributes['label'])) {
			$html .= $this->Form->label($fieldName, $attributes['label']) . ' ';
			unset($attributes['label']);
		}
		$attributes = Hash::merge(array(
			'type' => 'select',
			'class' => 'form-control',
			'empty' => false
			//'options' => $defaultRoles,
			//'style' => 'margin-bottom: 20px;'
		), $attributes);
		$html .= $this->Form->select($fieldName, $defaultRoles, $attributes);
		$html .= '</div></div>';

		return $html;
	}

/**
 * Outputs radio
 *
 * @param string $fieldName Name attribute of the RADIO
 * @param array $options The HTML options of the radio element.
 * @param array $attributes The HTML attributes of the radio element.
 * @return string Formatted RADIO element
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function radioUserRole($fieldName, $options, $attributes = array()) {
		$attributes = Hash::merge($this->radioAttributes, $attributes);

		return $this->Form->radio($fieldName, $options, $attributes);
	}

/**
 * Outputs upload max size select
 *
 * @param string $fieldName Name attribute of the SELECT
 * @param array $attributes The HTML attributes of the select element.
 * @return string Formatted SELECT element
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function selectMaxSize($fieldName, $attributes = array()) {
		$maxSizes = array_combine($this->optionsMaxSize, $this->optionsMaxSize);
		$maxSizes = array_map('CakeNumber::toReadableSize', $maxSizes);

		$attributes = Hash::merge(array(
			'type' => 'select',
			'class' => 'form-control',
			'empty' => false
			//'style' => 'margin-bottom: 20px;',
		), $attributes);

		return $this->Form->select($fieldName, $maxSizes, $attributes);
	}

}
