<?php
	// include_once('functions.php');

	$error = false;

	/**
	 * Call Detail Member by id
	 */
	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
		$id =  $_GET['id'];

		$sql = "SELECT * FROM tbl_admin WHERE id = ? LIMIT 1";
		$stmt = $connect->prepare($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id, $username, $password, $email, $full_name, $role);
		$stmt->fetch();

	} else {
		die('404 Oops!!!');
	}

	$error = false;
	/**
	 * Update Command
	 */
	if (isset($_POST['btnEdit'])) {

		$newusername   	= $_POST['username'];
		$newfullname    = $_POST['full_name'];
		$newpassword   	= trim($_POST['password']);
		$newrepassword 	= trim($_POST['repassword']);
		$newemail 		= $_POST['email'];
        //$newrole  = $_POST['role'] ? : '102';
		$newrole  		= $_POST['role'];

		if (strlen($newusername) < 3) {
			$error[] = 'Username is too short!';
		}

		if (empty($newfullname)) {
			$error[] = 'Full name can not be empty!';
		}

		if (empty($newpassword)) {
			$error[] = 'Password can not be empty!';
		}

		if ($newpassword != $newrepassword) {
			$error[] = 'Password does not match!';
		}

		$newpassword = hash('sha256', $newusername.$newpassword);

		if (filter_var($newemail, FILTER_VALIDATE_EMAIL) === FALSE) {
			$error[] = 'Email is not valid!';
		}

		if (! $error) {
			$sql = "UPDATE tbl_admin SET username = ?,
			 							password = ?,
			 							email = ?,
			 							full_name = ?,
			 							user_role = ?
			 						WHERE
			 							id = ?";
			$update = $connect->prepare($sql);
			$update->bind_param(
				'sssssi',
				$newusername,
				$newpassword,
				$newemail,
				$newfullname,
				$newrole,
				$id
			);

			$update->execute();

			$succes =<<<EOF
			<script>
			alert('Update User Success');
			window.location = 'edit-member.php?id=$id';
			</script>

EOF;
			echo $succes;
		}
	}

?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="members.php">Manage Administrator</a></li>
            <li class="active">Edit Administrator</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <form id="form_validation" method="post">
                    <div class="card">
                        <div class="header">
                            <h2>EDIT ADMINISTRATOR</h2>
                            <?php echo $error ? '<div class="alert alert-info">'. implode('<br>', $error) . '</div>' : '';?>
                        </div>
                        <div class="body">

                            <div class="row clearfix">
                                
                                <div>
                                    <div class="form-group col-sm-12">
                                        <div class="form-line">
                                            <div class="font-12">Username</div>
                                            <input type="text" class="form-control" value="<?php echo $username; ?>" disabled />

                                            <input type="hidden" class="form-control" value="<?php echo $username; ?>" name="username" id="username" />
                                            <!-- <label class="form-label">Username</label> -->
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="form-line">
                                            <div class="font-12">Full Name</div>
                                            <input type="text" class="form-control" name="full_name" id="full_name" value="<?php echo $full_name; ?>" />
                                            <!-- <label class="form-label">Username</label> -->
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="form-line">
                                            <div class="font-12">Email</div>
                                            <input type="email" class="form-control" name="email" id="email" value="<?php echo $email; ?>" />
                                            <!-- <label class="form-label">Email</label> -->
                                        </div>
                                    </div>

                                    <div class="form-group form-float col-sm-12">
                                        <div class="form-line">
                                            <input type="password" class="form-control" name="password" id="password" required />
                                            <label class="form-label">Password</label>
                                        </div>
                                    </div>

                                    <div class="form-group form-float col-sm-12">
                                        <div class="form-line">
                                            <input type="password" class="form-control" name="repassword" id="repassword" required />
                                            <label class="form-label">Re Password</label>
                                        </div>
                                    </div>

                                    <input type="hidden" name="role" id="role" value="<?php echo $role; ?>" />

                                    <div class="col-sm-12">
                                         <button class="btn bg-blue waves-effect pull-right" type="submit" name="btnEdit">SUBMIT</button>
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