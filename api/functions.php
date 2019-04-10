<?php

require_once("Rest.inc.php");
require_once("db.php");

class functions extends REST {
    
    private $mysqli = NULL;
    private $db = NULL;
    
    public function __construct($db) {
        parent::__construct();
        $this->db = $db;
        $this->mysqli = $db->mysqli;
    }

	public function checkConnection() {
			if (mysqli_ping($this->mysqli)) {
                $respon = array(
                    'status' => 'ok', 'database' => 'connected'
                );
                $this->response($this->json($respon), 200);
			} else {
                $respon = array(
                    'status' => 'failed', 'database' => 'not connected'
                );
                $this->response($this->json($respon), 404);
			}
	}

    public function getRecentPosts() {

    		include "../includes/config.php";
		    $setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
		    $setting_result = mysqli_query($connect, $setting_qry);
		    $settings_row   = mysqli_fetch_assoc($setting_result);
		    $api_key    = $settings_row['api_key'];

			if (isset($_GET['api_key'])) {

				$access_key_received = $_GET['api_key'];

				if ($access_key_received == $api_key) {

					if($this->get_request_method() != "GET") $this->response('',406);
						$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
						$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;
						
						$offset = ($page * $limit) - $limit;
						$count_total = $this->get_count_result("SELECT COUNT(DISTINCT n.nid) FROM tbl_news n WHERE n.content_type = 'Post' ");
						$query = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image, 
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type, 
									
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count

								  FROM tbl_news n 

								  LEFT JOIN tbl_comments r ON n.nid = r.nid 
								  LEFT JOIN tbl_category c ON n.cat_id = c.cid

								  WHERE n.content_type = 'Post'

								  GROUP BY n.nid 

								  ORDER BY n.nid 

								  DESC LIMIT $limit OFFSET $offset";

						$categories = $this->get_list_result($query);
						$count = count($categories);
						$respon = array(
							'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $categories
						);
						$this->response($this->json($respon), 200);

				} else {
					$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
					$this->response($this->json($respon), 404);
				}
			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
			}

    }

	public function getNewsById() {

		include "../includes/config.php";

		$id = $_GET['id'];
		
		$sql = "SELECT DISTINCT n.nid, 
						n.news_title, 
						n.cat_id,
						n.news_date, 
						n.news_image, 
						n.news_description,
						n.video_url,
						n.video_id, 
						n.content_type, 
									
						c.category_name, 
						COUNT(DISTINCT r.comment_id) as comments_count

						FROM tbl_news n 

						LEFT JOIN tbl_comments r ON n.nid = r.nid 
						LEFT JOIN tbl_category c ON n.cat_id = c.cid 

						WHERE n.nid = $id

						GROUP BY n.nid
								 
						LIMIT 1";
		$result = mysqli_query($connect, $sql);

		header( 'Content-Type: application/json; charset=utf-8' );
		print json_encode(mysqli_fetch_assoc($result));


	}    

	public function getNewsDetail() {

    	$id = $_GET['id'];

		if($this->get_request_method() != "GET") $this->response('',406);

		$query_post = "SELECT DISTINCT n.nid, 
						n.news_title, 
						n.cat_id,
						n.news_date, 
						n.news_image, 
						n.news_description,
						n.video_url,
						n.video_id, 
						n.content_type, 
									
						c.category_name, 
						COUNT(DISTINCT r.comment_id) as comments_count

						FROM tbl_news n 

						LEFT JOIN tbl_comments r ON n.nid = r.nid 
						LEFT JOIN tbl_category c ON n.cat_id = c.cid 

						WHERE n.nid = $id

						GROUP BY n.nid
								 
						LIMIT 1";

		$post = $this->get_one($query_post);
		$count = count($post);
		$respon = array(
			'status' => 'ok', 'post' => $post
		);
		$this->response($this->json($respon), 200);

    }

    public function getVideoPosts() {

    		include "../includes/config.php";
		    $setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
		    $setting_result = mysqli_query($connect, $setting_qry);
		    $settings_row   = mysqli_fetch_assoc($setting_result);
		    $api_key    = $settings_row['api_key'];

			if (isset($_GET['api_key'])) {

				$access_key_received = $_GET['api_key'];

				if ($access_key_received == $api_key) {

					if($this->get_request_method() != "GET") $this->response('',406);
						$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
						$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;
						
						$offset = ($page * $limit) - $limit;
						$count_total = $this->get_count_result("SELECT COUNT(DISTINCT n.nid) FROM tbl_news n WHERE n.content_type != 'Post' ");
						$query = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image, 
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type, 
									
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count

								  FROM tbl_news n 

								  LEFT JOIN tbl_comments r ON n.nid = r.nid 
								  LEFT JOIN tbl_category c ON n.cat_id = c.cid

								  WHERE n.content_type != 'Post'

								  GROUP BY n.nid 

								  ORDER BY n.nid 

								  DESC LIMIT $limit OFFSET $offset";

						$categories = $this->get_list_result($query);
						$count = count($categories);
						$respon = array(
							'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $categories
						);
						$this->response($this->json($respon), 200);

				} else {
					$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
					$this->response($this->json($respon), 404);
				}
			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
			}

    }
    
    public function getCategoryIndex() {

    	include "../includes/config.php";
        $setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
		$setting_result = mysqli_query($connect, $setting_qry);
		$settings_row   = mysqli_fetch_assoc($setting_result);
		$api_key    = $settings_row['api_key'];

			if (isset($_GET['api_key'])) {

				$access_key_received = $_GET['api_key'];

				if ($access_key_received == $api_key) {

					if($this->get_request_method() != "GET") $this->response('',406);
					$count_total = $this->get_count_result("SELECT COUNT(DISTINCT cid) FROM tbl_category");

					//$query = "SELECT distinct cid, category_name, category_image FROM tbl_category ORDER BY cid DESC";
					$query = "SELECT DISTINCT c.cid, c.category_name, c.category_image, COUNT(DISTINCT r.nid) as post_count
					  FROM tbl_category c LEFT JOIN tbl_news r ON c.cid = r.cat_id GROUP BY c.cid ORDER BY c.cid DESC";

					$news = $this->get_list_result($query);
					$count = count($news);
					$respon = array(
						'status' => 'ok', 'count' => $count, 'categories' => $news
					);
					$this->response($this->json($respon), 200);

				} else {
					$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
					$this->response($this->json($respon), 404);
				}
			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
			}

    }

    public function getCategoryPosts() {

    	$id = $_GET['id'];

		if($this->get_request_method() != "GET") $this->response('',406);
		$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
		$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;

		$offset = ($page * $limit) - $limit;
		$count_total = $this->get_count_result("SELECT COUNT(DISTINCT nid) FROM tbl_news WHERE cat_id = '$id'");

		$query_category = "SELECT distinct cid, category_name, category_image FROM tbl_category WHERE cid = '$id' ORDER BY cid DESC";

		$query_post = "SELECT DISTINCT n.nid, 
						n.news_title, 
						n.cat_id,
						n.news_date, 
						n.news_image, 
						n.news_description,
						n.video_url,
						n.video_id, 
						n.content_type, 
									
						c.category_name, 
						COUNT(DISTINCT r.comment_id) as comments_count

						FROM tbl_news n 

						LEFT JOIN tbl_comments r ON n.nid = r.nid 
						LEFT JOIN tbl_category c ON n.cat_id = c.cid 

						WHERE c.cid = '$id' 

						GROUP BY n.nid 
						ORDER BY n.nid DESC 
								 
						LIMIT $limit OFFSET $offset";

		$category = $this->get_category_result($query_category);
		$post = $this->get_list_result($query_post);
		$count = count($post);
		$respon = array(
			'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'category' => $category, 'posts' => $post
		);
		$this->response($this->json($respon), 200);

    }

    public function getSearchResults() {

    	include "../includes/config.php";
		    $setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
		    $setting_result = mysqli_query($connect, $setting_qry);
		    $settings_row   = mysqli_fetch_assoc($setting_result);
		    $api_key    = $settings_row['api_key'];

			if (isset($_GET['api_key'])) {

				$access_key_received = $_GET['api_key'];

				if ($access_key_received == $api_key) {

					$search = $_GET['search'];

					if($this->get_request_method() != "GET") $this->response('',406);
					$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
					$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;

					$offset = ($page * $limit) - $limit;
					$count_total = $this->get_count_result("SELECT COUNT(DISTINCT n.nid) FROM tbl_news n, tbl_category c WHERE n.cat_id = c.cid AND (n.news_title LIKE '%$search%' OR n.news_description LIKE '%$search%')");

					$query = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image, 
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type, 
									
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count

								  FROM tbl_news n 

								  LEFT JOIN tbl_comments r ON n.nid = r.nid 
								  LEFT JOIN tbl_category c ON n.cat_id = c.cid 

								  WHERE n.cat_id = c.cid AND (n.news_title LIKE '%$search%' OR n.news_description LIKE '%$search%') 

								  GROUP BY n.nid 
								  ORDER BY n.nid DESC

							LIMIT $limit OFFSET $offset";

					$post = $this->get_list_result($query);
					$count = count($post);
					$respon = array(
						'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $post
					);
					$this->response($this->json($respon), 200);

				} else {
					$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
					$this->response($this->json($respon), 404);
				}
			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
			}

    }

    public function getComments() {

			$nid = $_GET['nid'];

			if($this->get_request_method() != "GET") $this->response('',406);
			$count_total = $this->get_count_result("SELECT COUNT(DISTINCT comment_id) FROM tbl_comments c, tbl_news n WHERE n.nid = c.nid AND n.nid = '$nid'");
			
			$query = "SELECT 

			c.comment_id,
			c.user_id,
			u.name,
			u.imageName AS 'image',
			c.date_time,
			c.content

			FROM tbl_news n, tbl_comments c, tbl_users u WHERE n.nid = c.nid AND c.user_id = u.id AND n.nid = '$nid' 

			ORDER BY c.comment_id DESC";
					  
			$categories = $this->get_list_result($query);
			$count = count($categories);
			$respon = array(
				'status' => 'ok', 'count' => $count, 'comments' => $categories
			);
			$this->response($this->json($respon), 200);
	}

	public function postComment() {

			if($_SERVER['REQUEST_METHOD'] == 'POST') {

			       $response = array();
			       //mendapatkan data
			       $nid = $_POST['nid'];
			       $user_id = $_POST['user_id'];
			       $content = $_POST['content'];
			       $date_time = $_POST['date_time'];

			       include "../includes/config.php";

			       $sql = "INSERT INTO tbl_comments (nid, user_id, content, date_time) VALUES('$nid', '$user_id', '$content', '$date_time')";
			       if (mysqli_query($connect, $sql)) {
			         $response["value"] = 1;
			         $response["message"] = "success post comment";
			         echo json_encode($response);
			       } else {
			         $response["value"] = 0;
			         $response["message"] = "oops! failed!";
			         echo json_encode($response);
			       }
			     
			     // tutup database
			     mysqli_close($connect);

			  } else {
			    $response["value"] = 0;
			    $response["message"] = "oops! failed!";

			    header( 'Content-Type: application/json; charset=utf-8' );
			    echo json_encode($response);
			  }

	}

	public function updateComment() {

		    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		        $response = array();

		        //mendapatkan data
		        $comment_id = $_POST['comment_id'];
		        $date_time  = $_POST['date_time'];
		        $content    = $_POST['content'];

		        include "../includes/config.php";

		        $sql = "UPDATE tbl_comments SET comment_id = '$comment_id', date_time = '$date_time', content = '$content' WHERE comment_id = '$comment_id'";

		        if(mysqli_query($connect, $sql)) {
		            $response["value"] = 1;
		            $response["message"] = "your comment successfully updated";

		            header( 'Content-Type: application/json; charset=utf-8' );
		            echo json_encode($response);
		        } else {
		            $response["value"] = 0;
		            $response["message"] = "oops! failed update comment!";

		            header( 'Content-Type: application/json; charset=utf-8' );
		            echo json_encode($response);
		        }

		        mysqli_close($connect);

		    }

	}

	public function deleteComment() {

		    include "../includes/config.php";

		    if($_SERVER['REQUEST_METHOD'] == 'POST') {
		      
		        $response = array();
		        //mendapatkan data
		        $comment_id = $_POST['comment_id'];
		        $sql = "DELETE FROM tbl_comments WHERE comment_id = '$comment_id'";

		        if(mysqli_query($connect, $sql)) {
		            $response["value"] = 1;
		            $response["message"] = "Your comment was deleted successfully.";

		            header( 'Content-Type: application/json; charset=utf-8' );
		            echo json_encode($response);
		        } else {
		            $response["value"] = 0;
		            $response["message"] = "oops! Failed to delete comment!";

		            header( 'Content-Type: application/json; charset=utf-8' );
		            echo json_encode($response);
		        }
		        mysqli_close($connect);

		    }

	}

		public function userRegister() {

			include "../includes/config.php";
			include "../public/register.php";

			if(isset($_GET['email'])) {
	
				$qry = "SELECT * FROM tbl_users WHERE email = '".$_GET['email']."'"; 
				$sel = mysqli_query($connect, $qry);
			
				if(mysqli_num_rows($sel) > 0) {
					$set['result'][]=array('msg' => "Email address already used!", 'success'=>'0');
					echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
					die();
				} else {
		 			$data = array(
		 			'user_type'=>'Normal',											 
					'name'  => $_GET['name'],
					'email'  =>  $_GET['email'],
					'password'  =>  $_GET['password'],
					'status'  =>  '1'
					);

					$qry = Insert('tbl_users', $data);									 
					
					$set['result'][] = array('msg' => "Register succesfully...!", 'success'=>'1');
					echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
					die();
				}
				
			} else {
				
				 header( 'Content-Type: application/json; charset=utf-8' );
				 $json = json_encode($set);

				 echo $json;
				 exit;		 
			}

		}

		public function getUser() {

		    include "../includes/config.php";
		    
		    if(isset($_REQUEST['user_id'])) {
		         
		         $id = $_REQUEST['user_id'];
		         
		         $query = " SELECT * FROM tbl_users WHERE id = '$id' ";
		         $result = mysqli_query($connect, $query);
		         
		         while ($row = mysqli_fetch_assoc($result)) {
		              $output[] = $row;
		         }
		         
		         print(json_encode($output));
		         
		         mysqli_close($connect);

		    } else {

		       $output = "not found";
		       print(json_encode($output));
		    
		    }

		}

		public function getUserLogin() {

			include "../includes/config.php";

			$qry = "SELECT * FROM tbl_users WHERE email = '".$_GET['email']."' AND password = '".$_GET['password']."'"; 
			$result = mysqli_query($connect, $qry);
			$num_rows = mysqli_num_rows($result);
			$row = mysqli_fetch_assoc($result);
				
		    if ($num_rows > 0 && $row['status'] == 1) { 		 
				$set['result'][] = array('user_id' => $row['id'], 'name' => $row['name'], 'success' => '1'); 
			} else if ($num_rows > 0 && $row['status'] == 0) {
				$set['result'][] = array('msg' => 'Account disabled', 'success' => '2');
			} else {
				$set['result'][] = array('msg' => 'Login failed', 'success' => '0');
			}
			 
			header( 'Content-Type: application/json; charset=utf-8' );
			$json = json_encode($set);

			echo $json;
			exit;
			
		}

		public function getUserProfile() {

			include "../includes/config.php";

			$id = $_GET['id'];

 	 		$qry = "SELECT * FROM tbl_users WHERE id = '$id' ";
			$result = mysqli_query($connect, $qry);	 
			$row = mysqli_fetch_assoc($result);
			  				 
			$set['result'][] = array(
				'user_id' => $row['id'],
				'name'=>$row['name'],
				'email'=>$row['email'],
				'password'=>$row['password'],
				'image'=>$row['imageName'],
				'success'=>'1'
			);

			header( 'Content-Type: application/json; charset=utf-8' );
			$json = json_encode($set);

			echo $json;
			exit;

		}

		public function updateUserProfile() {

			include "../includes/config.php";
			include "../public/register.php";
	
		 	if($_GET['password']!="") {
				$data = array(
				'name'  =>  $_GET['name'],
				'email'  =>  $_GET['email'],
				'password'  =>  $_GET['password']
				);
			} else {
				$data = array(
				'name'  =>  $_GET['name'],
				'email'  =>  $_GET['email']
				);
			}
				
			$user_edit = Update('tbl_users', $data, "WHERE id = '".$_GET['user_id']."'");
		 	$set['result'][] = array('msg'=>'Updated', 'success'=>'1');
					 
			header( 'Content-Type: application/json; charset=utf-8' );
			$json = json_encode($set);

			echo $json;
			exit;

		}

		public function updateUserPhoto() {

		    include "../includes/config.php";

		    // check if "image" abd "user_id" is set 
		    if(isset($_POST["image"]) && isset($_POST["user_id"])) {

		        $data = $_POST["image"];
		        $time = time();

		        $user_id = $_POST["user_id"];
		        //$oldImage ="images/"."1_1497679518.jpg";
		        $ImageName = $user_id.'_'.$time.".jpg";

		        //$filePath = "images/".$ImageName;
		        $filePath = '../upload/avatar/'.$ImageName; // path of the file to store
		        echo "file : ".$filePath;
		        //echo "unlink : ".$oldImage;

		        // check if file exits
		        if (file_exists($filePath)) {
		            unlink($filePath); // delete the old file
		        } 
		        // create a new empty file
		        $myfile = fopen($filePath, "w") or die("Unable to open file!");
		        // add data to that file
		        file_put_contents($filePath, base64_decode($data));

		        // update the Customer table with new image name.
		        $query = " UPDATE tbl_users SET imageName = '$ImageName' WHERE id = '$user_id' ";
		        mysqli_query($connect, $query);

		        
		    } else {
		        echo 'not set';
		    }
		    
		    mysqli_close($connect);

		}

	public function forgotPassword() {

		include "../includes/config.php";
		    
		$qry = "SELECT * FROM tbl_users WHERE email = '".$_GET['email']."'"; 
		$result = mysqli_query($connect, $qry);
		$row = mysqli_fetch_assoc($result);
		
		if ($row['email']!="") {
			//$new_password=rand(1,99999);

			$to = $_GET['email'];
			// subject
			$subject = '[IMPORTANT] Android News App Forgot Password Information';
			//$message = '<div><strong>Confirmation Code</strong>:'.$confirm_code.'<br></div>';
			
			$message='<div style="background-color: #f9f9f9;" align="center"><br />
					  <table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
					    <tbody>
					      <tr>
					        <td width="600" valign="top" bgcolor="#FFFFFF"><br>
					          <table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
					            <tbody>
					              <tr>
					                <td valign="top"><table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
					                    <tbody>
					                      <tr>
					                        <td><p style="color: #262626; font-size: 28px; margin-top:0px;"><strong>Dear '.$row['name'].'</strong></p>
					                          <p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;">Thank you for using Android News App,<br>
					                            Your password is: '.$row['password'].'</p>
					                          <p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;margin-bottom:30px;">Thanks you,<br />
					                            Android News App.</p></td>
					                      </tr>
					                    </tbody>
					                  </table></td>
					              </tr>
					               
					            </tbody>
					          </table></td>
					      </tr>
					      <tr>
					        <td style="color: #262626; padding: 20px 0; font-size: 20px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">Copyright © Android News App.</td>
					      </tr>
					    </tbody>
					  </table>
					</div>';
 
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Android News App <don-not-reply@solodroid.net>' . "\r\n";
			// Mail it
			@mail($to, $subject, $message, $headers);

			$set['result'][]=array('msg' => "Password has been sent on your mail!",'success'=>'1');

		} else {
			$set['result'][]=array('msg' => "Email not found in our database!",'success'=>'0');		
		}

	 	header( 'Content-Type: application/json; charset=utf-8');
	    $json = json_encode($set);
					
		echo $json;
		exit;

	}

	public function getPrivacyPolicy() {

		include "../includes/config.php";
		
		$sql = "SELECT * FROM tbl_settings WHERE id = 1";
		$result = mysqli_query($connect, $sql);

		header( 'Content-Type: application/json; charset=utf-8' );
		print json_encode(mysqli_fetch_assoc($result));


	}




    public function get_list_result($query) {
		$result = array();
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) {
			while($row = $r->fetch_assoc()) {
				$result[] = $row;
			}
		}
		return $result;
	}

    public function get_count_result($query) {
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) {
			$result = $r->fetch_row();
			return $result[0];
		}
		return 0;
	}

	private function get_category_result($query) {
		$result = array();
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) {
			while($row = $r->fetch_assoc()) {
				$result = $row;
			}
		}
		return $result;
	}

	private function get_one($query) {
		$result = array();
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) $result = $r->fetch_assoc();
		return $result;
	}
    
}

?>