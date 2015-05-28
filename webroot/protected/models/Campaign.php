<?php

/**
 * This is the model class for table "campaign".
 *
 * The followings are the available columns in table 'campaign':
 * @property integer $id
 * @property integer $userid
 * @property string $cid
 * @property string $title
 * @property integer $plantype
 * @property integer $templateid
 * @property string $starttime
 * @property string $endtime
 * @property integer $timezone
 * @property integer $initialamount
 * @property integer $loadmoreamount
 * @property string $customurl
 * @property integer $visibility
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Campaigntemplate $template
 * @property Campaignrule[] $campaignrules
 * @property Campaignsource[] $campaignsources
 * @property Paymenthistory[] $paymenthistories
 */
class Campaign extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Campaign the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'campaign';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid, cid, title, starttime', 'required'),
			array('userid, plantype, templateid, timezone, initialamount, loadmoreamount, visibility, status', 'numerical', 'integerOnly'=>true),
			array('cid, starttime, endtime, customurl', 'length', 'max'=>100),
			array('title', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userid, cid, title, plantype, templateid, starttime, endtime, timezone, initialamount, loadmoreamount, customurl, visibility, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'userid'),
			'template' => array(self::BELONGS_TO, 'Campaigntemplate', 'templateid'),
			'campaignrules' => array(self::HAS_MANY, 'Campaignrule', 'campaignid'),
			'campaignsources' => array(self::HAS_MANY, 'Campaignsource', 'campaignid'),
			'paymenthistories' => array(self::HAS_MANY, 'Paymenthistory', 'campaignid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userid' => 'Userid',
			'cid' => 'Cid',
			'title' => 'Title',
			'plantype' => 'Plantype',
			'templateid' => 'Templateid',
			'starttime' => 'Starttime',
			'endtime' => 'Endtime',
			'timezone' => 'Timezone',
			'initialamount' => 'Initialamount',
			'loadmoreamount' => 'Loadmoreamount',
			'customurl' => 'Customurl',
			'visibility' => 'Visibility',
			'status' => 'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('cid',$this->cid,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('plantype',$this->plantype);
		$criteria->compare('templateid',$this->templateid);
		$criteria->compare('starttime',$this->starttime,true);
		$criteria->compare('endtime',$this->endtime,true);
		$criteria->compare('timezone',$this->timezone);
		$criteria->compare('initialamount',$this->initialamount);
		$criteria->compare('loadmoreamount',$this->loadmoreamount);
		$criteria->compare('customurl',$this->customurl,true);
		$criteria->compare('visibility',$this->visibility);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}