<?php
// echo "hii";die();
// $connect = new PDO("mysql:host=localhost:3308;dbname=testing", "root", "password");
$servername = "localhost:3308";
$username = "root";
$db = "testing";
$password = "password";
// $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
// print_r($conn);die();
if(isset($_POST["rating_data"]))
{
	
	//datetime , ".time()."
	$query = "
	INSERT INTO review_table 
	(user_name, user_rating, user_review) 
	VALUES ('".$_POST["user_name"]."', ".$_POST["rating_data"].", '".$_POST["user_review"]."')
	";
  //single quote for string data
	// $statement = $connect->prepare($query);

	// $statement->execute($data);
  // print_r(mysqli_query($conn, $query));die();
	if (mysqli_query($conn, $query)) {
		echo "Your Review & Rating Successfully Submitted";die();
	} else {
		echo "Error: " . $query . "<br>" . $conn->error;die();
	}
	
	// $conn->close();

	echo "Your Review & Rating Successfully Submitted";

}

if(isset($_POST["action"]))

{
	
	
	$average_rating = 0;
	$total_review = 0;
	$five_star_review = 0;
	$four_star_review = 0;
	$three_star_review = 0;
	$two_star_review = 0;
	$one_star_review = 0;
	$total_user_rating = 0;
	$review_content = array();

	$query = "
	SELECT * FROM review_table 
	ORDER BY review_id DESC
	";

	// $result = $conn->query($query, PDO::FETCH_ASSOC);
	$result = mysqli_query($conn, $query);
	// if (mysqli_num_rows($result) > 0) {
	// 	// output data of each row
	// 	while($row = mysqli_fetch_assoc($result)) {
	// 		echo "'.$row["user_name"].'";
	// 	}
	// }
while($row=mysqli_fetch_assoc($result))

  // $row=mysqli_fetch_assoc($result);
	// echo ".$row["user_review"].";die();
	
	{
		$review_content[] = array(
			'user_name'		=>	$row["user_name"],
			'user_review'	=>	$row["user_review"],
			'rating'		=>	$row["user_rating"],
			'datetime'		=>	$row["datetime"]
		);
		//date('l jS, F Y h:i:s A', 
		if($row["user_rating"] == '5')
		{
			$five_star_review++;
		}

		if($row["user_rating"] == '4')
		{
			$four_star_review++;
		}

		if($row["user_rating"] == '3')
		{
			$three_star_review++;
		}

		if($row["user_rating"] == '2')
		{
			$two_star_review++;
		}

		if($row["user_rating"] == '1')
		{
			$one_star_review++;
		}

		$total_review++;

		$total_user_rating = $total_user_rating + $row["user_rating"];

	}

	$average_rating = $total_user_rating / $total_review;

	$output = array(
		'average_rating'	=>	number_format($average_rating, 1),
		'total_review'		=>	$total_review,
		'five_star_review'	=>	$five_star_review,
		'four_star_review'	=>	$four_star_review,
		'three_star_review'	=>	$three_star_review,
		'two_star_review'	=>	$two_star_review,
		'one_star_review'	=>	$one_star_review,
		'review_data'		=>	$review_content
	);

	echo json_encode($output);

}
$conn->close();
?>