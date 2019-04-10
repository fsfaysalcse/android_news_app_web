<?php
    
    include 'functions.php';

    $sql_user   = "SELECT COUNT(*) as num FROM tbl_fcm_token";
    $total_user = mysqli_query($connect, $sql_user);
    $total_user = mysqli_fetch_array($total_user);
    $total_user = $total_user['num'];

    if (isset($_GET['id'])) {

        $sql = 'SELECT * FROM tbl_fcm_template WHERE id=\''.$_GET['id'].'\'';
        $img_rss = mysqli_query($connect, $sql);
        $img_rss_row = mysqli_fetch_assoc($img_rss);

        if ($img_rss_row['image'] != "") {
            unlink('upload/notification/'.$img_rss_row['image']);
        }

        Delete('tbl_fcm_template','id='.$_GET['id'].'');

        header("location: push-notification.php");
        exit;

    }  

    if (isset($_GET['send_notification_info'])) {

        $qry = "SELECT * FROM tbl_fcm_template WHERE id = '".$_GET['send_notification_info']."'";
        $result = mysqli_query($connect, $qry);
        $row = mysqli_fetch_assoc($result);

        $pesan = $row['message'];
        $id = $row['id'];
        $link = $row['link'];

        $image = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/upload/notification/'.$row['news_image'];

        $users_sql = "SELECT * FROM tbl_fcm_token";

        $users_result = mysqli_query($connect, $users_sql);
        while($user_row = mysqli_fetch_assoc($users_result)) {

            $msg    = $pesan;
            $img    = $image;
            $id     = $id;
            $link   = $link;

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
            $sql_query = "SELECT id, message, image, link FROM tbl_fcm_template ORDER BY id DESC";
        } else {
            $sql_query = "SELECT id, message, image, link FROM tbl_fcm_template WHERE message LIKE ? ORDER BY id DESC";
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
                    $data['id'],
                    $data['message'],
                    $data['image'],
                    $data['link']
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
            $from   = ($page * $offset) - $offset;
        } else {
            //if nothing was given in page request, lets load the first page
            $from = 0;  
        }   
        
        if (empty($keyword)) {
            $sql_query = "SELECT id, message, image, link FROM tbl_fcm_template ORDER BY id DESC LIMIT ?, ?";
        } else {
            $sql_query = "SELECT id, message, image, link FROM tbl_fcm_template WHERE message LIKE ? ORDER BY id DESC LIMIT ?, ?";
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
                $data['id'],
                $data['message'],
                $data['image'],
                $data['link']
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
            <li class="active">Notification</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>MANAGE NOTIFICATION</h2>
                            <div class="header-dropdown m-r--5">
                                <a href="add-notification.php"><button type="button" class="btn bg-blue waves-effect">Add New Template</button></a>
                            </div>
                            <br>
                                <?php echo isset($error['push_notification']) ? $error['push_notification'] : '';?>
                                <?php echo isset($error['delete_notification']) ? $error['delete_notification'] : '';?>
                        </div>

                        <div class="body table-responsive">
                            
                            <form method="get">
                                <div class="col-sm-10">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="keyword" placeholder="Search by name...">
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
                                        <th width="45%">Message</th>
                                        <th width="40%">Url</th>
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
            <li class="active">Notification</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>MANAGE NOTIFICATION</h2>
                            <div class="header-dropdown m-r--5">
                                <a href="add-notification.php"><button type="button" class="btn bg-blue waves-effect">Add New Template</button></a>
                            </div>
                            <br>
                                <?php echo isset($error['push_notification']) ? $error['push_notification'] : '';?>
                                <?php echo isset($error['delete_notification']) ? $error['delete_notification'] : '';?>
                        </div>

                        <div class="body table-responsive">
                            
                            <form method="get">
                                <div class="col-sm-10">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="keyword" placeholder="Search by name...">
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
                                        <th width="45%">Message</th>
                                        <th width="40%">Url</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>

                                <?php 
                                    while ($stmt_paging->fetch()) { ?>
                                        <tr>
                                            <td><?php echo $data['message'];?></td>
                                            <td>
                                                <?php
                                                    if ($data['link'] == '') {           
                                                ?>
                                                    no_url
                                                <?php } else { ?>
                                                    <?php
                                                        $value = $data['link'];
                                                        if (strlen($value) > 50)
                                                            $value = substr($value, 0, 47) . '...';
                                                        
                                                        echo $value;
                                                    ?>
                                                <?php } ?>
                                            </td>
                                            
                                            <td>

                                            <a href="push-notification.php?send_notification_info=<?php echo $data['id'];?>" onclick="return confirm('Send this notification to your users?')">
                                                <i class="material-icons">notifications_active</i>
                                            </a>

                                            <a href="edit-notification.php?id=<?php echo $data['id'];?>">
                                                <i class="material-icons">mode_edit</i>
                                            </a>

                                            <a href="push-notification.php?id=<?php echo $data['id'];?>" onclick="return confirm('Are you sure want to delete this template?')">
                                                <i class="material-icons">delete</i>
                                            </a>

                                        </td>
                                        </tr>
                                <?php 
                                    }
                                ?>
                            </table>

                            <h4><?php $function->doPages($offset, 'push-notification.php', '', $total_records, $keyword); ?></h4>
                            <?php 
                                }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>