<?php

	require_once("Rest.inc.php");
	require_once("db.php");
	require_once("functions.php");

	class API extends REST {

		private $functions = NULL;
		private $db = NULL;

		public function __construct() {
			$this->db = new DB();
			$this->functions = new functions($this->db);
		}

		public function check_connection() {
			$this->functions->checkConnection();
		}

		/*
		 * ALL API Related android client -------------------------------------------------------------------------
		*/

		private function get_news_detail() {
	        $this->functions->getNewsById();
	    }

	    private function get_post_detail() {
	        $this->functions->getNewsDetail();
	    }
		
		private function get_category_index() {
	        $this->functions->getCategoryIndex();
	    }

		private function get_recent_posts() {
			$this->functions->getRecentPosts();
		}

		private function get_video_posts() {
			$this->functions->getVideoPosts();
		}

		private function get_category_posts() {
	        $this->functions->getCategoryPosts();
	    }

	    private function get_search_results() {
	        $this->functions->getSearchResults();
	    }

	    private function user_register() {
	        $this->functions->userRegister();
	    }

	    private function get_user() {
	        $this->functions->getUser();
	    }

	    private function get_user_login() {
	        $this->functions->getUserLogin();
	    }

	    private function get_user_profile() {
	        $this->functions->getUserProfile();
	    }

	    private function update_user_profile() {
	        $this->functions->updateUserProfile();
	    }

	    private function update_user_photo() {
	        $this->functions->updateUserPhoto();
	    }

	    private function get_comments() {
	        $this->functions->getComments();
	    }

	    private function post_comment() {
	        $this->functions->postComment();
	    } 

	    private function update_comment() {
	        $this->functions->updateComment();
	    }

	    private function delete_comment() {
	        $this->functions->deleteComment();
	    }

	    private function forgot_password() {
	        $this->functions->forgotPassword();
	    }

	    private function get_privacy_policy() {
	        $this->functions->getPrivacyPolicy();
	    }

		/*
		 * End of API Transactions ----------------------------------------------------------------------------------
		*/

		public function processApi() {
			if(isset($_REQUEST['x']) && $_REQUEST['x']!=""){
				$func = strtolower(trim(str_replace("/","", $_REQUEST['x'])));
				if((int)method_exists($this,$func) > 0) {
					$this->$func();
				} else {
					echo 'processApi - method not exist';
					exit;
				}
			} else {
				echo 'processApi - method not exist';
				exit;
			}
		}

	}

	// Initiiate Library
	$api = new API;
	$api->processApi();

?>
