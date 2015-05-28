<?php

/**
 * This is the model class for table "paymenthistory".
 *
 * The followings are the available columns in table 'paymenthistory':
 * @property integer $id
 * @property integer $campaignid
 * @property string $transactionid
 * @property string $subscriberid
 * @property string $paidtime
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Campaign $campaign
 */
class Paymenthistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Paymenthistory the static model class
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
		return 'paymenthistory';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaignid, paidtime', 'required'),
			array('campaignid, status', 'numerical', 'integerOnly'=>true),
			array('transactionid, subscriberid, paidtime', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, campaignid, transactionid, subscriberid, paidtime, status', 'safe', 'on'=>'search'),
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
			'transactionid' => 'Transactionid',
			'subscriberid' => 'Subscriberid',
			'paidtime' => 'Paidtime',
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
		$criteria->compare('transactionid',$this->transactionid,true);
		$criteria->compare('subscriberid',$this->subscriberid,true);
		$criteria->compare('paidtime',$this->paidtime,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}