<?php

class Feed extends EMongoDocument
{

/*
		0 _id
		1 feed id
		2 feed source (fb, tw, ig, etc)
		3 userid
		3.5 username
		3.6 userimgurl
		4 description
		5 imgurl
		6 imgw
		7 imgh
		8 smallimgurl
		9 smallimgw
		10 smallimgh
		11 time
		12 videourl
		13 status
*/

	/**
	 * Returns the static model of the specified AR class.
	 * @return Test the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function collectionName()
	{
		return 'feeds';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('feedid, feedsource, userid, username, time, status', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('feedid, feedsource, userid, username, userimgurl, imgw, imgh, desc, time, status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'feedid' => 'Feed ID',
			'feedsource' => 'Feed Source',
			'userid' => 'User ID',
			'username' => 'User name',
			'userimgurl' => 'User Image URL',
			'desc' => 'Description',
			'imgurl' => 'Image Url',
			'imgw' => 'Image Width',
			'imgh' => 'Image Height',
			'smallimgurl' => 'Small Image Url',
			'smallimgw' => 'Small Image Width',
			'smallimgh' => 'Small Image Height',
			'time' => 'Time',
			'videourl' => 'Video Url',
			'status' => 'Status',
		);
	}
}