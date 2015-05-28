<?php

/**
 * CampaignForm class.
 * CampaignForm is the data structure for handling
 * user register form data. It is used by the 'register' action of 'UserController'.
 */
class CampaignForm extends CFormModel
{
	public $title;
	public $plantype;
	public $starttime;
	public $endtime;
	public $timezone;
	public $https;
	public $istext;
	public $isimage;
	public $isvideo;
	public $customurl;
	public $visibility;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('title, plantype, starttime, timezone, visibility', 'required','message'=>"{attribute} is required"),
			array('timezone, status', 'numerical', 'integerOnly'=>true),
			array('timezone','in','range'=>array_keys(Yii::app()->params['timezone']),'allowEmpty'=>false),
			array('plantype','validPlanType'),
			array('visibility','validVisibility'),
			array('starttime, endtime','validDate'),
			array('customurl','validUrl'),
			array('https','validHttps'),
			array('istext, isimage, isvideo','boolean'),
			// array('iagree', 'compare', 'compareValue' => true, 'message' => 'You must agree to the terms and conditions' ),
			array('endtime, customurl, https','safe'),
		);
	}

	public function validHttps($attribute,$params) {
		if(!in_array($this->https, array_keys(Yii::app()->params['https'])) && $this->https) {
			$this->addError('https','Http Protocol is invalid');
		}
	}

	public function validUrl($attribute,$params) {
		if(!Functions::isValidDomain($this->customurl) && $this->customurl) {
			$this->addError('customurl','Custom Url is invalid');
		}
	}

	public function validPlanType($attribute,$params) {
		if(!in_array($this->plantype, array_keys(Yii::app()->params['plantype'])) && $this->plantype) {
			$this->addError('plantype','Plan type is invalid');
		}
	}

	public function validVisibility($attribute,$params) {
		if(!in_array($this->visibility, array_keys(Yii::app()->params['visibility'])) && $this->visibility) {
			$this->addError('visibility','Visibility is invalid');
		}
	}

	public function validDate($attribute, $params) {
		if($this[$attribute]) {
			$d = date("d M Y H:i", strtotime($this[$attribute]));
			if(!($d && $d == $this[$attribute])) {
				$this->addError($attribute, $this->attributeLabels()[$attribute] . ' is invalid');
			} else {
				if($attribute == "endtime") {
					$this->validEndtime($attribute,$params);
				}
			}
		}
	}

	public function validEndtime($attribute, $params) {
		if($this->endtime && strtotime($this->endtime) < strtotime($this->starttime) && strtotime($this->endtime) != 0) {
			$this->addError("endtime", "Campaign cannot end before start time");
		}
		if($this->endtime && strtotime($this->endtime) < strtotime("today") && strtotime($this->endtime) != 0) {
			$this->addError("endtime", "Campaign cannot end before today");
		}
	}

	/**
	 * Declares attribute labels.
	 */
	
	public function attributeLabels()
	{
		return array(
			'title'=>'Campaign Title',
			'plantype'=>'Plan Type',
			'starttime'=>'Start Time',
			'endtime'=>'End Time',
			'customurl'=>'Custom URL',
			'visibility'=>'Visibility',
			'status'=>'Status',
		);
	}
}
