<?php

$app->post('/app/login', function ($request) {
	include __DIR__ . '/../Bootstrap/dbconnect.php';
    
    $uid = $request->getParsedBody()['uid'];
    $name = $request->getParsedBody()['name'];
    $email = $request->getParsedBody()['email'];
    $profileUrl = $request->getParsedBody()['profileUrl'];
    $CoverUrl = $request->getParsedBody()['CoverUrl'];
    $userToken = $request->getParsedBody()['userToken'];

    $checkstmt = $pdo->prepare("SELECT `uid` from `users` WHERE `uid` = :uid LIMIT 1;");
    $checkstmt->bindParam(':uid', $uid, PDO::PARAM_STR);
    $checkstmt->execute();
    $count = $checkstmt->rowcount();

    if($count==1)
    {
      //user is already signed in so we have to update the user token

    	$stmt = $pdo->prepare("UPDATE `users` SET `userToken` = :userToken WHERE `uid` = :uid;");
    	$stmt->bindParam(':userToken', $userToken, PDO::PARAM_STR);
    	$stmt->bindParam(':uid' , $uid , PDO::PARAM_STR);
    	$stmt=$stmt->execute();


    }else{

    	//inserting the data of the new user in database

    	$stmt = $pdo->prepare("INSERT INTO `users` (`uid`, `name`, `email`, `profileUrl` , `CoverUrl` , `userToken`) VALUES (:uid, :name, :email, :profileUrl, :CoverUrl, :userToken); ");

    	$stmt->bindParam(':uid' , $uid , PDO::PARAM_STR);
    	$stmt->bindParam(':name' , $name , PDO::PARAM_STR);
    	$stmt->bindParam(':email' , $email , PDO::PARAM_STR);
    	$stmt->bindParam(':profileUrl' , $profileUrl , PDO::PARAM_STR);
    	$stmt->bindParam(':CoverUrl' , $CoverUrl , PDO::PARAM_STR);
    	$stmt->bindParam(':userToken' , $userToken , PDO::PARAM_STR);
    	$stmt=$stmt->execute();


    }

     	if($stmt)
    	{
    		echo true;
    	}else{
    		echo false;
    	}


});

//loading own profile

$app->get('/app/loadownprofile', function($request){
 
  include __DIR__ . '/../Bootstrap/dbconnect.php';

  $user_id = $request->getParam('userid');

  $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `uid`=:userid");
  $stmt->bindParam(':userid', $user_id,PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $row['state']=5;

  echo json_encode($row);

});

//loading unknown's profile

$app->get('/app/otherprofile', function($request){
 
  include __DIR__ . '/../Bootstrap/dbconnect.php';

  $user_id = $request->getParam('userid');
  $profileid = $request->getParam('profileid');
  $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `uid`=:userid");
  $stmt->bindParam(':userid', $profileid,PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

   $currentstate = "0";

   $request = checkRequest($user_id, $profileid);

 /*  Array
 (
    [requestId] => 1
    [sender] => gCYGGceafgOXaqB3fOjvEnacb0t1
    [receiver] => gMzfofMykMO7eteDjOVLeX0ulfS2
    [requestDate] => 2019-12-24 16:13:42
 )*/

 if($request)
 {
 	if($request["sender"] == $user_id)
 	{
 		$currentstate = "2";
 	}
 	elseif($request["sender"] == $profileid)
 	{
 		$currentstate = "3";
 	}
 }
 else
 {
 	if(checkFriend($user_id, $profileid))
 	{
 		$currentstate = "1";
 	}
 	else
 	{
 		$currentstate = "4";
 	}
 }

   $row['state'] = $currentstate;

  echo json_encode($row);

});


//Api for search view

$app->get('/app/search', function($request){
 
  include __DIR__ . '/../Bootstrap/dbconnect.php';

  $keyword = $request->getParam('keyword');

  $stmt = $pdo->prepare("
    SELECT * FROM `users`
    WHERE name like '$keyword%'
    LIMIT 10

  	");


  $stmt->execute();
  $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($row);

});

//load friends

$app->get('/app/loadfriends',function($request){

	include __DIR__ .'/../bootstrap/dbconnect.php';
	$userId = $request->getParam('userId');

	$stmt = $pdo->prepare('
								SELECT users.* FROM `users` 
								Inner JOIN `requests`
								ON users.uid = requests.sender
								 WHERE `receiver` = :userId'
							);

		 $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);

		$stmt->execute();	

		$row['requests']= $stmt->fetchAll(PDO::FETCH_ASSOC);



		$stmt = $pdo->prepare('
								SELECT users.* FROM `users` 
								Inner JOIN `friends`
								ON users.uid = friends.profileId
								 WHERE friends.userId = :userId'
							);

		 $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);

		$stmt->execute();		
		$row['friends'] = $stmt->fetchAll(PDO::FETCH_ASSOC);	


		echo json_encode($row);

});

//Api for showing user's profile timeline

$app->get('/app/profiletimeline',function($request){

     	 include __DIR__ . '/../bootstrap/dbconnect.php';


		 $onlineid = $request->getParam('onlineid');
		 $uid = $request->getParam('uid');
		 $limit = $request->getParam('limit');
		 $offset = $request->getParam('offset');
		 $current_state = $request->getParam('current_state');
		
	 	 $stmt =  $pdo->prepare("SELECT `name`,`profileUrl`,`userToken` from `users` WHERE `uid` = :uid LIMIT 1");
		 $stmt->bindParam(':uid', $uid, PDO::PARAM_STR);
		 $stmt->execute();	

		 $userInfo =$stmt->fetch(PDO::FETCH_OBJ);

				

				/*

				privacy flags representation

			        0 - > Friends privacy level
			        1 - > Only Me privacy level
			        2 - > Public privacy level

			     */


			   /*
					Relations between two accounts 

				    1=  two people are friends 
				    4 = people are unkown
				    5 = own profile


			     */

		if($current_state==5){


			$stmt = " SELECT * FROM `posts` WHERE `postUserId` = :uid ORDER By statusTime DESC"; 

			/*

				-> our own profile,
				-> can view only me, friends and public  privacy level post

			*/

		}else if($current_state==4){

			$stmt = " SELECT * FROM `posts` WHERE `postUserId` = :uid AND `privacy` = 2 ORDER By statusTime DESC"; 

			/*

				-> not friend account ( unknown profile ),
				-> can view public privacy level post

			*/
		}else if($current_state==1){

			$stmt = " SELECT * FROM `posts` WHERE `postUserId` = :uid AND ( `privacy` = 2 OR `privacy` = 0 ) ORDER By statusTime DESC"; 

			/*

				-> friends accoun
				-> can view fiends and public privacy level post

			*/
		}else{
			$stmt = " SELECT * FROM `posts` WHERE `postUserId` = :uid AND `privacy` = 2 ORDER By statusTime DESC"; 
			/*
				-> relation not known
				-> can view nothing
			*/
		}

		$stmt .=  '  LIMIT '.$limit. ' OFFSET '.$offset;
	

	 	$stmt = $pdo->prepare($stmt);

		$stmt->bindParam(':uid', $uid, PDO::PARAM_STR);		 
	   	$stmt->execute();

		$reviews= $stmt->fetchAll(PDO::FETCH_OBJ);
		
	

		foreach ($reviews as $key => $value) {

			$value->name        =  $userInfo->name;
			$value->profileUrl =   $userInfo->profileUrl;
			$value->userToken   = $userInfo->userToken;

			if(checkLike($onlineid,$value->postid)){
		 		$value->isLiked=true;
		 	}else{
		 		$value->isLiked=false;
			}
		

		}
			echo json_encode($reviews);	
			
				
});


//Api for personalized timeline
$app->get('/app/gettimelinepost',function($request){

     	 include __DIR__ . '/../bootstrap/dbconnect.php';

		
		
		 $uid = $request->getParam('uid');
		 $limit = $request->getParam('limit');
		 $offset = $request->getParam('offset');
		
	$stmt = $pdo->prepare("
	 							SELECT 	 posts.*, users.name, users.profileUrl,users.userToken
						 		from 	`timeline`
						 		INNER JOIN `posts`
						 			on timeline.postid = posts.postid
						 		INNER JOIN `users`
						 			on  posts.postUserId = users.uid
						 		WHERE 	timeline.whoseTimeLine= :uid
						 		ORDER By timeline.statusTime DESC
						 		LIMIT $limit OFFSET $offset
						 		"
						 	);

		$stmt->bindParam(':uid', $uid, PDO::PARAM_STR);		 
	   	$stmt->execute();

	
		
	  $reviews= $stmt->fetchAll(PDO::FETCH_OBJ);

	  foreach ($reviews as $key => $value) {


			if(checkLike($uid,$value->postid)){
		 		$value->isLiked=true;
		 	}else{
		 		$value->isLiked=false;
			}
		

		}
		
	

			echo json_encode($reviews);	
			
				
});

//Get Notification From Database


$app->get('/app/getnotification',function($request){

		include __DIR__ . '/../bootstrap/dbconnect.php';
		 $userId = $request->getParam('uid');

		$stmt = $pdo->prepare('
			
					SELECT notifications.*,users.name,users.profileUrl,posts.post FROM `notifications`
					LEFT join users
					ON
					notifications.notificationFrom = users.uid
					LEFT join posts
					ON
					notifications.postId = posts.postid 
					WHERE `notificationTo` = :userId 
					ORDER BY
					notifications.notificationTime
					DESC

					');


		 $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
		$stmt->execute();		
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		echo json_encode($row);
});


//loading single post
$app->get('/app/details',function($request){
 	include __DIR__ . '/../bootstrap/dbconnect.php';


		$postId = $request->getParam('postId');
		$uid = $request->getParam('uid');

	 	$stmt = $pdo->prepare("
	 							SELECT posts.*, users.name, users.profileUrl,users.userToken
	 							 FROM `posts` 
	 							 LEFT join users
								  ON
								 posts.postUserId = users.uid
	 							 WHERE `postid` = :postId

	 							 ");
		$stmt->bindParam(':postId', $postId, PDO::PARAM_INT);		 
	   	$stmt->execute();

		$result =$stmt->fetch(PDO::FETCH_OBJ);	

		if(checkLike($uid,$postId)){
		 		$result->isLiked=true;
		 	}else{
		 		$result->isLiked=false;
			}
		echo json_encode($result);
		
});

// Test Api for sending Notification 

$app->get('/app/test',function($request){
	
	echo "Hi How are you";
	

});


//function to check like

function checkLike($userId,$postId){
	 include __DIR__ . '/../bootstrap/dbconnect.php';
		$stmt = $pdo->prepare("SELECT * FROM `userpostlikes` WHERE `likeBy` = :userId AND `postOn` = :postId");
		$stmt->bindParam(":userId", $userId, PDO::PARAM_STR);
		$stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_OBJ);

	}


?>