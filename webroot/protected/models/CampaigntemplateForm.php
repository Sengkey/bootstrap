<?php

/**
 * CampaigntemplateForm class.
 * CampaigntemplateForm is the data structure for handling
 * user register form data. It is used by the 'register' action of 'UserController'.
 */
class CampaigntemplateForm extends CFormModel
{
	public $id;
	public $name;
	public $backgroundcolor;
	public $captionbackgroundcolor;
	public $captiontextfont;
	public $captiontextcolor;
	public $captiontextsize;
	public $blockbordercolor;
	public $blockpaddingsize;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// blockbordercolor needs to be set to required later
			array('id, backgroundcolor, captionbackgroundcolor, captiontextfont, captiontextcolor, captiontextsize, blockpaddingsize', 'required','message'=>"{attribute} is required"),
			array('name','match','pattern'=>'/^([a-zA-Z0-9 -_])+$/', 'message'=>'{attribute} is invalid'),
			array('backgroundcolor, captionbackgroundcolor, captiontextcolor, blockbordercolor','validHexcolor'),
			array('id, captiontextsize, blockpaddingsize', 'numerical', 'integerOnly'=>true),
			array('captiontextsize','validCaptionTextSize'),
			array('blockpaddingsize','validBlockPaddingSize'),
			array('name','customTemplate'),
			array('name, blockbordercolor','safe'),
		);
	}

	public function customTemplate($attribute,$params) {
		if($this['id'] == 0 && !$this['name']) {
			$this->addError("name", 'Custom template name is required');
		}
	}

	public function validHexcolor($attribute,$params) {
		if($this[$attribute] && !preg_match('/^#[a-f0-9]{6}$/i', $this[$attribute])) {
			$this->addError($attribute, $attribute . ' is invalid');
		}
	}

	public function validCaptionTextSize($attribute,$params) {
		if($this[$attribute] && !in_array($this[$attribute], array_keys(Yii::app()->params['captiontextsize']))) {
			$this->addError($attribute, $attribute . ' is invalid');
		}
	}

	public function validBlockPaddingSize($attribute,$params) {
		if($this[$attribute] && !in_array($this[$attribute], array_keys(Yii::app()->params['blockpaddingsize']))) {
			$this->addError($attribute, $attribute . ' is invalid');
		}
	}

	/**
	 * Declares attribute labels.
	 */
	
	public function attributeLabels()
	{
		return array(
			"name" => "Custom Theme Name",
			"backgroundcolor" => "Body Background Color",
			"blockpaddingsize" => "Padding/Gutter",
			"captionbackgroundcolor" => "Caption Background Color",
			"captiontextfont" => "Caption Text Font",
			"captiontextcolor" => "Caption Text Color",
			"captiontextsize" => "Caption Text Size",
			"captionfontfamily" => "Caption Font Family",
			"blockbordercolor" => "Block Border Color",
		);
	}
}
