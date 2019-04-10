<?php
	include 'functions.php';
	include 'fcm.php';
?>

<?php 

	$sql_user   = "SELECT COUNT(*) as num FROM tbl_fcm_token";
    $total_user = mysqli_query($connect, $sql_user);
    $total_user = mysqli_fetch_array($total_user);
    $total_user = $total_user['num'];

    if (isset($_GET['send_notification_post'])) {

        $qry = "SELECT * FROM tbl_news WHERE nid = '".$_GET['send_notification_post']."'";
        $result = mysqli_query($connect, $qry);
        $row = mysqli_fetch_assoc($result);

        $pesan = $row['news_title'];
        $id = $row['nid'];
        $link = "";

        if ($row['content_type'] == 'youtube') {
			$image = 'http://img.youtube.com/vi/'.$row['video_id'].'/mqdefault.jpg';
        } else {
        	$image = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/upload/'.$row['news_image'];
        }

        $users_sql = "SELECT * FROM tbl_fcm_token";

        $users_result = mysqli_query($connect, $users_sql);
        while($user_row = mysqli_fetch_assoc($users_result)) {

            $msg = $pesan;
            $img = $image;
            $id = $id;
            $link = $link;

            $data = array("title" => $msg, "image" => $img, "id" => $id, "link" => $link);

            echo SEND_FCM_NOTIFICATION($user_row['token'], $data);

        }

        if ($result) {
            $error['push_notification'] = "<div class='alert alert-info'>Congratulations, Push Notification Sent to $total_user Users.</div>";
        } else {
            $error['push_notification'] = "<div>Failed.</div>";
        }
    }

?>

	<?php 
		// create object of functions class
		$function = new functions;
		
		// create array variable to store data from database
		$data = array();
		
		if(isset($_GET['keyword'])) {	
			// check value of keyword variable
			$keyword = $function->sanitize($_GET['keyword']);
			$bind_keyword = "%".$keyword."%";
		} else {
			$keyword = "";
			$bind_keyword = $keyword;
		}
			
		if (empty($keyword)) {
			$sql_query = "SELECT nid, news_title, news_image, news_date, category_name, video_id, content_type FROM tbl_news m, tbl_category c
					WHERE m.cat_id = c.cid  
					ORDER BY m.nid DESC";
		} else {
			$sql_query = "SELECT nid, news_title, news_image, news_date, category_name, video_id, content_type FROM tbl_news m, tbl_category c
					WHERE m.cat_id = c.cid AND news_title LIKE ? 
					ORDER BY m.nid DESC";
		}
		
		
		$stmt = $connect->stmt_init();
		if ($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			if (!empty($keyword)) {
				$stmt->bind_param('s', $bind_keyword);
			}
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result( 
					$data['nid'],
					$data['news_title'],
					$data['news_image'],
					$data['news_date'],
					$data['category_name'],
					$data['video_id'],
					$data['content_type']
					);
			// get total records
			$total_records = $stmt->num_rows;
		}
			
		// check page parameter
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 1;
		}
						
		// number of data that will be display per page		
		$offset = 10;
						
		//lets calculate the LIMIT for SQL, and save it $from
		if ($page) {
			$from 	= ($page * $offset) - $offset;
		} else {
			//if nothing was given in page request, lets load the first page
			$from = 0;	
		}	
		
		if (empty($keyword)) {
			$sql_query = "SELECT nid, news_title, news_image, news_date, category_name, video_id, content_type FROM tbl_news m, tbl_category c
					WHERE m.cat_id = c.cid  
					ORDER BY m.nid DESC LIMIT ?, ?";
		} else {
			$sql_query = "SELECT nid, news_title, news_image, news_date, category_name, video_id, content_type FROM tbl_news m, tbl_category c
					WHERE m.cat_id = c.cid AND news_title LIKE ? 
					ORDER BY m.nid DESC LIMIT ?, ?";
		}
		
		$stmt_paging = $connect->stmt_init();
		if ($stmt_paging ->prepare($sql_query)) {
			// Bind your variables to replace the ?s
			if (empty($keyword)) {
				$stmt_paging ->bind_param('ss', $from, $offset);
			} else {
				$stmt_paging ->bind_param('sss', $bind_keyword, $from, $offset);
			}
			// Execute query
			$stmt_paging ->execute();
			// store result 
			$stmt_paging ->store_result();
			$stmt_paging->bind_result(
				$data['nid'],
				$data['news_title'],
				$data['news_image'],
				$data['news_date'],
				$data['category_name'],
				$data['video_id'],
				$data['content_type']
			);
			// for paging purpose
			$total_records_paging = $total_records; 
		}

		// if no data on database show "No Reservation is Available"
		if ($total_records_paging == 0) {
	
	?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active">Manage News</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>MANAGE NEWS</h2>
                            <div class="header-dropdown m-r--5">
                                <a href="add-news.php"><button type="button" class="btn bg-blue waves-effect">ADD NEW NEWS</button></a>
                            </div>
                        </div>

                        <div class="body table-responsive">
	                        
	                        <form method="get">
	                        	<div class="col-sm-10">
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="form-control" name="keyword" placeholder="Search by title...">
										</div>
									</div>
								</div>
								<div class="col-sm-2">
					                <button type="submit" name="btnSearch" class="btn bg-blue btn-circle waves-effect waves-circle waves-float"><i class="material-icons">search</i></button>
								</div>
							</form>
										
							<table class='table table-hover table-striped'>
								<thead>
									<tr>
										<th width="40%">News Title</th>
										<th width="15%">News Image</th>
										<th width="15%">Date</th>
										<th width="15%">Category</th>
										<th width="15%">Action</th>
									</tr>
								</thead>

								
							</table>

							<div class="col-sm-10">Wopps! No data found with the keyword you entered.</div>

						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

	<?php 
		// otherwise, show data
		} else {
			$row_number = $from + 1;
	?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active">Manage News</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>MANAGE NEWS</h2>
                            <div class="header-dropdown m-r--5">
                                <a href="add-news.php"><button type="button" class="btn bg-blue waves-effect">ADD NEW NEWS</button></a>
                            </div>
                            <br>
                                <?php echo isset($error['push_notification']) ? $error['push_notification'] : '';?>
                        </div>

                        <div class="body table-responsive">
	                        
	                        <form method="get">
	                        	<div class="col-sm-10">
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="form-control" name="keyword" placeholder="Search by title...">
										</div>
									</div>
								</div>
								<div class="col-sm-2">
					                <button type="submit" name="btnSearch" class="btn bg-blue btn-circle waves-effect waves-circle waves-float"><i class="material-icons">search</i></button>
								</div>
							</form>
										
							<table class='table table-hover table-striped'>
								<thead>
									<tr>
										<th width="40%">News Title</th>
										<th width="12%">News Image</th>
										<th width="12%">Date</th>
										<th width="12%">Category</th>
										<th width="10%">Type</th>
										<th width="14%"><center>Action</center></th>
									</tr>
								</thead>

								<?php 
									while ($stmt_paging->fetch()) { ?>
										<tr>
											<td><?php echo $data['news_title'];?></td>

							            	<td>
							            		<?php
													if ($data['content_type'] == 'youtube') {			
										      	?>
										      		<img src="https://img.youtube.com/vi/<?php echo $data['video_id'];?>/mqdefault.jpg" height="48px" width="60px"/>
										      	<?php } else { ?>
							            			<img src="upload/<?php echo $data['news_image'];?>" height="48px" width="60px"/>
							            		<?php } ?>
							            	</td>

											<td>
												<?php 
													$date = strtotime($data['news_date']);
													$new_date = date("F d, Y H:i:s", $date);
													echo $new_date; 
												?>
											</td>
											<td><?php echo $data['category_name'];?></td>
											<td>
                                                <?php if ($data['content_type'] == 'Post') { ?>
                                                    <span class="label bg-green">NEWS</span>
                                                 <?php } else { ?>
                                                    <span class="label bg-red">VIDEO</span>
                                                <?php } ?>	
											</td>
											<td><center>
												<a href="manage-news.php?send_notification_post=<?php echo $data['nid'];?>" onclick="return confirm('Send this notification to your users?')">
                                                <i class="material-icons">notifications_active</i>
                                            	</a>

									            <a href="news-detail.php?id=<?php echo $data['nid'];?>">
									                <i class="material-icons">launch</i>
									            </a>

									            <a href="edit-news.php?id=<?php echo $data['nid'];?>">
									                <i class="material-icons">mode_edit</i>
									            </a>
									                        
									            <a href="delete-news.php?id=<?php echo $data['nid'];?>" onclick="return confirm('Are you sure want to delete this News?')" >
									                <i class="material-icons">delete</i>
									            </a></center>
									        </td>
										</tr>
								<?php 
									}
								?>
							</table>

							<h4><?php $function->doPages($offset, 'manage-news.php', '', $total_records, $keyword); ?></h4>
							<?php 
								}
							?>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>