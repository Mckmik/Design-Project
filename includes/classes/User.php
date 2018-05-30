<?php 
class User {
	private $user;
	private $con;

	public function __construct($con, $user){
		$this->con = $con;
		$userDetailsQuery = mysqli_query($con, "SELECT * FROM users WHERE username='$user'");
		$this->user = mysqli_fetch_array($userDetailsQuery);

    }
	
	//Returns a given user's username
    public function getUsername() {
		return $this->user['username'];
    }
	
	//Returns a given user's first and last name concatenated
    public function getFirstAndLastName() {
		$username = $this->user['username'];
		$query = mysqli_query($this->con, "SELECT first_name, last_name FROM users WHERE username='$username'");
		$row = mysqli_fetch_array($query);
		return $row['first_name'] . " " . $row['last_name'];

	}

	//Loads all service techs for use in a dropdown select input. Probably doesn't belong in User Class
	public function dropdownLoadServiceTechs(){
		$str="";
		$dataQuery = mysqli_query($this->con, "SELECT * FROM users WHERE is_tech='yes'");

		if(mysqli_num_rows($dataQuery) >0){

			while ($row = mysqli_fetch_array($dataQuery)) {
					$id = $row['user_id'];
					$firstName = $row['first_name'];
					$lastName = $row['last_name'];

					$str .= '<option value="'.$id.'">'.$firstName.' '.$lastName. '</option>';
			}

			echo $str;
					
		}
	}

	//generate random string for passwords
	public function generateRandomString($length = 10) {
    	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$charactersLength = strlen($characters);
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    	}
    	return $randomString;
	}



}

?>