<?php

/**
 * This is the model class for table "campaignrule".
 *
 * The followings are the available columns in table 'campaignrule':
 * @property integer $id
 * @property integer $campaignid
 * @property string $type
 * @property integer $public
 * @property integer $starttime
 * @property integer $endtime
 * @property string $allowuser
 * @property string $blockuser
 * @property string $allowtag
 * @property string $blocktag
 * @property integer $istext
 * @property integer $isimage
 * @property integer $isvideo
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Campaign $campaign
 */
class Campaignrule extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Campaignrule the static model class
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
		return 'campaignrule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaignid, type', 'required'),
			array('campaignid, public, starttime, endtime, isimage, isvideo, status', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>2),
			array('allowuser, blockuser, allowtag, blocktag', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, campaignid, type, public, endtime, allowuser, blockuser, allowtag, blocktag, istext, isimage, isvideo, status', 'safe', 'on'=>'search'),
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
			'campaign' => array(self::BELONGS_TO, 'Campaign', 'campaignid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'campaignid' => 'Campaignid',
			'type' => 'Type',
			'public' => 'Public',
			'starttime' => 'Start Time',
			'endtime' => 'End Time',
			'allowuser' => 'Allowuser',
			'blockuser' => 'Blockuser',
			'allowtag' => 'Allowtag',
			'blocktag' => 'Blocktag',
			'istext' => 'Is text',
			'isimage' => 'Is image',
			'isvideo' => 'Is video',
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
		$criteria->compare('campaignid',$this->campaignid);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('public',$this->public);
		$criteria->compare('starttime',$this->starttime,true);
		$criteria->compare('endtime',$this->endtime,true);
		$criteria->compare('allowuser',$this->allowuser,true);
		$criteria->compare('blockuser',$this->blockuser,true);
		$criteria->compare('allowtag',$this->allowtag,true);
		$criteria->compare('blocktag',$this->blocktag,true);
		$criteria->compare('istext',$this->istext);
		$criteria->compare('isimage',$this->isimage);
		$criteria->compare('isvideo',$this->isvideo);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}