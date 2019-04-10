<?php
	include 'functions.php'; 
?>

	<?php

		if (isset($_GET['id'])) {
			$ID = $_GET['id'];
		} else {
			$ID = "";
		}
			
		if (isset($_POST['btnEdit'])) {

			$message = $_POST['message'];
			$link = $_POST['link'];

			// create array variable to handle error
			$error = array();
				
			if (empty($message)) {
				$error['message'] = " <span class='label label-danger'>Must Insert!</span>";
			}			
				
			if (!empty($message)) {
					
				$sql_query = "UPDATE tbl_fcm_template SET message = ?, link = ? WHERE id = ?";
					
				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('sss', $message, $link, $ID);
					// Execute query
					$stmt->execute();
					// store result 
					$update_result = $stmt->store_result();
					$stmt->close();
				}
				

	            // check update result
	            if ($update_result) {
	                //$error['update_notification'] = "<br><div class='alert alert-info'>Push Notification Template Successfully Updated...</div>";
		            $succes =<<<EOF
					<script>
					alert('Push Notification Template Successfully Updated...');
					window.location = 'push-notification.php';
					</script>
EOF;

					echo $succes;
	            } else {
	                $error['update_notification'] = "<br><div class='alert alert-danger'>Update Failed</div>";
	            }				

			}
				
		}
			
		// create array variable to store previous data
		$data = array();
		
		$sql_query = "SELECT id, message, link FROM tbl_fcm_template WHERE id = ?";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['id'], 
					$data['message'],
					$data['link']
					);
			$stmt->fetch();
			$stmt->close();
		}
		
	?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="push-notification.php">Notification</a></li>
            <li class="active">Edit Notification Template</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
            	
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <form id="form_validation" method="post" enctype="multipart/form-data">
                    <div class="card">
                        <div class="header">
                            <h2>EDIT NOTIFICATION TEMPLATE</h2>
                                <?php echo isset($error['update_notification']) ? $error['update_notification'] : ''; ?>
                        </div>
                        <div class="body">

                            <div class="row clearfix">
                                
                                <div>
                                    <div class="form-group col-sm-12">
                                        <div class="form-line">
                                            <div class="font-12">Message</div>
                                            <input type="text" class="form-control" name="message" id="message" value="<?php echo $data['message']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="form-line">
                                            <div class="font-12">Url (Optional)</div>
                                            <input type="text" class="form-control" name="link" id="link" value="<?php echo $data['link']; ?>" >
                                        </div>
                                    </div>                           

                                    <div class="col-sm-12">
                                         <button class="btn bg-blue waves-effect pull-right" type="submit" name="btnEdit">UPDATE</button>
                                    </div>

                                   
                                    
                                </div>

                            </div>
                        </div>
                    </div>
                    </form>

                </div>

            </div>
            
        </div>

    </section>