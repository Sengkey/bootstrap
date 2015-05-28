<?php

/**
 * UserForm class.
 * UserForm is the data structure for handling
 * user register form data. It is used by the 'register' action of 'UserController'.
 */
class UserForm extends CFormModel
{
	public $email;
	public $password;
	public $iagree;
	public $rememberme;

	public $firstname;
	public $lastname;
	public $country;
	public $language;

	public $email1;
	public $password1;

	public $type;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('email, email1, password, password1, termsconditions', 'required','message'=>"{attribute} is required"),
			array('email', 'validEmail'),
			array('iagree', 'compare', 'compareValue' => true, 'message' => 'You must agree to the terms and conditions' ),
			array('firstname, lastname','match','pattern'=>'/^([a-zA-Z0-9\'" -])+$/', 'message'=>'{attribute} is invalid'),
			array('country', 'validCountry'),
			array('language', 'validLanguage'),
			array('rememberme', 'boolean'),
			array('email1', 'compare', 'compareAttribute'=>'email', 'message'=>"Email mismatch"),
			array('password1', 'compare', 'compareAttribute'=>'password', 'message'=>"Password mismatch"),
			array('type', 'safe'),
		);
	}

	/**
	 * Check email validity
	 */
	public function validEmail($attribute,$params)
	{
		if(!filter_var($this->email, FILTER_VALIDATE_EMAIL) && $this->email) {
			$this->addError('email','Email Address is not a valid email address');
		}
	}

	/**
	 * Check country validity
	 */
	public function validCountry($attribute,$params)
	{
		if((!in_array($this->country, array_keys(Yii::app()->params['countries'])) && $this->country) || $this->country == "") {
			$this->addError('country','Country is invalid');
		}
	}

	/**
	 * Check language validity
	 */
	public function validLanguage($attribute,$params)
	{
		if((!in_array($this->language, array_keys(Yii::app()->params['languages'])) && $this->language) || $this->language == "") {
			$this->addError('language','Language is invalid');
		}
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'firstname' => 'First Name',
			'lastname' => 'Last Name',
			'country' => 'Country',
			'language' => 'Language',
			'email' => 'Email',
			'password' => 'Password',
		);
	}
}
