
<?php

class SiteController extends Controller
{
	public $layout = "main";
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		echo "Silence is golden";exit;
		$data['tpl'] = "/site/home";
		$this->render('index',array("data"=>$data));
	}

	public function actionAjax() {
		if(isset($_POST) && isset($_POST['type'])) {
			// print_r($_POST);exit;
			$output = array();
			$errors = array();
			$output[0] = 1;
			if( $_POST['type']=="loadmore" && isset($_POST['cid'])	&& isset($_POST['feednumber']) && is_numeric($_POST['feednumber'])) {
				$campaign = Campaign::model()->findByAttributes(array("cid"=>$_POST['cid']));
				$data = array();
				// foreach(Test::model()->find(['id'  => ['$gte' => 2]]) as $feed) {
				$mongocon = Yii::app()->mongodb->getConnection();
				Yii::app()->mongodb->selectDB('pond_'.$campaign['cid']);
				$i=0;
				$data['feed'] = array();
				$data['campaignid'] = $campaign['cid'];
				foreach(Feed::model()->find(['status'  => 1])->skip($_POST['feednumber'])->limit($campaign['loadmoreamount'])->sort(array('time' => -1)) as $feed) {
					$data['feed'][$i] = $feed->attributes;
					$i++;
				}
				$feeds = $this->renderPartial('/site/feedbox',array("data"=>$data),true);
				array_push($output,$feeds);
			} else if( $_POST['type']=="campaignrule" &&
				isset($_POST['source']) && in_array($_POST['source'],array_keys(Yii::app()->params['campaignsource'])) &&
				$campaign = Campaign::model()->findByAttributes(array("cid"=>$_POST['cid'],"userid"=>Yii::app()->user->ID,"status"=>1))) {
				$campaignrule = new Campaignrule;
				$campaignrule['type'] = $_POST['source'];
				$campaignrule['campaignid'] = $campaign['id'];
				$campaignrule['istext'] = (isset($_POST['istext'])) ? 1 : 0;
				$campaignrule['isimage'] = (isset($_POST['isimage'])) ? 1 : 0;
				$campaignrule['isvideo'] = (isset($_POST['isvideo'])) ? 1 : 0;
				$campaignrule['starttime'] = (isset($_POST['starttime']) && $_POST['starttime']) ? strtotime($_POST['starttime']) : 0;
				$campaignrule['endtime'] = (isset($_POST['endtime']) && $_POST['endtime']) ? strtotime($_POST['endtime']) : 0;
				$campaignrule['public'] = $_POST['publicpost'];
				if(!$campaignrule['public']) { // specific users
					if(isset($_POST['allowuser']) || isset($_POST['blockuser'])) {
						if(isset($_POST['allowuser'])) {
							if(Functions::validateTags($_POST['allowuser'], 1)) {
								$campaignrule['allowuser'] = Functions::prettifyTags($_POST['allowuser']);
							} else {
								array_push($errors, "Allow specific users contains 1 or more invalid tags");
							}
						}
						if(isset($_POST['blockuser'])) {
							if(Functions::validateTags($_POST['blockuser'], 1)) {
								$campaignrule['blockuser'] = Functions::prettifyTags($_POST['blockuser']);
							} else {
								array_push($errors, "Block specific users contains 1 or more invalid tags");
							}
						}
					} else {
						array_push($errors, "At least 1 user must be specified");
					}
				} else {
					if($campaignrule['type']=="ig") { // Instagram API does not provide time constraint for public images
						$campaignrule['starttime'] = 0;
						$campaignrule['endtime'] = 0;
					}
				}
				if(Functions::validateTags($_POST['allowtag'])) {
					$campaignrule['allowtag'] = Functions::prettifyTags($_POST['allowtag']);
				} else {
					array_push($errors, "Allow specific tags contains 1 or more invalid tags");
				}
				if(Functions::validateTags($_POST['blocktag'])) {
					$campaignrule['blocktag'] = Functions::prettifyTags($_POST['blocktag']);
				} else {
					array_push($errors, "Block specific tags contains 1 or more invalid tags");
				}
				$campaignrule['status']=1;
				try {
					$campaignrule->save();
				} catch (Exception $e) {
					print_r($e);
				}
				if(!count($errors) && $campaignrule->save()) {
					$data['ruleid'] = $campaignrule['id'];
					$data['source'] = $_POST['source'];
					$data['statement'] = Functions::getRuleStatement($campaignrule);
					$statement['html'] = $this->renderPartial('/site/campaignrule',array("data"=>$data),true);
					array_push($output, $statement);
				} else {
					array_push($errors, $campaignrule->errors);
				}
			} else if($_POST['type']=="pasterule" && isset($_POST['ruleid']) && is_numeric($_POST['ruleid']) &&
				isset($_POST['source']) && in_array($_POST['source'],array_keys(Yii::app()->params['campaignsource'])) &&
				$campaignrule = Campaignrule::model()->findByAttributes(array("id"=>$_POST['ruleid'],"status"=>1))) {
					if($campaignrule && $campaign = Campaign::model()->findByAttributes(array("id"=>$campaignrule['campaignid'],"userid"=>Yii::app()->user->ID,"status"=>1))) {
						$rules = array();
						$rules['starttime'] = date("d M Y H:i",$campaignrule['starttime']);
						$rules['endtime'] = ($campaignrule['endtime']!=0) ? date("d M Y H:i",$campaignrule['endtime']) : "";
						$rules['publicpost'] = $campaignrule['public'];
						if($rules['publicpost']==0) {
							$userids = explode(",", $campaignrule['allowuser']);
							$rules['allowuser'] = array();
							foreach($userids AS $userid) {
								$userid = trim($userid);
								$userdata = Functions::getSocialUserData($_POST['source'],$userid);
								if(isset($userdata['text'])) {
									array_push($rules['allowuser'], $userdata);
								}
							}
							$userids = explode(",", $campaignrule['blockuser']);
							$rules['blockuser'] = array();
							foreach($userids AS $userid) {
								$userid = trim($userid);
								$userdata = Functions::getSocialUserData($_POST['source'],$userid);
								if(isset($userdata['text'])) {
									array_push($rules['blockuser'], $userdata);
								}
							}
						}
						$rules['allowtag'] = explode(',', $campaignrule['allowtag']);
						$rules['blocktag'] = explode(',', $campaignrule['blocktag']);
						$rules['istext'] = $campaignrule['istext'];
						$rules['isimage'] = $campaignrule['isimage'];
						$rules['isvideo'] = $campaignrule['isvideo'];
						array_push($output, $rules);
					} else {
						array_push($errors, "Invalid campaign rule id");
					}
			} else if($_POST['type']=="toggleswitchcampaign" && isset($_POST['cid']) &&
				isset($_POST['source']) && in_array($_POST['source'],array_keys(Yii::app()->params['campaignsource'])) &&
				$campaign = Campaign::model()->findByAttributes(array("id"=>$_POST['cid'],"userid"=>Yii::app()->user->ID,"status"=>1))) {
					$campaignsource = Campaignsource::model()->findByAttributes(array("campaignid"=>$campaign['id'],'type'=>$_POST['source']));
					if(!$campaignsource) {
						$campaignsource = new Campaignsource;
						$campaignsource['campaignid'] = $campaign['id'];
						$campaignsource['type'] = $_POST['source'];
					}
					if($campaignsource['status']==1) {
						$campaignsource['status'] = 0;
					} else {
						$campaignsource['status'] = 1;
					}
					if($campaignsource->save()) {
						Yii::app()->mongodb->db = "pond_".$campaign['cid'];
						if($campaignrules = Campaignrule::model()->findAllByAttributes(array("campaignid"=>$campaign['id'],"status"=>1))) {
							foreach($campaignrules AS $campaignrule) {
								if(Campaignsource::model()->findByAttributes(array("campaignid"=>$campaign['id'],'type'=>$campaignrule['type'],"status"=>1))) {
									Functions::getFeeds($campaignrule);
								}
							}
						}
					}
				return;
			} else if($_POST['type']=="deleterule" && isset($_POST['ruleid']) && is_numeric($_POST['ruleid']) &&
				$campaignrule = Campaignrule::model()->findByAttributes(array("id"=>$_POST['ruleid'],"status"=>1))) {
				if($campaignrule && $campaign = Campaign::model()->findByAttributes(array("id"=>$campaignrule['campaignid'],"userid"=>Yii::app()->user->ID,"status"=>1))) {
					$campaignrule['status']=0;
					if(!$campaignrule->save()) {
						array_push($errors, $campaignrule->errors);
					}
				} else {
					array_push($errors, "Invalid campaign rule id");
				}
			} else if($_POST['type']=="getsocialuser" && isset($_POST['username']) &&
				isset($_POST['source']) && in_array($_POST['source'],array_keys(Yii::app()->params['campaignsource']))) {

				if($_POST['source']=="fb") {
					$userjsondata = Functions::fetchUrl("http://graph.facebook.com/".$_POST['username']);
					$userdata = json_decode($userjsondata,true);
					if(isset($userdata['error'])) {
						array_push($errors, "Username does not exist");
					} else {
						array_push($output,$userdata['id']);
					}
				} else if($_POST['source']=="tw") {
					$tmhOAuth = new tmhOAuth(array(
						'consumer_key'		=> Yii::app()->params['twconsumer_key'],
						'consumer_secret'	=> Yii::app()->params['twconsumer_secret'],
						'user_token'		=> Yii::app()->params['twuser_token'],
						'user_secret'		=> Yii::app()->params['twuser_secret'],
					));

					$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/users/show.json'), array(
						'screen_name'	=> $_POST['username'],
					));
					if ($code == 200) {
						$userdata = json_decode($tmhOAuth->response['response'], true);
						array_push($output,$userdata['id_str']);
					} else {
						array_push($errors,"User does not exist");
					}
				} else if($_POST['source']=="ig") {
					$url = "https://api.instagram.com/v1/users/search?q=".$_POST['username']."&count=3&access_token=".Yii::app()->params['igaccesstoken'];
					$response = Functions::fetchUrl($url);
					$content = json_decode($response, true);
					$userid = 0;
					if($content['meta']['code']==200) {
						foreach($content['data'] AS $userdata) {
							if($userdata['username']==$_POST['username']) {
								$userid = $userdata['id'];
								$url = "https://api.instagram.com/v1/users/$userid/relationship?access_token=".Yii::app()->params['igaccesstoken'];
								$response = Functions::fetchUrl($url);
								$useraccessibility = json_decode($response, true);
								if($useraccessibility['data']['target_user_is_private']) {
									array_push($errors,"User ".$userdata['username']." is private");
								} else {
									array_push($output,$userdata['id']);
								}
								break;
							}
						}
						if(!$userid) {
							array_push($errors,"User does not exist");
						}
					} else {
						array_push($errors,"User does not exist");
					}
				} else {
					array_push($errors, "Invalid data supplied");
				}
			} else if($_POST['type']=="feedstatusswitch" 
				&& isset($_POST['feedid']) && isset($_POST['checked']) 
				&& isset($_POST['cid']) && $campaign = Campaign::model()->findByAttributes(array("cid"=>$_POST['cid'],"userid"=>Yii::app()->user->ID,"status"=>1))
				) {
				// try {
					$mongocon = Yii::app()->mongodb->getConnection();
					Yii::app()->mongodb->selectDB('pond_'.$_POST['cid']);
					$id = Feed::model()->getPrimaryKey($_POST['feedid']);
					if($feed = Feed::model()->findOne(['_id'=>$id]) ) {
						$feed['status'] = (int) $_POST['checked'];
						if($feed->save()) {
							$output[0] = 1;
						} else {
							array_push($errors,$feed->errors);
						}
					}
				// } catch (Exception $e) {
				// 	array_push($errors, $e->getMessage());
				// }
			} else if($_POST['type']=="selecttemplate" 
				&& isset($_POST['templateid']) && is_numeric($_POST['templateid']) 
				&& $template = Campaigntemplate::model()->findByAttributes(array("id"=>$_POST['templateid'],"status"=>1))
				) {
				if($template['default'] || (!$template['default'] && $template['userid']==Yii::app()->user->ID)) {
					$output[] = $template->attributes;
				} else {
					array_push($errors, "Invalid data supplied");
				}
			} else {
				array_push($errors, "Invalid data supplied");
			}
			if(count($errors)) {
				$output[0] = 0;
				array_push($output, $errors);
			}
			echo json_encode($output);
		}
		return;
	}

	public function actionDashboard() {
		if(Yii::app()->user->ID) {
			$user = User::model()->findByPk(Yii::app()->user->ID);
			if($user['status']==0) {
				$this->redirect("activate");
			}
			$data['tpl'] = "/site/dashboard";
			$data['activemenu'] = "dashboard";
			if($user['token']!="") {
				$data['newuser'] = 1;
				$user['token'] = "";
				$user->save();
			}
			$this->render('index',array("data"=>$data));
		} else {
			$this->redirect('login');
		}
	}

	public function actionProfile() {
		if(!Yii::app()->user->ID) {
			Yii::app()->user->returnUrl = Yii::app()->request->requestUri;
			$this->redirect('login');
		}
		$user = User::model()->findByPk(Yii::app()->user->ID);
		$data['tpl'] = "/site/profile";
		$data['activemenu'] = "profile";
		$model = new UserForm;
		if(isset($_POST['UserForm']) && isset($_POST['UserForm']['type'])) {
			$model->attributes = $_POST['UserForm'];
			$type = $_POST['UserForm']['type'];
			$data['errors'] = array();

			if($type == "profile") {
				if($model->validate(array('firstname','lastname','country','language'))) {
					$user['firstname'] = $model['firstname'];
					$user['lastname'] = $model['lastname'];
					$user['country'] = $model['country'];
					$user['language'] = $model['language'];
					if($user->save()) {
						$this->redirect("profile");
					}
				} else {
					foreach($model->errors AS $k=>$v) {
						$data['errors']=array_merge($data['errors'],$v);
					}
				}
			} else if($type == "email") {
				if($model->validate(array('email','email1'))) {
					$user['email'] = $model['email'];
					if($user->save()) {
						Yii::app()->user->setState("email",$user['email']);
						$this->redirect("profile");
					}
				} else {
					foreach($model->errors AS $k=>$v) {
						$data['errors']=array_merge($data['errors'],$v);
					}
				}
			} else if($type == "password") {
				if($model->validate(array('password','password1'))) {
					$user['password'] = md5($model['password']);
					if($user->save()) {
						$this->redirect("profile");
					}
				} else {
					foreach($model->errors AS $k=>$v) {
						$data['errors']=array_merge($data['errors'],$v);
					}
				}
			}
		} else {
			$model['firstname'] = $user['firstname'];
			$model['lastname'] = $user['lastname'];
			$model['country'] = $user['country'];
			$model['language'] = $user['language'];
		}
		$data['model'] = $model;
		$this->render('index',array("data"=>$data));
	}

	public function actionCampaign() {
		if(!Yii::app()->user->ID) {
			Yii::app()->user->returnUrl = Yii::app()->request->requestUri;
			$this->redirect('login');
		}
		$data['tpl'] = "/site/campaign";
		$data['activemenu'] = "campaign";
		$data['campaigns'] = array();
		$i=0;
		$campaigns = Campaign::model()->findAllByAttributes(array("userid"=>Yii::app()->user->ID,"status"=>1),array("order"=>"id DESC"));
		foreach($campaigns AS $campaign) {
			$data['campaigns'][$i] = $campaign->attributes;

			$mongocon = Yii::app()->mongodb->getConnection();
			Yii::app()->mongodb->selectDB('pond_'.$campaign['cid']);
			$feeds = Feed::model()->findAll();
			$data['campaigns'][$i]['feeds'] = $feeds->count();
			$i++;
		}
		$this->render('index',array("data"=>$data));
	}

	/*
		Campaign feed data ->>beepond_db
		1 public or not
		2 allowuser
		3 blockuser
		4 allowtag
		5 blocktag
		6 isimage
		8 isvideo
		 		9 type
		10 feed status

		Feed data -->> mongodbb
		0 _id
		1 feed id
		2 feed type (fb, tw, ig, etc)
		3 publisher
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




	public function actionCampaignEdit() {
		if(!Yii::app()->user->ID) {
			Yii::app()->user->returnUrl = Yii::app()->request->requestUri;
			$this->redirect('login');
		}
		$data['tpl'] = "/site/campaignedit";
		$data['activemenu'] = "campaign";
		$data['editcampaign'] = 0;
		$data['notification'] = "";
		if(Yii::app()->user->getState("campaignupdate")) {
			$data['notification'] = "update";
			Yii::app()->user->setState("campaignupdate",null);
		}
		$model = new CampaignForm;
		if(isset($_GET['cid'])) { // Edit
			if($campaign = Campaign::model()->findByAttributes(array("cid"=>$_GET['cid']))) {
				$data['editcampaign'] = 1;
			} else if(!Campaign::model()->findByAttributes(array("cid"=>$_GET['cid'],"userid"=>Yii::app()->user->ID,"status"=>1))) {
				$this->redirect("campaign");
			} else {
				$this->redirect('campaignedit');
			}
		}
		if(isset($_POST['CampaignForm'])) {
			$model->attributes = $_POST['CampaignForm'];
			if(!$model['plantype'] && isset($campaign)) {
				$model['plantype'] = $campaign['plantype'];
			}
			if($model->validate(array('title','plantype','starttime','endtime','timezone','https','customurl','visibility'))) {
				if(!isset($campaign)) { // setting up a new campaign variable
					$campaign = new Campaign;
					$campaign['userid'] = Yii::app()->user->ID;
					do {
						$campaign['cid'] = Functions::generateRandomCid();
					} while (Campaign::model()->findByAttributes(array("cid"=>$campaign['cid']))); // prevent duplicated cid
				}
				$campaign['title'] = htmlspecialchars($model['title']);
				$neworder=0;
				if($campaign['plantype'] < $model['plantype']) {
					$neworder = 1;
				}
				$campaign['starttime'] = strtotime($model['starttime']);
				$campaign['endtime'] = strtotime($model['endtime']);
				$campaign['timezone'] = $model['timezone'];
				if(strpos($model['customurl'], "https://") === 0) {
					$model['https']=1;
				}
				if(!(strpos($model['customurl'], "https://") === 0 || strpos($model['customurl'], "http://") === 0) && $model['customurl']!="") {
					$campaign['customurl'] = Yii::app()->params['https'][$model['https']].$model['customurl'];
				} else {
					$campaign['customurl'] = $model['customurl'];
				}
				$campaign['visibility'] = $model['visibility'];
				// $campaign['templateid'] = 1;

				if($campaign->save()) {
					if($neworder==1) { // redirect to order confirmation
						Yii::app()->user->setState("plantype", $model['plantype']);
						Yii::app()->user->setState("campaignid", $campaign['id']);
						$this->redirect("orderconfirmation");
					} else { // redirect to campaignedit
						if(isset($_GET['cid'])) {
							$cid = $_GET['cid'];
						} else {
							$cid = $campaign['id'];
						}
						Yii::app()->user->setState("campaignupdate", $cid);
						$this->redirect(array('campaignedit', 'cid'=>$cid));
					}
				} else {
					// print_r($campaign->errors);
				}
			} else {
				$data['errors'] = array();
				foreach($model->errors AS $k=>$v) {
					$data['errors']=array_merge($data['errors'],$v);
				}
			}
		} else {
		}
		if(isset($campaign)) {
			$model['title'] = $campaign['title'];
			$model['starttime'] = date("d M Y H:i",$campaign['starttime']);
			if($campaign['endtime']) {
				$model['endtime'] = date("d M Y H:i",$campaign['endtime']);
			}
			$model['timezone'] = $campaign['timezone'];
			if(strpos($campaign['customurl'], "https://") === 0) {
				$model['https'] = 1;
			}
			$model['customurl'] = str_replace(array_values(Yii::app()->params['https']),"",$campaign['customurl']);
			$model['plantype'] = $campaign['plantype'];
			$model['visibility'] = $campaign['visibility'];

			foreach(Yii::app()->params['campaignsource'] AS $campaignruleid=>$campaignname) {
				if($campaignrules = Campaignrule::model()->findAllByAttributes(array("campaignid"=>$campaign['id'],"type"=>$campaignruleid,"status"=>1))) {
					$data['campaignrule'][$campaignruleid] = "";
					foreach($campaignrules AS $campaignrule) {
						$data['ruleid'] = $campaignrule['id'];
						$data['source'] = $campaignruleid;
						$data['statement'] = Functions::getRuleStatement($campaignrule);
						$data['campaignrule'][$campaignruleid] .= $this->renderPartial('/site/campaignrule',array("data"=>$data),true);
					}
				}
				$campaignsources = Campaignsource::model()->findAllByAttributes(array("campaignid"=>$campaign['id']));
				foreach($campaignsources AS $campaignsource) {
					$data['campaignsource'][$campaignsource['type']] = $campaignsource['status'];
				}
			}
			$data['campaign'] = $campaign;
		}

		$data['model'] = $model;
		if(!$data['model']['starttime']) {
			$data['model']['starttime'] = date("d M Y H:i");
		}
		if(!$data['model']['timezone']) {
			$data['model']['timezone'] = 0;
		}
		$this->render('index',array("data"=>$data));
	}

	public function actionTemplateEdit() {
		if(!Yii::app()->user->ID) {
			Yii::app()->user->returnUrl = Yii::app()->request->requestUri;
			$this->redirect('login');
		}

		if(!isset($_GET['cid']) || !$campaign = Campaign::model()->findByAttributes(array("cid"=>$_GET['cid'],"userid"=>Yii::app()->user->ID,"status"=>1))) {
			$this->redirect("campaign");
		}
		$data['campaign'] = $campaign;
		$data['tpl'] = "/site/templateedit";
		$data['activemenu'] = "campaign";

		$model = new CampaigntemplateForm;
		if(isset($_POST['CampaigntemplateForm'])) {
			$model->attributes = $_POST['CampaigntemplateForm'];
			if($model->validate(array('id','name','backgroundcolor','captionbackgroundcolor','captiontextfont','captiontextcolor','captiontextsize','blockpaddingsize'))) { // blockbordercolor needs to be validated later
				if(!Fonts::model()->findByPk($model['captiontextfont'])) {
					$model->addError('captiontextfont', 'Caption Text Font is invalid');
				} else {
					$updatetemplate = 1;
					$templateerror = 0;
					if($model['id']==0) {
						$campaigntemplate = new Campaigntemplate;
						$campaigntemplate['name'] = trim($model['name']);
					} else {
						$campaigntemplate = Campaigntemplate::model()->findByAttributes(array("id"=>$model['id'],"userid"=>Yii::app()->user->ID));
						if(!$campaigntemplate) {
							$model->addError("id","Campaign template ID is invalid");
							$updatetemplate = 0;
							$templateerror = 1;
						} else if($campaigntemplate['default']==1) {
							$updatetemplate = 0;
						}
					}
					if($updatetemplate) {
						$campaigntemplate['backgroundcolor'] 		= str_replace("#","",$model['backgroundcolor']);
						$campaigntemplate['captionbackgroundcolor'] = str_replace("#","",$model['captionbackgroundcolor']);
						$campaigntemplate['captiontextfont'] 		= $model['captiontextfont'];
						$campaigntemplate['captiontextcolor'] 		= str_replace("#","",$model['captiontextcolor']);
						$campaigntemplate['captiontextsize'] 		= str_replace("#","",$model['captiontextsize']);
						$campaigntemplate['blockpaddingsize'] 		= str_replace("#","",$model['blockpaddingsize']);
						$campaigntemplate['userid'] 				= Yii::app()->user->ID;
					}
					if(!$templateerror && $campaigntemplate->save()) {
						$campaign['templateid'] = $campaigntemplate['id'];
						$campaign->save();
						$this->redirect(array('templateedit', 'cid'=>$campaign['cid']));
				// 		// send activation email
				// 		$mailparams = array();
				// 		$mailparams['user'] = $campaigntemplate;
				// 		Functions::sendmail("activate",$campaigntemplate);
				// 		Yii::app()->user->ID = $campaigntemplate['email'];
				// 		$this->redirect("activate");
					}
				}
			} else {
				// print_r($model->errors);
			}
		} else if($campaign['templateid'] && $campaigntemplate = Campaigntemplate::model()->findByPk($campaign['templateid'])) {
			$model['id'] 						= $campaigntemplate['id'];
			$model['backgroundcolor'] 			= "#".$campaigntemplate['backgroundcolor'];
			$model['captionbackgroundcolor'] 	= "#".$campaigntemplate['captionbackgroundcolor'];
			$model['captiontextfont'] 			= $campaigntemplate['captiontextfont'];
			$model['captiontextcolor'] 			= "#".$campaigntemplate['captiontextcolor'];
			$model['captiontextsize'] 			= $campaigntemplate['captiontextsize'];
			$model['blockpaddingsize'] 			= $campaigntemplate['blockpaddingsize'];
		}

		// Set default templates
		$defaulttemplates = Campaigntemplate::model()->findAllByAttributes(array("default"=>1));
		$data['templates'] = array();
		foreach($defaulttemplates AS $template) {
			$data['templates'][$template['id']] = $template['name'];
		}

		// Set current templates
		$usertemplates = Campaigntemplate::model()->findAllByAttributes(array("userid"=>Yii::app()->user->ID));
		foreach($usertemplates AS $template) {
			$data['templates'][$template['id']] = $template['name'];
		}

		// Add custom to the end of the array
		$data['templates'][0] = "Custom";

		// Set the fonts options
		$fonts = Fonts::model()->findAll();
		foreach($fonts AS $font) {
			$data['fonts'][$font['id']] = $font['name'];
		}
		$data['model'] = $model;
		$this->render('index',array("data"=>$data));
	}

	public function actionFeeds() {
		if(!Yii::app()->user->ID) {
			Yii::app()->user->returnUrl = Yii::app()->request->requestUri;
			$this->redirect('login');
		}

		if(isset($_GET['cid'])
			&& $data['campaign'] = Campaign::model()->findByAttributes(array("cid"=>$_GET['cid'])) // Campaign exists & belongs to current user
			// && $data['campaign'] = Campaign::model()->findByAttributes(array("cid"=>$_GET['cid'],"userid"=>Yii::app()->user->ID)) // Campaign exists & belongs to current user

			) {
			$data['tpl'] = "/site/feeds";
			$data['activemenu'] = "campaign";
			$data['pageNum'] = 1;
			if(isset($_GET['p'])) {
				$data['pageNum'] = $_GET['p'];
			}
			$data['q'] = "";
			if(isset($_GET['q'])) {
				$data['q'] = $_GET['q'];
			}

			$data['feedsource'] = "";
			if(isset($_GET['feedsource'])) {
				$data['feedsource'] = $_GET['feedsource'];
			}

			$mongocon = Yii::app()->mongodb->getConnection();
			Yii::app()->mongodb->selectDB('pond_'.$_GET['cid']);
			
			if($data['q']) {
				$c = new EMongoCriteria();
				$c1 = new EMongoCriteria();
				$c2 = new EMongoCriteria();
				$c1->compare("username",$data['q'],true);
				$c2->compare("desc",$data['q'],true);
				$c->addOrCondition(array($c1->condition,$c2->condition));
				if($data['feedsource']) {
					$c->addCondition("feedsource",$data['feedsource']);
				}
				$feeds = Feed::model()->findAll($c);
			} else {
				if($data['feedsource']) {
					$feeds = Feed::model()->findAll(["feedsource"=>$data['feedsource']]);
				} else {
					$feeds = Feed::model()->findAll();
				}
			}
			$data['totalCampaignNumber'] = $feeds->count();
			$feeds = $feeds->skip(Yii::app()->params['elementsPerPage'] * ($data['pageNum']-1))->limit(Yii::app()->params['elementsPerPage'])->sort(array('time' => -1));
			$i=0;
			$data['feed'] = array();
			foreach($feeds as $feed) {
				$data['feed'][$i] = $feed->attributes;
				$i++;
			}
			$this->render('index',array("data"=>$data));
		} else {
			$this->redirect('dashboard');
		}
	}

	// public function actionTestOffset() {

	// 	$mongocon = Yii::app()->mongodb->getConnection();
	// 	Yii::app()->mongodb->selectDB('pond_onnLRnH1r1k');
	// 	$q = "Caleb";
	// 	$q1 = "haru";
	// 	$c = new EMongoCriteria();
	// 	$c1 = new EMongoCriteria();
	// 	$c2 = new EMongoCriteria();
	// 	$c1->compare("username",$q,true);
	// 	$c2->compare("desc",$q1,true);
	// 	$c->addOrCondition(array($c1->condition,$c2->condition));
	// 	// $c->compare("desc",$q1,true,"OR");
		
		
	// 	// $c->mergeWith($c1);
	// 	// $c->addOrCondition(array($c1->condition));
	// 	// $c->mergeWith($c1);
	// 	print_r($c);
	// 	$feeds = Feed::model()->findAll($c);
		
	// 	// $feeds = Feed::model()->find(['status'=>1])->skip(10)->limit(10)->sort(array('time' => -1));
	// 	// $i=0;
	// 	foreach($feeds AS $feed) {
	// 		print_r($feed->attributes);
	// 	// 	echo $feed['desc'];
	// 	// 	echo "<BR><BR>";
	// 	// 	$i++;
	// 	}
	// 	// echo $i;
	// }

	public function actionOrderConfirmation() {
		if($data['campaign']=Campaign::model()->findByPk(Yii::app()->user->getState("campaignid"))) {
			$data['plantype'] = Yii::app()->user->getState("plantype");
			$data['total'] = 0;
			$data['total'] += Yii::app()->params['plancost'][$data['plantype']]; // billed every 30 days
		} else {
			$this->redirect("dashboard");
		}
		$data['tpl'] = "/site/orderconfirmation";
		$data['activemenu'] = "billing";
		$this->render('index',array("data"=>$data));
	}

	public function actionCampaignFront() {
		$data = array();
		// foreach(Test::model()->find(['id'  => ['$gte' => 2]]) as $feed) {
		$mongocon = Yii::app()->mongodb->getConnection();
		Yii::app()->mongodb->selectDB('pond_'.$_GET['u']);
		$i=0;
		$data['feed'] = array();
		$data['campaignid'] = $_GET['u'];
		$data['campaign'] = Campaign::model()->findByAttributes(array("cid"=>$_GET['u']));
		$data['campaigntemplate'] = Campaigntemplate::model()->findByPk($data['campaign']['templateid']);
		$feeds = Feed::model()->find(['status' => 1])->limit($data['campaign']['initialamount'])->sort(array('time' => -1));
		// $feeds = Feed::model()->find(['status'  => 1])->limit(1000)->sort(array('time' => -1));
		foreach($feeds as $feed) {
			$data['feed'][$i] = $feed->attributes;
			$i++;
		}
		$data['font'] = Fonts::model()->findByPk($data['campaigntemplate']['captiontextfont']);
		$data['maxfeednumber'] = Feed::model()->find(['status'  => 1])->count();
		$this->renderPartial('indexfront',array("data"=>$data));
	}

	public function actionFront() {
		$data = array();
		// foreach(Test::model()->find(['id'  => ['$gte' => 2]]) as $feed) {
		$_GET['u'] = "onnLRnH1r1k";
		$mongocon = Yii::app()->mongodb->getConnection();
		Yii::app()->mongodb->selectDB('pond_'.$_GET['u']);
		$i=0;
		$data['feed'] = array();
		$data['campaignid'] = $_GET['u'];
		foreach(Feed::model()->find(['status'  => 1])->limit(20)->sort(array('time' => -1)) as $feed) {
			$data['feed'][$i] = $feed->attributes;
			$i++;
		}

		$this->renderPartial('indexfront',array("data"=>$data));
	}

	public function actionFeedDetails() {

		// $_POST['cid'] = "onnLRnH1r1k";
		// $_POST['id'] = "54697f4af313870c8bd63af1";

		// $feed = Feed::model()->findOne(['_id'=>$id,'status'=>1]);
		// print_r($feed->attributes);
		$output = array();
		$output[0] = 0;
		if(isset($_POST['cid']) && isset($_POST['id'])) {
			$mongocon = Yii::app()->mongodb->getConnection();
			Yii::app()->mongodb->selectDB('pond_'.$_POST['cid']);

			$id = Feed::model()->getPrimaryKey($_POST['id']);

			if($data['feed'] = Feed::model()->findOne(['_id'=>$id,'status'  => 1]) ) {
				$data['videotype'] = "";
				if($data['feed']['videourl']) {
					$data['videourldata'] = parse_url($data['feed']['link']);
					if (strpos($data['videourldata']['host'],"youtu") !== false) {
						$data['videotype'] = "youtube";
					} else if (strpos($data['videourldata']['host'],"facebook") !== false) {
						$data['videotype'] = "facebook";
					} else if (strpos($data['feed']['videourl'],".swf") !== false) {
						$data['videotype'] = "swf";
					}
				}
				$output[0] = 1;
				$output[1] = $this->renderPartial('/site/feeddetails',array("data"=>$data),true);
			}
		}
		echo json_encode($output);
	}

	public function actionTestIg() {

		$url = "https://api.instagram.com/v1/tags/soccer/media/recent?access_token=29089265.1f73a06.b86d89c21ae14da3a8fb438c3f85d81c";
		$response = Functions::fetchUrl($url);
		$content = json_decode($response, true);
		print_r($content);
		exit;
		$q = "john";
		$url = "https://api.instagram.com/v1/users/search?q=".$q."&access_token=".Yii::app()->params['igaccesstoken'];
		$response = Functions::fetchUrl($url);
		$content = json_decode($response, true);
		if($content['meta']['code']==200) {
			foreach($content['data'] AS $iguserdata) {
				print_r($iguserdata);
			}
		}
		
		if ($code == 200) {
			$twcontent = json_decode($tmhOAuth->response['response'], true);
			$gottweet=0;
		}
		print_r($response);
		$response = @file_get_contents($url);
		if($response === FALSE) {
			// $output['error'] = Yii::app()->params['instagramErrorMessage'];
		} else {
			foreach(json_decode($response)->data as $item) {
				if($item->username==$q) {
					print_r($item);
					break;
				}
			}
		}

		print_r($response);
	}


	public function actionTestFeedTr() {
		$q = "watermelon";
	}

	public function actionTestFeedIg() {
		// $q = "watermelon";
		// $mongocon = Yii::app()->mongodb->getConnection();
		// Yii::app()->mongodb->selectDB('pond_onnLRnH1r1k');
		// echo "success";
		$mongo = Yii::app()->mongodb;
		$cid = "ronny";
		$mongo->selectDB('pond_'.$cid);
		$campaign = Campaign::model()->findByAttributes(array("cid"=>$cid));
		$campaignrules = Campaignrule::model()->findAllByAttributes(array("campaignid"=>$campaign['id'],"status"=>1));
		foreach($campaignrules AS $campaignrule) {
			Functions::getFeeds($campaignrule);
		}
		exit;
	}

	public function actionTestFeedPt() {
		$q = "watermelon";

	}

	public function actionTestFeedYt() {
		$q = "watermelon";
		$ytdata = Functions::fetchUrl("https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=50&q=".$q."&key=".Yii::app()->params['googleapikey']);
		$ytcontent = json_decode($ytdata, true);
		print_r($ytcontent);
	}

	public function actionTestFeedTw() {


		$mongo = Yii::app()->mongodb;
		$cid = "ronny";
		$mongo->selectDB('pond_'.$cid);
		$campaign = Campaign::model()->findByAttributes(array("cid"=>$cid));
		$campaignrules = Campaignrule::model()->findAllByAttributes(array("campaignid"=>$campaign['id'],"status"=>1));
		foreach($campaignrules AS $campaignrule) {
			Functions::getFeeds($campaignrule);
		}
		exit;


		$q = "soccer";

		$mongocon = Yii::app()->mongodb->getConnection();
		Yii::app()->mongodb->selectDB('pond_testtw');



		// $feedid = Feed::model()->findOne(array('feedid' => 571948595253010432));
		// print_r($feedid);exit;




		$tmhOAuth = new tmhOAuth(array(
			'consumer_key'		=> Yii::app()->params['twconsumer_key'],
			'consumer_secret'	=> Yii::app()->params['twconsumer_secret'],
			'user_token'		=> Yii::app()->params['twuser_token'],
			'user_secret'		=> Yii::app()->params['twuser_secret'],
		));

		$params = array();
		$params['include_entities'] = 1;
		$params['q'] = $q;
		$params['count'] = 20;
		$user=1;
		$params['screen_name'] = "clcaustralia";
		if($user) {
			$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/statuses/user_timeline.json'), $params);
		} else {
			$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/search/tweets.json'), $params);
		}

		if ($code == 200) {
			$twcontent = json_decode($tmhOAuth->response['response'], true);
			if(isset($twcontent['statuses'])) {
				$tweets = $twcontent['statuses'];
			} else {
				$tweets = $twcontent;
			}

    // $twcontent[search_metadata] => Array
    //     (
    //         [completed_in] => 0.027
    //         [max_id] => 529835913677778944
    //         [max_id_str] => 529835913677778944
    //         [next_results] => ?max_id=529835537092186111&q=watermelon&count=20&include_entities=1
    //         [query] => watermelon
    //         [refresh_url] => ?since_id=529835913677778944&q=watermelon&include_entities=1
    //         [count] => 20
    //         [since_id] => 0
    //         [since_id_str] => 0
    //     )

			foreach ($tweets as $feeddata) {
				// print_r($feeddata);
				// echo "<BR><BR>\n\n";
				$ismedia=0;
				if(isset($feeddata['entities'])
					&& isset($feeddata['entities']['media'])
					&& isset($feeddata['entities']['media'][0])
					&& isset($feeddata['entities']['media'][0]['type'])
					&& $feeddata['entities']['media'][0]['type']=="photo") {
						// if($campaignrule['isimage'] == 1) {
						$filter = 1;
					// }
					$ismedia = 1;
				} else {
					$filter=1;
				}
				if(!isset($feeddata['retweeted_status']) &&
					!(strpos($feeddata['text'],"RT") === 0) &&
					!Feed::model()->findOne(array('feedid' => $feeddata['id_str'])) && $filter == 1) {

					$newfeed = new Feed;
					$newfeed['feedid'] = $feeddata['id'];
					$newfeed['feedsource'] = "tw";
					$newfeed['userid'] = $feeddata['user']['id'];
					$newfeed['username'] = $feeddata['user']['screen_name'];
					$newfeed['userimgurl'] = str_replace("_normal","_mini",$feeddata['user']['profile_image_url_https']);
					$newfeed['link'] = "https://twitter.com/".$newfeed['username']."/status/".$newfeed['feedid'];//$feeddata->link;
					$newfeed['desc'] = $feeddata['text'];

					if($ismedia) {
						$newfeed['imgurl'] = $feeddata['entities']['media'][0]['media_url_https'] . ":small";
						$newfeed['largeimgurl'] = $feeddata['entities']['media'][0]['media_url_https'];
						$newfeed['imgw'] = (int)$feeddata['entities']['media'][0]['sizes']['small']['w'];
						$newfeed['imgh'] = (int)$feeddata['entities']['media'][0]['sizes']['small']['h'];
					}
					$newfeed['time'] = strtotime($feeddata['created_at']);
					$newfeed['status'] = 1;
					if(!$newfeed->save()) {
						print_r($newfeed->errors);
					} else {
						print_r($newfeed['id']);
					}
				}
			}
		}
	}



	public function actionTestFeedFb() {

		// $cr = new Campaignrule;
	 //    $cr['public'] = 1;
	 //    $cr['istext'] = 1;
	 //    $cr['isimage'] = 1;
	 //    $cr['isvideo'] = 0;
	 //    $cr['status'] = 1;
	 //    $cr['type'] = "fb";
	 //    $cr['campaignid'] = 11;
	 //    $cr['starttime'] = 1416782700;
	 //    $cr['allowtag'] = "soccer";
	 //    $cr->save();
	 //    exit;

		// $imgurl = "https://fbcdn-sphotos-f-a.akamaihd.net/hphotos-ak-xfa1/v/t1.0-9/10733966_10152346712131403_7785843538431829191_n.jpg?oh=d2abbd563f0ce4e580054b4272cdb3fa&oe=54E73B96&__gda__=1424162939_666f031bf090ee395efd46008c5eb0ac";
		// $imgdata = file_get_contents($imgurl);
		// $size = getimagesize($imgurl);
		// print_r($size);
		// $size = getimagesize($imgdata);
		// print_r($soze);
		// exit;
		// if(!Yii::app()->user->getState("mongo")) {
		// 	// $mongo = Yii::app()->mongodb->getConnection();
		// 	$mongo = Yii::app()->mongodb;
		// 	Yii::app()->user->setState("mongo",$mongo);
		// }
		$mongo = Yii::app()->mongodb;
		$cid = "clc";
		$mongo->selectDB('pond_'.$cid);
		$campaign = Campaign::model()->findByAttributes(array("cid"=>$cid));
		$campaignrules = Campaignrule::model()->findAllByAttributes(array("campaignid"=>$campaign['id'],"status"=>1));
		foreach($campaignrules AS $campaignrule) {
			Functions::getFeeds($campaignrule);
		}
		exit;

		// exit;
		// Yii::app()->mongodb->db = "pond_onnLRnH1r1k";
		// // print_r($mongo->db);
		// // print_r(Yii::app()->mongodb);
		// $feeds = Feed::model()->findAll();

		// foreach($feeds AS $feed) {
		// 	print_r($feed->attributes);
		// 	break;
		// // 	if($feed['videourl']) {
		// // 		$feed['videourl'] = $feed['link'];
		// // 		$feed->save();
		// // 	}
		// }
		// return;

		// $data['keyword'] = "scenery";
		
		// $app_id = Yii::app()->params["fbappid"];
		// $app_secret = Yii::app()->params["fbappsecret"];

		//Get Facebook search result
		//https://graph.facebook.com/search?q=save_the_reef&type=page
		//https://graph.facebook.com/search?q=watermelon&type=post&access_token=246290772075137|KI5_2e4Phbc3Qt-gupnmdvcyQyU&limit=10
			// $since = 1199145600; // 2008
			// $until = time();
			// $limit = 10;
			// $accessToken = Functions::fetchUrl("https://graph.facebook.com/oauth/access_token?type=client_cred&client_id=".$app_id."&client_secret=".$app_secret);
			// Yii::app()->user->setState("fbaccesstoken",$accessToken);
			// $urlFeed = "https://graph.facebook.com/search?q=".$data['keyword']."&type=post&".$accessToken;
			$urlFeed = "https://graph.facebook.com/110445717021/posts?access_token=246290772075137|KI5_2e4Phbc3Qt-gupnmdvcyQyU";

		echo "<!-- $urlFeed -->";
		$json_object = Functions::fetchUrl($urlFeed);
		// echo $json_object;
		// // print_r($json_object);
		// $json_object = str_replace("\u","&#x",$json_object);
		$feedarray = json_decode($json_object);
		$urls = array();
		if(isset($feedarray)) {
			if(!isset($feedarray->error)) {
				// $i=0;
				$mongocon = Yii::app()->mongodb->getConnection();
				Yii::app()->mongodb->selectDB('pond_deleteme');

				$i=0;
				foreach ( $feedarray->data as $key=>$feeddata ) {

					if(!Feed::model()->findOne(array('feedid' => $feeddata->id))
						&& (($feeddata->type=="photo" && isset($feeddata->from->category)) || $feeddata->type!="photo") // disable public photo by user due to Facebook disabling its feed

						) {
						echo $feeddata->id . "<BR><BR>";
						$imgdata = Functions::getFeedImages($feeddata,"fb");
						if( isset($imgdata) && isset($imgdata['imgurl'])
							&& !Feed::model()->findOne(array('imgurl' => $imgdata['imgurl']))) { // Prevent duplicated photo & video

							$newfeed = new Feed;
							$newfeed['feedid'] = $feeddata->id;
							$newfeed['feedsource'] = "fb";
							$newfeed['userid'] = $feeddata->from->id;
							$newfeed['username'] = $feeddata->from->name;
							$newfeed['userimgurl'] = "https://graph.facebook.com/".$newfeed['userid']."/picture?type=small";
							if(isset($feeddata->link)) {
								$newfeed['link'] = $feeddata->link;
							}
							if(isset($feeddata->message)) {
								$newfeed['desc'] = $feeddata->message;
							} else if(isset($feeddata->description)) {
								$newfeed['desc'] = $feeddata->description;
							} else if(isset($feeddata->name)) {
								$newfeed['desc'] = $feeddata->name;
							} else {
								$newfeed['desc'] = "";
							}

							$newfeed['imgurl'] = $imgdata['imgurl'];
							if(isset($imgdata['largeimgurl'])) {
								$newfeed['largeimgurl'] = $imgdata['largeimgurl'];
							} else {
								$newfeed['largeimgurl'] = $newfeed['imgurl'];
							}

							$size = getimagesize($newfeed['imgurl']);
							$newfeed['imgw'] = (int)$size[0];
							$newfeed['imgh'] = (int)$size[1];

							$newfeed['time'] = strtotime($feeddata->updated_time);
							if($feeddata->type=="video") {
								$newfeed['videourl'] = $feeddata->source;
							}
							$newfeed['status'] = 1;
							if(!$newfeed->save()) {
								print_r($newfeed->errors);
							}
						}
					} else {

					}
					// if($i==0) { break; }
					$i++;
				}
			}
		}
/*
		1 feed id
		2 feed type (fb, tw, ig, etc)
		3 publisher
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
	}



	public function actionTestJS() {
		$data['tpl'] = "/site/testjs";
		$data['activemenu'] = "billing";
		$this->render('indexLoginRegister',array("data"=>$data));
	}

	public function actionTest() {

		//


		// $fbuserdata = Functions::fetchUrl("http://graph.facebook.com/".$_GET['u']);
		// $fbuser = json_decode($fbuserdata, true);
		// if(isset($fbuser['error'])) {
		// 	echo "error<BR>\n";
		// 	print_r($fbuser);
		// } else {
		// 	print_r($fbuser);
		// }

		
		// exit;


		// $campaign = Campaign::model()->findByAttributes(array("id"=>7));
		// print_r($campaign->attributes);
		// echo Functions::generateRandomCid();
		// exit;
		$mongocon = Yii::app()->mongodb->getConnection();
		// print_r($mongocon);
		// $mongo = Yii::app()->mongodb->getConnection();
		// Yii::app()->mongodb->createCollection("testhello1",array("name"=>"mydatabase"));

		// print_r(Yii::app()->mongodb->database);
		// $mongo->createCollection("test1");
		// Yii::app()->mongodb->getConnection()->createCollection("test2");
		// Yii::app()->mongodb->selectDB('mydatabase')->createCollection('test');
		Yii::app()->mongodb->selectDB('mydatabase');
		// Yii::app()->mongodb->selectDB('mydatabase')->insert(array("var1"=>"one"));
		// $test = new Test;
		// $test['id'] = 3;
		// $test['name'] = "myname three";
		// $test['status'] = 1;
		// $test->save();
		// print_r($savedata);
		// echo "<br>errors: ";
		// print_r($test->errors);
		// $_id = 1;


		// $c = new EMongoCriteria(array(
		// 	'condition' => array('id'=>'2'),
		// 	'limit' => 10
		// ));
		// $test = Test::model()->find(array(),array('id'=>1));
		$test = Test::model()->findOne(array('id' => 2));
		var_dump($test->attributes);

		$test = Test::model()->findBy_id("544ef7cff3138786b9d63af2");
		var_dump($test->attributes);

		echo "<BR><BR>";
		foreach(Test::model()->find(['id'  => ['$gte' => 2]]) as $doc){
			var_dump($doc->attributes);
		}

	}

	public function actionNotified() {
		if(isset($_POST)) {
			$data = $_POST;

// $data['mc_gross']="0.01";
// $data['protection_eligibility']="Ineligible";
// $data['address_status']="unconfirmed";
// $data['payer_id']="ZSWUB4S9HU6X8";
// $data['address_street']="PO Box 910";
// $data['payment_date']="22:07:15 Oct 23, 2014 PDT";
// $data['payment_status']="Completed";
// $data['charset']="windows-1252";
// $data['address_zip']="2046";
// $data['first_name']="Reginal";
// $data['mc_fee']="0.01";
// $data['address_country_code']="AU";
// $data['address_name']="Insolvency Appointments";
// $data['notify_version']="3.8";
// $data['subscr_id']="I-KFWP4TRA34GM";
// $data['custom']="1";
// $data['payer_status']="verified";
// $data['business']="dothebest1@gmail.com";
// $data['address_country']="Australia";
// $data['address_city']="Five Dock";
// $data['verify_sign']="AoIGoM5G8BWFF-SSj175eQkuPxYlAbYD1Lt4DOLCa4BgVEMrVPwiom5i";
// $data['payer_email']="sengkey@gmail.com";
// $data['txn_id']="4VP08682FJ543514X";
// $data['payment_type']="instant";
// $data['payer_business_name']="Insolvency Appointments";
// $data['btn_id']="88014990";
// $data['last_name']="Sengkey";
// $data['address_state']="New South Wales";
// $data['receiver_email']="dothebest1@gmail.com";
// $data['payment_fee']="";
// $data['receiver_id']="9L4NWXXG7W8YS";
// $data['txn_type']="subscr_payment";
// $data['item_name']="abcd Empat 5 (ID:4)";
// $data['mc_currency']="AUD";
// $data['item_number']="BPT";
// $data['residence_country']="AU";
// $data['transaction_subject']="abcd Empat 5 (ID:4)";
// $data['payment_gross']="";
// $data['ipn_track_id']="e7705c05efc2";


			$itemdata = explode(" (ID:", $data['item_name']);
			$campaignid = str_replace(")","",$itemdata[1]);

			// make sure to check the value of custom (plantypeid) is correct in regards to the plantype cost
			// just in case hacker change the 'custom' value in HTML page mc_gross
			$newplantypeid = $data['custom'];
			foreach(Yii::app()->params['plancost'] AS $plantypeid=>$plantypecost) {
				if($plantypecost == $data['mc_gross']) {
					if($plantypeid!=$data['custom']) {
						$newplantypeid = $plantypeid;

						// Hacking attempt reported
						Functions::sendmail("bugreport",array("subject"=>"HACKING ATTEMPT by changing plantypeid 'custom' variable value","error"=>$data));

					}
				}
			}

			if($campaign = Campaign::model()->findByPk($campaignid)) {
				$campaign['plantype'] = $newplantypeid;
				if($campaign->save()) {
					if(!$paymenthistory=PaymentHistory::model()->findByAttributes(array("campaignid"=>$campaignid,"transactionid"=>$data['txn_id']))) {
						$paymenthistory = new PaymentHistory;
					}
					$paymenthistory['campaignid'] = $campaignid;
					if($data['txn_id']) {
						$paymenthistory['transactionid'] = $data['txn_id'];
					}
					if($data['subscr_id']) {
						$paymenthistory['subscriberid'] = $data['subscr_id'];				
					}
					$paymenthistory['paidtime'] = time();
					if($paymenthistory->save()) {
						Functions::sendmail("notification",array("subject"=>"A new campaign has been paid - ID: ".$campaignid));
					} else {
						$errordata['attributes'] = $paymenthistory->attributes;
						$errordata['errors'] = $paymenthistory->errors;
						Functions::sendmail("bugreport",array("subject"=>"Payment history cannot be saved","error"=>$errordata));
					}
				} else {
					$errordata['attributes'] = $campaign->attributes;
					$errordata['errors'] = $campaign->errors;
					Functions::sendmail("bugreport",array("subject"=>"New Paid Campaign cannot be saved","error"=>$campaign));
				}
			} else {
				Functions::sendmail("bugreport",array("subject"=>"Paid Campaign ID not found","error"=>$data));
			}
		}
	}

	public function actionThankyou() {
		$data['tpl'] = "/site/thankyou";
		$data['activemenu'] = "billing";
		$this->render('index',array("data"=>$data));
	}

	public function actionBilling() {
		$data['tpl'] = "/site/billing";
		$data['activemenu'] = "billing";
		$this->render('index',array("data"=>$data));
	}

	public function actionPaymentmethods() {
		$data['tpl'] = "/site/paymentmethods";
		$data['activemenu'] = "billing";
		$this->render('index',array("data"=>$data));
	}

	public function actionRegister() {
		if(!isset(Yii::app()->user->ID)) {
			$data['tpl'] = "/site/register";
			$model = new UserForm;
			if(isset($_POST['UserForm'])) {
				$model->attributes = $_POST['UserForm'];
				if($model->validate(array('email','password','iagree'))) {
					$newuser = new User;
					$newuser['email'] = $model['email'];
					$newuser['password'] = md5($model['password']);
					$newuser['token'] = substr(str_shuffle( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, 1).substr( md5( time() ), 1);
					$newuser['updated'] = time();
					if($newuser->save()) {
						// send activation email
						$mailparams = array();
						$mailparams['user'] = $newuser;
						Functions::sendmail("activate",$newuser);
						Yii::app()->user->ID = $newuser['email'];
						$this->redirect("activate");
					}
				} else {
					// print_r($model->errors);
				}
			}
			$data['model'] = $model;
			$this->render('indexLoginRegister',array("data"=>$data));
		} else {
			$this->redirect("dashboard");
		}
	}

	public function actionActivate() {
		if(Yii::app()->user->ID) {
			$user = User::model()->findByPk(Yii::app()->user->ID);
			if($user['status']==1) {
				$this->redirect("dashboard");
			}
			$data['tpl'] = "/site/activate";
			$data['resend'] = 0;
			if(isset($_POST) && isset($_POST['type']) && $_POST['type']=="resend") {
				$mailparams = array();
				$mailparams['user'] = $user;
				Functions::sendmail("activate",$user);
				Yii::app()->user->setState("resend", 1);
				$this->redirect("activate");
			}
			if(Yii::app()->user->getState("resend") == 1) {
				$data['resend'] = 1;
				Yii::app()->user->setState("resend", null);
			}
			$this->render('indexLoginRegister',array("data"=>$data));
		} else {
			$this->redirect("login");
		}
	}

	public function actionConfirm() {
		$data['tpl'] = "/site/confirm";
		if(isset($_GET['t'])) {
			if($user = User::model()->findByAttributes(array("token"=>$_GET['t'],"status"=>0))) {
				$user['status'] = 1;
				if($user->save()) {
					Yii::app()->user->ID = $user['email'];
					$this->redirect("dashboard");
				}
			}
		} else {
			$this->redirect("login");
		}
	}

	public function actionLogin()
	{
		if(Yii::app()->user->ID) {
			$user = User::model()->findByPk(Yii::app()->user->ID);
			$this->redirect("dashboard");
		}
		$data['tpl'] = "/site/login";
		$model = new UserForm;
		if(isset($_POST['UserForm'])) {
			$model->attributes = $_POST['UserForm'];
			if($model->validate(array('email','password','rememberme'))) {
				if($existinguser = User::model()->findByAttributes(array("email"=>$model['email'],"password"=>md5($model['password'])))) {
					Yii::app()->user->ID = $existinguser['id'];
					Yii::app()->user->setState('email', $existinguser['email']);
					if($existinguser['status']==0) { // not activated
						$this->redirect("activate");
					}
					if(Yii::app()->user->returnUrl) {
						$this->redirect(Yii::app()->user->returnUrl);
					} else {
						$this->redirect("dashboard");
					}
				}
			}
		}
		$data['model'] = $model;
		$this->render('indexLoginRegister',array("data"=>$data));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError() {
	    if($error=Yii::app()->errorHandler->error) {
	    	if(Yii::app()->request->isAjaxRequest) {
	    		echo $error['message'];
	    	} else {
	        	$this->render('error', $error);
	        }
	    }
	}

	/**
	 * Displays the login page
	 */
	// public function actionLogin()
	// {
	// 	$model=new LoginForm;

	// 	// if it is ajax validation request
	// 	if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
	// 	{
	// 		echo CActiveForm::validate($model);
	// 		Yii::app()->end();
	// 	}

	// 	// collect user input data
	// 	if(isset($_POST['LoginForm']))
	// 	{
	// 		$model->attributes=$_POST['LoginForm'];
	// 		// validate user input and redirect to the previous page if valid
	// 		if($model->validate() && $model->login())
	// 			$this->redirect(Yii::app()->user->returnUrl);
	// 	}
	// 	// display the login form
	// 	$this->render('login',array('model'=>$model));
	// }

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}