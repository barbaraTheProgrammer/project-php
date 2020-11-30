<?php 
	class User {
		private $id;
		private $name;
		private $username;
		private $email;
		//address
		private $street;
		private $suite;
		private $city;
		private $zipcode;
		//geo
		private $lat;
		private $lng;

		private $phone;
		private $website;
		//company
		private $companyName;
		private $catchPhrase;
		private $bs;
		

		//id
		public function setId($id) {
			$this->id = $id;
		}
		public function getId() {
			return $this->id;
		}

		//name
		public function setName($name) {
			$this->name = $name;
		}
		public function getName() {
			return $this->name;
		}

		//username
		public function setUsername($username) {
			$this->username = $username;
		}
		public function getUsername() {
			return $this->username;
		}

		//email
		public function setEmail($email) {
			$this->email = $email;
		}
		public function getEmail() {
			return $this->email;
		}

		//address
		//street
		public function setStreet($street) {
			$this->street = $street;
		}
		public function getStreet() {
			return $this->street;
		}

		//suite
		public function setSuite($suite) {
			$this->suite = $suite;
		}
		public function getSuite() {
			return $this->suite;
		}

		//city
		public function setCity($city) {
			$this->city = $city;
		}
		public function getCity() {
			return $this->city;
		}

		//zipcode
		public function setZipcode($zipcode) {
			$this->zipcode = $zipcode;
		}
		public function getZipcode() {
			return $this->zipcode;
		}

		//geo
		//lat
		public function setLat($lat) {
			$this->lat = $lat;
		}
		public function getLat() {
			return $this->lat;
		}

		//lng
		public function setLng($lng) {
			$this->lng = $lng;
		}
		public function getLng() {
			return $this->lng;
		}

		//phone
		public function setPhone($phone) {
			$this->phone = $phone;
		}
		public function getPhone() {
			return $this->phone;
		}

		//website
		public function setWebsite($website) {
			$this->website = $website;
		}
		public function getWebsite() {
			return $this->website;
		}

		//company
		//companyName
		public function setCompanyName($companyName) {
			$this->companyName = $companyName;
		}
		public function getCompanyName() {
			return $this->companyName;
		}

		//catchPhrase
		public function setCatchPhrase($catchPhrase) {
			$this->catchPhrase = $catchPhrase;
		}
		public function getCatchPhrase() {
			return $this->catchPhrase;
		}

		//bs
		public function setBs($bs) {
			$this->bs = $bs;
		}
		public function getBs() {
			return $this->bs;
		}

	}


	function getDomain($user) {
		/*function explode() returns arrey of strings cuted when "@"
		domain is secound element of arrey*/
		$domain = explode("@",$user->getEmail())[1];
		return $domain;
	}


	function getPersonData($user) {
		$personData = array(
			"id" => $user->getId(),
			"name" => $user->getName(),
			"username" => $user->getUsername(),
			"email" => $user->getEmail(),
			"street" => $user->getStreet(),
			"suite" => $user->getSuite(),
			"city" =>  $user->getCity(),
			"zipcode" =>  $user->getZipcode(),
			"lat" =>  $user->getLat(),
			"lng" =>  $user->getLng(),
			"phone" =>  $user->getPhone(),
			"website" =>  $user->getWebsite(),
			"companyName" => $user->getCompanyName(),
			"catchPhrase" =>  $user->getCatchPhrase(),
			"bs" =>  $user->getBs()
		);

		echo json_encode($personData)."<br><br>";

	}


	function returnValuesOfArray($array) 
	{ 
		return (array_values($array)); 
	}


	// Database methods

	function createDatabase() {
		$connection = mysqli_connect("localhost", "root", "");
		
		// Check connection
		if($connection === FALSE){
			die("ERROR: connection failed" . mysqli_connect_error());
		}
		
		$sql = "CREATE DATABASE IF NOT EXISTS result";
		if(mysqli_query($connection, $sql)){
			echo "Database created successfully."."<br>";
		} else{
			echo "ERROR: " . mysqli_error($connection);
		}
		
		// Close connection
		mysqli_close($connection);
	}


	function createTable() {
		$connection = mysqli_connect("localhost", "root", "", "result");
		
		// Check connection
		if($connection === FALSE){
			die("ERROR: connection failed" . mysqli_connect_error());
		}

		$sql = "CREATE TABLE IF NOT EXISTS useremail(
			email NVARCHAR(255) NOT NULL
		)";

		if(mysqli_query($connection, $sql)) {
			echo "Table created successfully."."<br>";
		}else {
			echo "ERROR: " . mysqli_error($connection);
		}

		// Close connection
		mysqli_close($connection);
	}


	function insertDataToTable($user) {
		$connection = mysqli_connect("localhost", "root", "", "result");
		
		// Check connection
		if($connection === FALSE){
			die("ERROR: connection failed" . mysqli_connect_error());
		}

		$email = $user->getEmail();
		$sql = "INSERT INTO useremail (email) VALUES ('$email')";

		if(mysqli_query($connection, $sql)) {
			echo "Data inserted successfylly"."<br><br>";
		}else {
			echo "ERROR:". mysqli_error($connection);
		}

		// Close connection
		mysqli_close($connection);
	}


	function checkHowMuchSameDomainInDB($domain) {
		$connection = mysqli_connect("localhost", "root", "", "result");
		
		// Check connection
		if($connection === FALSE){
			die("ERROR: connection failed" . mysqli_connect_error());
		}

		$sql = "SELECT email FROM useremail";
		$result = $connection->query($sql);
		$records = $result->fetch_assoc();

		$howMuchSameDomainInDB = 0;
		for($i = 0; $i < $result->num_rows; $i++) {
			if(strpos($records["email"],$domain) == TRUE) {
				$howMuchSameDomainInDB ++;
			}
		}

		if ($result->num_rows > 1) {
			if(strpos($records["email"],$domain) == TRUE) {

				//chcecking if column with counter exsists
				$sql = "SELECT domainCounter FROM useremail";

				if(mysqli_query($connection, $sql) == NULL) {
					
					//adding new column with counter
					$sql = "ALTER TABLE useremail ADD domainCounter INT(100) NULL";

					if(mysqli_query($connection, $sql)) {
						//column added, inserting counter value

						$sql = "UPDATE useremail SET domainCounter='$howMuchSameDomainInDB'";
						mysqli_query($connection, $sql);
					} else {
						echo "ERROR:". mysqli_error($connection);
					}
				}else {
					//column exists, just inserting counter value

					$sql = "UPDATE useremail SET domainCounter='$howMuchSameDomainInDB'";
					mysqli_query($connection, $sql);
				}

			}
		}

		// Close connection
		mysqli_close($connection);
	}


	function printDB($domain) {
		$connection = mysqli_connect("localhost", "root", "", "result");
		
		// Check connection
		if($connection === FALSE){
			die("ERROR: connection failed" . mysqli_connect_error());
		}

		$sql = "SELECT email, domainCounter FROM useremail";
		if(mysqli_query($connection,$sql)) {
			$result = $connection->query($sql);
			$records = $result->fetch_assoc();

			echo "DATABASE CONTAINS:<br>";
			for($i = 0; $i < $result->num_rows; $i++) {
				echo "<pre>EMAIL: ".$records["email"]. "   HOW MANY $domain IN DB: ".$records["domainCounter"]."<br>";
			}
		}else {
			$sql = "SELECT email FROM useremail";
			$result = $connection->query($sql);
			$records = $result->fetch_assoc();

			echo "DATABASE CONTAINS:<br>";
			echo "<pre>EMAIL: ".$records["email"]."<br>";
		}
		

		// Close connection
		mysqli_close($connection);
	}



	//getting data from page
	$dataFromPage = file_get_contents('https://jsonplaceholder.typicode.com/users/1');

	//converting input string from page to format that fits parse_str()
	$filteredDataFromPage = str_replace( array('"address":', '"geo":', ' "company":', '"'), '', $dataFromPage); 
	$filteredDataFromPage = str_replace('{ name', "companyName", $filteredDataFromPage);
	$filteredDataFromPage = str_replace( array('{', '}'), '', $filteredDataFromPage); 
	$filteredDataFromPage = str_replace(",", "&", $filteredDataFromPage);
	$filteredDataFromPage = str_replace(":", "=", $filteredDataFromPage);

	parse_str($filteredDataFromPage, $singleDataFromPageArray);	
	$userValuesToInsert = returnValuesOfArray($singleDataFromPageArray);


	$user = new User();

	$user->setId($userValuesToInsert[0]);
	$user->setName($userValuesToInsert[1]);
	$user->setUsername($userValuesToInsert[2]);
	$user->setEmail($userValuesToInsert[3]);
	//address
	$user->setStreet($userValuesToInsert[4]);
	$user->setSuite($userValuesToInsert[5]);
	$user->setCity($userValuesToInsert[6]);
	$user->setZipcode($userValuesToInsert[7]);
	//geo
	$user->setLat($userValuesToInsert[8]);
	$user->setLng($userValuesToInsert[9]);

	$user->setPhone($userValuesToInsert[10]);
	$user->setWebsite($userValuesToInsert[11]);
	//company
	$user->setCompanyName($userValuesToInsert[12]);
	$user->setCatchPhrase($userValuesToInsert[13]);
	$user->setBs($userValuesToInsert[14]);

	$domain = getDomain($user);
	echo $domain."<br><br>";
	getPersonData($user);

	createDataBase();
	createTable();
	insertDataToTable($user);
	checkHowMuchSameDomainInDB($domain);
	printDB($domain);

?>