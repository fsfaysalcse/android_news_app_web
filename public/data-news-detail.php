<?php 
		if(isset($_GET['id'])) {
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
			
		// create array variable to handle error
		$error = array();
			
		// create array variable to store data from database
		$data = array();
		
		// get data from reservation table
		$sql_query = "SELECT * FROM tbl_news WHERE nid = ?";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result(
					$data['nid'], 
					$data['cat_id'],
					$data['news_title'],
					$data['news_date'],
					$data['news_description'],
					$data['news_image'],
					$data['video_url'],
					$data['video_id'],
					$data['content_type'],
					$data['size']
					);
			$stmt->fetch();
			$stmt->close();
		}


		$sql_query2 = "SELECT * FROM tbl_news n, tbl_comments c, tbl_users u WHERE n.nid = c.nid AND c.user_id = u.id AND n.nid = '".$_GET['id']."'";
		$hasil = mysqli_query($connect, $sql_query2);

		$sql_comments = "SELECT COUNT(*) as num FROM tbl_news n, tbl_comments c WHERE n.nid = c.nid AND n.nid = '".$_GET['id']."'";
  		$total_comments = mysqli_query($connect, $sql_comments);
  		$total_comments = mysqli_fetch_array($total_comments);
  		$total_comments = $total_comments['num'];
			
	?>

	<section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage-news.php">Manage News</a></li>
            <li class="active">News Detail</a></li>
        </ol>

        <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                	<form method="post">
                	<div class="card">
                        <div class="header">
                            <h2>NEWS DETAIL</h2>
                        </div>
                        <div class="body">

                        	<div class="row clearfix">
                        	<div class="form-group form-float col-sm-12">
                        		<p>
									<h4>
										<?php echo $data['news_title']; ?>
										<a href="edit-news.php?id=<?php echo $data['nid'];?>"><i class="material-icons">mode_edit</i></a>
										<a href="delete-news.php?id=<?php echo $data['nid'];?>" onclick="return confirm('Are you sure want to delete this News?')" ><i class="material-icons">delete</i></a>
									</h4>
								</p>
								<p>
									<?php echo $data['news_date']; ?> 

								</p>

								<?php if ($data['content_type'] == 'youtube') { ?>
									<p><img style="max-width:40%" src="https://img.youtube.com/vi/<?php echo $data['video_id'];?>/mqdefault.jpg" ></p>
					            <?php } else { ?>
					            	<p><img style="max-width:40%" src="upload/<?php echo $data['news_image']; ?>" ></p>
					            <?php } ?>

								<p><?php echo $data['news_description']; ?></p>
								
                	</form>

								<hr>
							<p><b>Comments ( <?php echo $total_comments;?> )</b></p>
							<?php
								$total = 0;
								while ($data2 = mysqli_fetch_array($hasil)) {
							?>
							<div>
							<table>
								<tr>
									<td><b><?php echo $data2['name']; ?></b></td>
									<td><a href="delete-comment.php?id=<?php echo $data2['comment_id'];?>" onclick="return confirm('Are you sure want to delete this comment?')" ><i class="material-icons">delete</i></a></td>
								</tr>

								<tr>
									<td><?php echo $data2['date_time']; ?></td>
								</tr>

								<tr>
									<td><?php echo $data2['content']; ?></td>
								</tr>


							</table>
					            	
				           	</div>
				           	<br>

				            <?php } ?>

							</div>
                        	</div>
                        </div>
                    </div>

                </div>

            </div>
            
        </div>

    </section>