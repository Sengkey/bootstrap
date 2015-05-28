<?php
 
// this file must be stored in:
// protected/components/WebUser.php
 
class WebUser extends CWebUser {
 
	// Store model to not repeat query.
	private $_model;
	 
	// Return first name.
	// access it by Yii::app()->user->first_name
	function getUserName($id){
		if(isset($id)) {
			$user = $this->loadUser($id);
			return $user->username;
		}
		return false;
	}
	
	function getPassword($id){
		if(isset($id)) {
			$user = $this->loadUser($id);
			return $user->password;
		}
		return false;
	}

	function getEmail($id){
		if(isset($id)) {
			$user = $this->loadUser($id);
			return $user->email;
		}
		return false;
	}
	 
	// This is a function that checks the field 'role'
	// in the User model to be equal to 1, that means it's admin
	// access it by Yii::app()->user->isAdmin()
	function isGuest() {
		$user = $this->loadUser(Yii::app()->user->id);
		return intval($user['id']) == 0;
	}
	function isAdmin(){
		$user = $this->loadUser(Yii::app()->user->id);	
		return intval($user['id']) == 1; // ADMIN level is 1
	}
	function isStaff(){
		$user = $this->loadUser(Yii::app()->user->id);
		return intval($user['id']) > 1; // STAFF level
	}
	function isSuperAdmin(){
		$user = $this->loadUser(Yii::app()->user->id);	
		return intval($user['id']) == -1; // SUPERADMIN level is 9
	}
	 
	// Load user model.
	public function loadUser($id)
    {
        if(isset($id)) {
            $this->_model=Users::model()->findByAttributes(array('username'=>$id));
        }
        return $this->_model;
    }
}
?>