<?php 
class Functions {
	public static function sendmail($type, $mailparams) {
		$ccc = new CController('mail');
		if(isset($type)) {
			if($type=="activate" && isset($mailparams['user'])) {

				$user = $mailparams['user'];
				$data['token'] = $user['token'];
				$to = $user['email'];
				$subject = "Action required: Activate your account";
				$data['tpl'] = "/site/activatemail";
				$data['nofooterlinks'] = 1;
				$message = $ccc->renderPartial('/site/emailtemplate',array("data"=>$data),true);
				$headers  = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=utf-8\r\n";
				$headers .= "From: ".Yii::app()->params['nameEmail']. "<".Yii::app()->params['noreplyEmail'].">\r\n";
				mail($to, $subject, $message, $headers);

			} else if($type="bugreport" && isset($mailparams['error']) && isset($mailparams['subject'])) {

				$data['error'] = $mailparams['error'];
				$to = Yii::app()->params['adminEmail'];
				$subject = "Beepond Bug report: " . $mailparams['subject'];
				$data['tpl'] = "/site/bugreportemail";
				$data['nofooterlinks'] = 1;
				$message = $ccc->renderPartial('/site/emailtemplate',array("data"=>$data),true);
				$headers  = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=utf-8\r\n";
				$headers .= "From: ".Yii::app()->params['nameEmail']. "<".Yii::app()->params['noreplyEmail'].">\r\n";
				mail($to, $subject, $message, $headers);

			} else if($type="notification" && isset($mailparams['subject'])) {

				$to = Yii::app()->params['adminEmail'];
				$subject = $mailparams['subject'];
				$data['tpl'] = "/site/blankmail";
				$data['message'] = "";
				$data['nofooterlinks'] = 1;
				$message = $ccc->renderPartial('/site/emailtemplate',array("data"=>$data),true);
				$headers  = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=utf-8\r\n";
				$headers .= "From: ".Yii::app()->params['nameEmail']. "<".Yii::app()->params['noreplyEmail'].">\r\n";
				mail($to, $subject, $message, $headers);				

			}
		}
	}
	public static function sendForgotPassword($user) {
		$forgotpassword = CustomerForgotpassword::model()->getValidModelByCustomerId($user['id']);
		if(!$forgotpassword) {
			$forgotpassword = new CustomerForgotpassword;
			$forgotpassword['customerid'] = $user['id'];
			$forgotpassword['token'] = Functions::generateRandomString();
			$forgotpassword['expirytime'] = strtotime('30 minutes');
			$forgotpassword->save();
		}
		$data['forgotpassword'] = $forgotpassword;
		$data['firstname'] = $user['firstname'];
		$data['lastname'] = $user['lastname'];
		$data['expirytime'] = date("d M Y H:i",$forgotpassword['expirytime']);
		$to = $user['firstname']." " . $user['lastname'] . " <".$user['email'].">";
		$subject = "Reset your ".Yii::app()->name." password";

		$ccc = new CController('forgotpassword');
		$message = $ccc->renderPartial('/site/forgotpasswordmail',array("data"=>$data),true);
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";
		$headers .= "From: ".Yii::app()->params['nameEmail']. "<".Yii::app()->params['noreplyEmail'].">\r\n";
		mail($to, $subject, $message, $headers);
	}
	public static function sendOrderConfirmation($customer,$order,$cart) {
		$data['customer'] = $customer;
		$data['order'] = $order;
		$data['cart'] = $cart;

		$to = $customer['firstname']." " . $customer['lastname'] . " <".$customer['email'].">";
		$subject = "Order Confirmation #".$order['id']." | bettacut.com.au";

		$ccc = new CController('orderconfirmation');
		$message = $ccc->renderPartial('/site/orderconfirmmail',array("data"=>$data),true);
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";
		$headers .= "From: ".Yii::app()->params['nameEmail']. "<".Yii::app()->params['mainEmail'].">\r\n";
		$headers .= "Bcc: ".Yii::app()->params['bccEmail'];
		mail($to, $subject, $message, $headers);
	}
	public static function getFeeds($campaignrule) {
		if(!$campaignrule) {
			return;
		}
		if($campaignrule['type']=="fb") {
			if($campaignrule['public']==1) { // get public post
				Functions::getFeedsFromFb($campaignrule);
			} else {
				$users = explode(",", $campaignrule['allowuser']);
				foreach($users AS $user) {
					$user = trim($user);
					Functions::getFeedsFromFb($campaignrule,$user);
				}
			}
		} else if($campaignrule['type']=="tw") {
			if($campaignrule['public']==1) { // get public post
				Functions::getFeedsFromTw($campaignrule);
			} else {
				$users = explode(",", $campaignrule['allowuser']);
				foreach($users AS $user) {
					$user = trim($user);
					Functions::getFeedsFromTw($campaignrule,$user);
				}
			}
		} else if($campaignrule['type']=="ig") {
			if($campaignrule['public']==1) { // get public post
				Functions::getFeedsFromIg($campaignrule);
			} else {
				$users = explode(",", $campaignrule['allowuser']);
				foreach($users AS $user) {
					$user = trim($user);
					Functions::getFeedsFromIg($campaignrule,$user);
				}
			}
		}
	}
	public static function getFeedsFromIg($campaignrule,$user=null) {
		$next_url = "";
		if($user) {
			$next_url = "https://api.instagram.com/v1/users/".$user."/media/recent/?access_token=".Yii::app()->params['igaccesstoken'];;
			if($campaignrule['starttime'] && $campaignrule['starttime']>0) {
				$next_url .= "&min_timestamp=".$campaignrule['starttime'];
			}
			if($campaignrule['endtime'] && $campaignrule['endtime']>0) {
				$next_url .= "&max_timestamp=".$campaignrule['endtime'];
			}
		} else {
			if($campaignrule['allowtag']) {
				$allowtag = explode(", ",$campaignrule['allowtag']);
				$next_url = "https://api.instagram.com/v1/tags/".$allowtag[0]."/media/recent?access_token=".Yii::app()->params['igaccesstoken'];
			}
		}
		$breakflag=0;
		do {
			$response = Functions::fetchUrl($next_url);
			$content = json_decode($response, true);
			if(isset($content['meta']['code']) && $content['meta']['code']==200) {
				foreach($content['data'] AS $feeddata) {
					if($campaignrule['starttime'] && $campaignrule['starttime'] >= $feeddata['created_time']) {
						$breakflag=1;
						break;
					}
					if($campaignrule['endtime'] && $campaignrule['endtime'] <= $feeddata['created_time']) {
						$breakflag=1;
						break;
					}
					$filter = 0;
					if(($feeddata['type']=="image" && $campaignrule['isimage']==1) ||
						$feeddata['type']=="video" && $campaignrule['isvideo']==1) {
						$filter = 1;
					}
					$tagflag=0;
					$includetags = $campaignrule['allowtag'] ? explode(", ",$campaignrule['allowtag']) : array();
					$excludetags = $campaignrule['blocktag'] ? explode(", ",$campaignrule['blocktag']) : array();

					foreach($includetags AS $includetag) {
						if(!in_array($includetag, $feeddata['tags'])) {
							$tagflag=1;
							break;
						}
					}
					if(!$tagflag) {
						foreach($excludetags AS $excludetag) {
							if(in_array($excludetag, $feeddata['tags'])) {
								$tagflag=2;
								break;
							}
						}
					}

					if(!Feed::model()->findOne(array('feedid' => $feeddata['id'])) && $tagflag==0 && $filter==1) {
						$newfeed = new Feed;
						$newfeed['feedid'] = $feeddata['id'];
						$newfeed['feedsource'] = "ig";
						$newfeed['userid'] = $feeddata['user']['id'];
						$newfeed['username'] = $feeddata['user']['username'];
						$newfeed['userimgurl'] = $feeddata['user']['profile_picture'];
						$newfeed['link'] = $feeddata['link'];
						$newfeed['desc'] = $feeddata['caption']['text'];

						$newfeed['imgurl'] = $feeddata['images']['low_resolution']['url'];
						$newfeed['largeimgurl'] = $feeddata['images']['standard_resolution']['url'];
						$newfeed['imgw'] = (int)$feeddata['images']['low_resolution']['width'];
						$newfeed['imgh'] = (int)$feeddata['images']['low_resolution']['height'];

						if($feeddata['type']=="video") {
							$newfeed['videourl'] = $feeddata['videos']['standard_resolution']['url'];
						}

						$newfeed['time'] = $feeddata['created_time'];
						$newfeed['status'] = 1;

						if(!$newfeed->save()) {
							// print_r($newfeed->errors);
							// Functions::sendmail();
						}
					}
				}
				if(isset($content['pagination']['next_url'])) {
					$next_url = $content['pagination']['next_url'];
				} else {
					$breakflag = 1;
				}
			} else {
				// send report
				$data = $content['meta'];
				Functions::sendmail("bugreport",array("subject"=>__FUNCTION__ . ": " . $data['error_type'] . " " . $data['code'] . " " . $data['error_message'],"error"=>$data));
				$breakflag = 1;
				exit;

			}
		} while($breakflag==0);
		return;
	}
	public static function getFeedsFromTw($campaignrule,$user=null) {
		$tmhOAuth = new tmhOAuth(array(
			'consumer_key'		=> Yii::app()->params['twconsumer_key'],
			'consumer_secret'	=> Yii::app()->params['twconsumer_secret'],
			'user_token'		=> Yii::app()->params['twuser_token'],
			'user_secret'		=> Yii::app()->params['twuser_secret'],
		));
		$params = array();
		$params['include_entities'] = 1;
		if($campaignrule['allowtag']) {
			$params['q'] = $campaignrule['allowtag'];
		}
		$params['count'] = Yii::app()->params['twfeedpercall'];
		$breakflag = 0;
		do {
			if($user) {
				$params['user_id'] = $user;
				$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/statuses/user_timeline.json'), $params);
			} else {
				$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/search/tweets.json'), $params);
			}
			if($code == 200) {
				$twcontent = json_decode($tmhOAuth->response['response'], true);
				if(isset($twcontent['statuses'])) {
					$tweets = $twcontent['statuses'];
				} else {
					$tweets = $twcontent;
				}

				foreach ($tweets AS $feeddata) {
					$filter = 0;
					if($campaignrule['starttime'] && $campaignrule['starttime'] >= strtotime($feeddata['created_at'])) {
						$breakflag=1;
						break;
					}

					$params['max_id'] = $feeddata['id'];

					$ismedia=0;
					if(isset($feeddata['entities'])
						&& isset($feeddata['entities']['media'])
						&& isset($feeddata['entities']['media'][0])
						&& isset($feeddata['entities']['media'][0]['type'])) {
						if($feeddata['entities']['media'][0]['type']=="photo" && $campaignrule['isimage'] == 1) {
							$filter = 1;
						} else if($feeddata['entities']['media'][0]['type']=="video" && $campaignrule['isvideo'] == 1) {
							$filter = 1;
						}
						$ismedia = 1;
					} else if($campaignrule['istext'] == 1) {
						$filter = 1;
					}

					if($campaignrule['endtime'] && $campaignrule['endtime'] < strtotime($feeddata['created_at'])) {
						$filter = 0;
					}

					$tagflag = 0;
					$includetags = $campaignrule['allowtag'] ? explode(", ",$campaignrule['allowtag']) : array();
					$excludetags = $campaignrule['blocktag'] ? explode(", ",$campaignrule['blocktag']) : array();

					// Filter out the alphanumeric and emptyspace characters from the tweet
					// Lowercase it
					// Split into array of words
					$tweetwords = explode(" ",preg_replace("/[^a-zA-Z0-9 ]+/", "", strtolower($feeddata['text'])));

					foreach($includetags AS $includetag) {
						if(!in_array($includetag, $tweetwords)) {
							$tagflag=1;
							break;
						}
					}
					if(!$tagflag) {
						foreach($excludetags AS $excludetag) {
							if(in_array($excludetag, $tweetwords)) {
								$tagflag=2;
								break;
							}
						}
					}

					if(!isset($feeddata['retweeted_status']) &&
						!(strpos($feeddata['text'],"RT") === 0) &&
						!Feed::model()->findOne(array('feedid' => $feeddata['id'])) && 
						$filter == 1 && $tagflag==0) {

						$newfeed = new Feed;
						$newfeed['feedid'] = $feeddata['id'];
						$newfeed['feedsource'] = "tw";
						$newfeed['userid'] = $feeddata['user']['id'];
						$newfeed['username'] = $feeddata['user']['screen_name'];
						$newfeed['userimgurl'] = str_replace("_normal","_mini",$feeddata['user']['profile_image_url_https']);
						$newfeed['link'] = "https://twitter.com/".$newfeed['username']."/status/".$newfeed['feedid'];
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
							// print_r($newfeed->errors);
							// Functions::sendmail();
						}
					}
				}
			} else {
				$data = $campaignrule->attributes;
				Functions::sendmail("bugreport",array("subject"=>__FUNCTION__ . ": code: " . $code,"error"=>$data));
				$breakflag = 1;
				exit;
			}
		} while($breakflag==0);
		return;
	}

	public static function getFeedsFromFb($campaignrule,$user=null) {
		$app_id = Yii::app()->params["fbappid"];
		$app_secret = Yii::app()->params["fbappsecret"];
		if(!Yii::app()->user->getState("fbaccesstoken")) {
			$fbaccessToken = Functions::fetchUrl("https://graph.facebook.com/oauth/access_token?type=client_cred&client_id=".$app_id."&client_secret=".$app_secret);
			Yii::app()->user->setState("fbaccesstoken",$fbaccessToken);
		}

		$since = "";
		if(isset($campaignrule['starttime']) && $campaignrule['starttime']) {
			$since = "&since=".$campaignrule['starttime'];
		}

		$until = "";
		if(isset($campaignrule['endtime']) && $campaignrule['endtime']) {
			$until = "&until=".$campaignrule['endtime'];
		}

		if($user) {
			$urlFeed = "https://graph.facebook.com/".$user."/posts?".Yii::app()->user->getState("fbaccesstoken").$since.$until;
		} else {
			$urlFeed = "https://graph.facebook.com/search?q=".$campaignrule['allowtag']."&type=post&".Yii::app()->user->getState("fbaccesstoken").$since.$until;
		}

		$urlFeedFromFb="";
		$outoftimerangeflag=0;
		do {
			$json_object = Functions::fetchUrl($urlFeed);
			$feedarray = json_decode($json_object);
			if(isset($feedarray->error)) {
				$data = $feedarray->error;
				Functions::sendmail("bugreport",array("subject"=>"getFeedsFromFb: " . $feedarray->error->message,"error"=>$data));
				$outoftimerangeflag = 1;
				exit;
			} else {
				$urls = array();
				if(isset($feedarray)) {
					if(!isset($feedarray->error) && $feedarray->data) {
						foreach ( $feedarray->data as $key=>$feeddata ) {

							if($campaignrule['starttime'] && $campaignrule['starttime'] >= strtotime($feeddata->created_time)) {
								$outoftimerangeflag=1;
								break;
							}
							if($campaignrule['endtime'] && $campaignrule['endtime'] <= strtotime($feeddata->created_time)) {
								$outoftimerangeflag=1;
								break;
							}
							$filter = 0;
							$ismedia = 0;
							if($feeddata->type=="photo") {
								if($campaignrule['isimage'] == 1 && isset($feeddata->from->category)) {
									$filter = 1;
								}
								$ismedia = 1;
							} else if($feeddata->type=="video") {
								if($campaignrule['isvideo'] == 1) {
									$filter = 1;
								}
								$ismedia = 1;
							} else {
								$filter=1;
							}

							if(!Feed::model()->findOne(array('feedid' => $feeddata->id)) && $filter == 1) {
								$imgdata = Functions::getFeedImages($feeddata,"fb");
								if( (isset($imgdata) && isset($imgdata['imgurl']) && !Feed::model()->findOne(array('imgurl' => $imgdata['imgurl'])) && $ismedia) // Prevent duplicated photo & video
								 || ($campaignrule['istext'] && !$ismedia)) {

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
									}

									if($ismedia) {
										$newfeed['imgurl'] = $imgdata['imgurl'];
										if(isset($imgdata['largeimgurl'])) {
											$newfeed['largeimgurl'] = $imgdata['largeimgurl'];
										} else {
											$newfeed['largeimgurl'] = $newfeed['imgurl'];
										}
										try {
											$size = getimagesize($newfeed['imgurl']);
											$newfeed['imgw'] = (int)$size[0];
											$newfeed['imgh'] = (int)$size[1];

										} catch (Exception $e) {
											// mail error to admin

										}

										if($feeddata->type=="video") {
											$newfeed['videourl'] = $feeddata->source;
										}

									}
									$newfeed['time'] = strtotime($feeddata->updated_time);
									$newfeed['status'] = 1;
									if(!$newfeed->save()) {
										// print_r($newfeed->errors);
									}
								}
							} else {

							}
						}
						if(isset($feedarray->paging)) {
							$urlFeed = $feedarray->paging->next;
						} else {
							$outoftimerangeflag=0;
						}
					} else {
						$outoftimerangeflag=0;
					}
				} else {
					$outoftimerangeflag=0;
				}
			}
		} while($outoftimerangeflag==0);
	}

	public static function getFeedImages($feeddata,$source) {
		$urls = array();
		if($source=="fb") {
			// print_r($feeddata);

			// Video

			if($feeddata->type=="video") {
				$parsedurlbysource = parse_url($feeddata->source);
				$parsedurlbylink = parse_url($feeddata->link);
				if (strpos($parsedurlbylink['host'],'facebook.com') !== false) { // Facebook
					$url = "https://graph.facebook.com/".$feeddata->object_id."?".Yii::app()->user->getState("fbaccesstoken");
					$userjsondata = Functions::fetchUrl($url);
					$userdata = json_decode($userjsondata,true);
					if(!isset($userdata['error'])) {
						foreach($userdata['format'] AS $fbimage) {
							if($fbimage['width'] == 480) {
								$urls['imgurl'] = $fbimage['picture'];
							} else if($fbimage['width'] == 720) {
								$urls['largeimgurl'] = $fbimage['picture'];
							}
						}
						if(!isset($urls['imgurl'])) {
							$formatnumber = count($userdata['format'])-1;
							$urls['imgurl'] = $userdata['format'][$formatnumber]['picture'];
							$urls['largeimgurl'] = $urls['imgurl'];
						} else {
						}
					} else {
						// mail
					}
				} else if (strpos($parsedurlbysource['host'],'youtube.com') !== false) { // Youtube
					$videoid = Functions::getVideoIdFromUrl($feeddata->source);
					$urls['imgurl'] = "http://img.youtube.com/vi/".$videoid."/hqdefault.jpg";
					$urls['largeimgurl'] = "http://img.youtube.com/vi/".$videoid."/sddefault.jpg";
				} else {
					$urls['imgurl'] = $feeddata->picture;
					$urls['largeimgurl'] = $feeddata->picture;
				}

			// Photo

			} else if($feeddata->type=="photo") {
				$url = "https://graph.facebook.com/".$feeddata->object_id."/picture/?".Yii::app()->user->getState("fbaccesstoken");
				$urls['imgurl'] = Functions::getRedirectUrl($url);
				$urls['largeimgurl'] = $urls['imgurl'];
			}
		}
		// if($type=="video") {
		// 	if($source=="fb") {

		// 	}
		// } else if($type=="photo") {
		// 	if($source=="fb") {
		// 		$url = "https://graph.facebook.com/".$objectid."?access_token=".Yii::app()->params['fbpublicaccesstoken'];
		// 		$userjsondata = Functions::fetchUrl($url);
		// 		$userdata = json_decode($userjsondata,true);
		// 		if(!isset($userdata['error'])) {
		// 			foreach($userdata['images'] AS $fbimage) {
		// 				if($fbimage['width'] == $userdata['width']) {
		// 					return $fbimage['source'];
		// 				}
		// 			}
		// 		} else {
		// 			// mail
		// 		}
		// 	}
		// }
		return $urls;
	}
	public static function getVideoIdFromUrl($url) {
		$parts = parse_url($url);
		if(isset($parts['query'])) {
			parse_str($parts['query'], $qs);
			if(isset($qs['v'])) {
				return $qs['v'];
			}else if(isset($qs['vi'])) {
				return $qs['vi'];
			}
		}
		if(isset($parts['path'])){
			$path = explode('/', trim($parts['path'], '/'));
			return $path[count($path)-1];
		}
		return false;
	}
	public static function hideEmail($email) {
		if(filter_var( $email, FILTER_VALIDATE_EMAIL )) {
			$mail_segments = explode("@", $email);
			$mail_segments[0] = substr($mail_segments[0],0,1) . str_repeat("*", strlen($mail_segments[0])-2) . substr($mail_segments[0],-1);

			return implode("@", $mail_segments);
		}
		return $email;
	}
	public static function getRuleStatement($campaignrule) {
		$statement = "";
		if($campaignrule['public']) {
			$statement .= "Getting data from <b>all public posts</b>";
		} else {
			$statement .= "Getting data";
			if(isset($campaignrule['allowuser']) && $campaignrule['allowuser']) {
				$statement .= " from<b>";
				$userids = explode(",", $campaignrule['allowuser']);
				$i=0;
				foreach($userids AS $userid) {
					$userid = trim($userid);
					$userdata = Functions::getSocialUserData($campaignrule['type'], $userid);
					if(isset($userdata['text'])) {
						$statement .= " @" . Functions::setSocialUserUrl($campaignrule['type'],$userdata);
						if($i+1<count($userids)) {
							$statement .= ", ";
						}
					}
					$i++;
				}
				$statement .= "</b>";
			}
			if(isset($campaignrule['blockuser']) && $campaignrule['blockuser']) {
				$statement .= " excluding from<b>";
				$userids = explode(",", $campaignrule['blockuser']);
				$i=0;
				foreach($userids AS $userid) {
					$userid = trim($userid);
					$userdata = Functions::getSocialUserData($campaignrule['type'], $userid);
					if(isset($userdata['text'])) {
						$statement .= " @" . Functions::setSocialUserUrl($campaignrule['type'],$userdata);
						if($i+1<count($userids)) {
							$statement .= ", ";
						}
					}
					$i++;
				}
				$statement .= "</b>";
			}
		}
		if($campaignrule['starttime'] || $campaignrule['endtime']) {
			if($campaignrule['starttime']) {
				$statement .= " from <b>" . date("d M Y H:i",$campaignrule['starttime'])."</b>";
			}
			if($campaignrule['endtime']) {
				$statement .= " to <b>" . date("d M Y H:i",$campaignrule['endtime'])."</b>";
			}
		}
		$statement .= ". ";
		if(isset($campaignrule['allowtag']) && $campaignrule['allowtag']) {
			$statement .= "Included tags are <b>" . $campaignrule['allowtag']."</b>. ";
		}
		if(isset($campaignrule['blocktag']) && $campaignrule['blocktag']) {
			$statement .= "Excluded tags are <b>" . $campaignrule['blocktag']."</b>.";
		}
		$isstatement = "";
		if(isset($campaignrule['istext']) && $campaignrule['istext']) {
			$isstatement = "text";
		}
		if((isset($campaignrule['isimage']) && $campaignrule['isimage']) || (isset($campaignrule['isvideo']) && $campaignrule['isvideo'])) {
			if($campaignrule['isimage']) {
				if($isstatement) {
					$isstatement .= " and ";
				}

				$isstatement .= "images";
			}

			if($campaignrule['isvideo']) {
				if($isstatement) {
					$isstatement .= " and ";
				}
				$isstatement .= "videos";
			}
		}
		if($isstatement) {
			$isstatement = " Must <b>have " . $isstatement. "</b>.";
		}
		return $statement . $isstatement;
	}

	public static function getSocialUserData($sourcetype, $userid) {
		$user = array();
		$user['id'] = $userid;
		if($sourcetype=="fb") {
			$userjsondata = Functions::fetchUrl("http://graph.facebook.com/".$userid);
			$userdata = json_decode($userjsondata,true);
			if(!isset($userdata['error'])) {
				$user['text'] = $userdata['username'];
			} else {
				// error
			}
		} else if($sourcetype=="tw") {
			$tmhOAuth = new tmhOAuth(array(
				'consumer_key'		=> Yii::app()->params['twconsumer_key'],
				'consumer_secret'	=> Yii::app()->params['twconsumer_secret'],
				'user_token'		=> Yii::app()->params['twuser_token'],
				'user_secret'		=> Yii::app()->params['twuser_secret'],
			));

			$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/users/show.json'), array(
				'user_id'	=> $userid,
			));

			if ($code == 200) {
				$userdata = json_decode($tmhOAuth->response['response'], true);
				$user['text'] = $userdata['screen_name'];
			}
		} else if($sourcetype=="ig") {
			$url = "https://api.instagram.com/v1/users/".$userid."/?access_token=".Yii::app()->params['igaccesstoken'];
			$response = Functions::fetchUrl($url);
			$content = json_decode($response, true);
			if($content['meta']['code']==200) {
				$user['text'] = $content['data']['username'];
			} else {
				// error
			}
		}
		return $user;
	}
	public static function setSocialUserUrl($sourcetype, $user) {
		$urllink = "";
		if($sourcetype=="fb") {
			$url = "https://www.facebook.com/profile.php?id=".$user['id'];
		} else if($sourcetype=="tw") {
			$url = "https://twitter.com/".$user['text'];
		} else if($sourcetype=="ig") {
			$url = "http://www.instagram.com/".$user['text'];
		}
		$urllink = "<a href='".$url."' target='_blank'>".$user['text']."</a>";
		return $urllink;
	}
	public static function prettifyTags($str) {
		$tags = explode(",", $str);
		$tagstr = "";
		foreach($tags AS $tag) {
			if($tagstr) {
				$tagstr .= ", ";
			}
			$tagstr .= trim($tag);
		}
		return $tagstr;
	}
	public static function validateTags($tags,$user=0) {
		if(!$tags) {
			return 1;
		}
		$tagsdata = explode(",", $tags);
		$allowed = array(".", "-", "_");
		foreach($tagsdata AS $tag) {
			$tag = trim($tag);
			if(!$user) {
				if($tag[0] == "@" || $tag[0] == "#") {
					$tag = substr($tag, 1);
				}
			}
			if(!ctype_alnum(str_replace($allowed, "", $tag))) {
				return 0;
			}
		}
		return 1;
	}
	public static function fetchUrl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		// You may need to add the line below
		// curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		$feedData = curl_exec($ch);
		curl_close($ch); 
		return $feedData;
	}
	public static function getRedirectUrl($url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // follow redirects
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // set referer on redirect
		curl_exec($ch);
		$target = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		curl_close($ch);

		if ($target)
			return $target;

		return false;
	}

	public static function addhttp($url) {
		if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
			$url = "http://" . $url;
		}
		return $url;
	}
	public static function turnTo2Dec($number) {
		if(!$number) { $number = 0; }
		return number_format($number, 2, '.', ',');
	}
	public static function isValidDomain($domain) {
		if(stripos($domain, 'http://') === 0)
		{
			$domain = substr($domain, 7);
		} else if(stripos($domain, 'http://') === 0) {
			$domain = substr($domain, 8);
		}

		///Not even a single . this will eliminate things like abcd, since http://abcd is reported valid
		if(!substr_count($domain, '.'))
		{
			return false;
		}

		if(stripos($domain, 'www.') === 0)
		{
			$domain = substr($domain, 4); 
		}

		$again = 'http://' . $domain;
		return filter_var ($again, FILTER_VALIDATE_URL);
	}
	public static function generateRandomCid() {
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$string = "";
		$random_string_length = 11;
		for ($i = 0; $i < $random_string_length; $i++) {
			$string .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $string;
	}
}
?>