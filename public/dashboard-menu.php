<?php

  $sql_category = "SELECT COUNT(*) as num FROM tbl_category";
  $total_category = mysqli_query($connect, $sql_category);
  $total_category = mysqli_fetch_array($total_category);
  $total_category = $total_category['num'];

  $sql_news = "SELECT COUNT(*) as num FROM tbl_news";
  $total_news = mysqli_query($connect, $sql_news);
  $total_news = mysqli_fetch_array($total_news);
  $total_news = $total_news['num'];

  $sql_fcm = "SELECT COUNT(*) as num FROM tbl_fcm_template";
  $total_fcm = mysqli_query($connect, $sql_fcm);
  $total_fcm = mysqli_fetch_array($total_fcm);
  $total_fcm = $total_fcm['num'];

  $sql_user = "SELECT COUNT(*) as num FROM tbl_users";
  $total_users = mysqli_query($connect, $sql_user);
  $total_users = mysqli_fetch_array($total_users);
  $total_users = $total_users['num'];

?>

    <section class="content">

    <ol class="breadcrumb">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li class="active">Home</a></li>
    </ol>

        <div class="container-fluid">
             
             <div class="row">

                <a href="manage-category.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">MANAGE CATEGORY</div>
                            <div class="color-name"><?php echo $total_category; ?></div>
                            <div class="color-class-name">Total Category</div>
                            <br>
                        </div>
                    </div>
                </a>

               <a href="manage-news.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">MANAGE NEWS</div>
                            <div class="color-name"><?php echo $total_news; ?></div>
                            <div class="color-class-name">Total News</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="push-notification.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">NOTIFICATION</div>
                            <div class="color-name"><?php echo $total_fcm; ?></div>
                            <div class="color-class-name">Total Template</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="registered-user.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">REGISTERED USER</div>
                            <div class="color-name"><?php echo $total_users; ?></div>
                            <div class="color-class-name">Total Users</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="members.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">ADMINISTRATOR</div>
                            <div class="color-name"><i class="material-icons">people</i></div>
                            <div class="color-class-name">Admin Panel Privileges</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="settings.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name">SETTINGS</div>
                            <div class="color-name"><i class="material-icons">settings</i></div>
                            <div class="color-class-name">Key and Privacy Settings</div>
                            <br>
                        </div>
                    </div>
                </a>

            </div>
            
        </div>

    </section>