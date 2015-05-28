<?php

/**
 * This is the model class for table "campaigntemplate".
 *
 * The followings are the available columns in table 'campaigntemplate':
 * @property integer $id
 * @property integer $userid
 * @property string $name
 * @property string $backgroundcolor
 * @property string $captionbackgroundcolor
 * @property integer $captiontextfont
 * @property string $captiontextcolor
 * @property integer $captiontextsize
 * @property string $blockbordercolor
 * @property integer $blockpaddingsize
 * @property integer $default
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Campaign[] $campaigns
 * @property Fonts $captiontextfont0
 * @property User $user
 */
class Campaigntemplate extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Campaigntemplate the static model class
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
		return 'campaigntemplate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('backgroundcolor, captionbackgroundcolor, captiontextcolor', 'required'),
			array('userid, captiontextfont, captiontextsize, blockpaddingsize, default, status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('backgroundcolor, captionbackgroundcolor, captiontextcolor, blockbordercolor', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userid, name, backgroundcolor, captionbackgroundcolor, captiontextfont, captiontextcolor, captiontextsize, blockbordercolor, blockpaddingsize, default, status', 'safe', 'on'=>'search'),
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
			'campaigns' => array(self::HAS_MANY, 'Campaign', 'templateid'),
			'captiontextfont0' => array(self::BELONGS_TO, 'Fonts', 'captiontextfont'),
			'user' => array(self::BELONGS_TO, 'User', 'userid'),
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
			'name' => 'Name',
			'backgroundcolor' => 'Backgroundcolor',
			'captionbackgroundcolor' => 'Captionbackgroundcolor',
			'captiontextfont' => 'Captiontextfont',
			'captiontextcolor' => 'Captiontextcolor',
			'captiontextsize' => 'Captiontextsize',
			'blockbordercolor' => 'Blockbordercolor',
			'blockpaddingsize' => 'Blockpaddingsize',
			'default' => 'Default',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('backgroundcolor',$this->backgroundcolor,true);
		$criteria->compare('captionbackgroundcolor',$this->captionbackgroundcolor,true);
		$criteria->compare('captiontextfont',$this->captiontextfont);
		$criteria->compare('captiontextcolor',$this->captiontextcolor,true);
		$criteria->compare('captiontextsize',$this->captiontextsize);
		$criteria->compare('blockbordercolor',$this->blockbordercolor,true);
		$criteria->compare('blockpaddingsize',$this->blockpaddingsize);
		$criteria->compare('default',$this->default);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}