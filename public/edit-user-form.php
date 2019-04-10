<?php include 'functions.php' ?>

<?php

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
	} else {
		$id = "";
	}
			
	// create array variable to handle error
	$error = array();
	
	// create array variable to store data from database
	$data = array();
			
	if (isset($_POST['btnSave'])) {
		$process = $_POST['status'];
		$sql_query = "UPDATE tbl_users SET status = ? WHERE id = ?";
			
		$stmt = $connect->stmt_init();
		if ($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('ss', $process, $id);
			// Execute query
			$stmt->execute();
			// store result 
			$update_result = $stmt->store_result();
			$stmt->close();
		}
			
		// check update result
		if ($update_result) {
			//$error['update_data'] = "<br><div class='alert alert-info'>User status successfully changed...</div>";
			$succes =<<<EOF
				<script>
				alert('User Status Successfully Changed ...');
				window.location = 'registered-user.php';
				</script>
EOF;

			echo $succes;
		} else {
			$error['update_data'] = "<br><div class='alert alert-danger'>Update Failed</div>";
		}
	}
		
	// get data from reservation table
	$sql_query = "SELECT * FROM tbl_users WHERE id = ?";
		
	$stmt = $connect->stmt_init();
	if ($stmt->prepare($sql_query)) {	
		// Bind your variables to replace the ?s
		$stmt->bind_param('s', $id);
		// Execute query
		$stmt->execute();
		// store result 
		$stmt->store_result();
		$stmt->bind_result($data['id'], 
				$data['user_type'],
				$data['name'],
				$data['email'],
				$data['password'],
				$data['confirm_code'], 
				$data['status'], 
				$data['imageName']
				);
		$stmt->fetch();
		$stmt->close();
	}
		
?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="registered-user.php">Registered User</a></li>
            <li class="active">Edit User</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <form id="form_validation" method="post" enctype="multipart/form-data">
                    <div class="card">
                        <div class="header">
                            <h2>EDIT USER</h2>
                                <?php echo isset($error['update_data']) ? $error['update_data'] : ''; ?>
                        </div>
                        <div class="body">

                            <div class="row clearfix">
                                
                                <div class="col-sm-12">

                    <div class="form-group">
                        <div class="font-12">status</div>

                    <select class="form-control show-tick" name="status" id="status">	
						<?php if ($data['status'] == 1) { ?>
							<option value="1" selected="selected">Enabled</option>
							<option value="0" >Disabled</option>
						<?php } else { ?>
							<option value="1" >Enabled</option>
							<option value="0" selected="selected">Disabled</option>
						<?php } ?>
					</select>
					</div>

                                    <div class="col-sm-12">
                                         <button class="btn bg-blue waves-effect pull-right" type="submit" name="btnSave">UPDATE</button>
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