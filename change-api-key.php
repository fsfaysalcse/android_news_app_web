<?php include('session.php'); ?>
<?php include("public/menubar.php"); ?>

<?php

include('public/fcm.php');

    $qry = "SELECT * FROM tbl_settings where id = '1'";
    $result = mysqli_query($connect, $qry);
    $settings_row = mysqli_fetch_assoc($result);

    if(isset($_POST['submit'])) {

        $sql_query = "SELECT * FROM tbl_settings WHERE id = '1'";
        $img_res = mysqli_query($connect, $sql_query);
        $img_row=  mysqli_fetch_assoc($img_res);

        $data = array(
            'api_key' => $_POST['api_key']
        );

        $news_edit = Update('tbl_settings', $data, "WHERE id = '1'");

        if ($news_edit > 0) {
            $_SESSION['msg'] = "9";
            header( "Location:settings.php");
            exit;
        }
    }

?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="settings.php">Settings</a></li>
            <li class="active">Change API Key</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <form method="post" enctype="multipart/form-data">
                        <div class="card">
                            <div class="header">
                                <h2>CHANGE API KEY</h2>
                                <div class="header-dropdown m-r--5">
                                    <button type="submit" name="submit" class="btn bg-blue waves-effect" onclick="return confirm('Are you sure want to update API Key?')">Update API Key</button>
                                </div>
                                <br>

                                <?php if(isset($_SESSION['msg'])) { ?>
                                <div class='alert alert-info'>
                                    <?php echo $message[$_SESSION['msg']] ; ?>
                                </div>
                                <?php unset($_SESSION['msg']); }?>
                            </div>

                            <div class="body">

                                <div class="row clearfix">

                                    <div class="col-sm-2">
                                        <a class="btn bg-blue waves-effect" href="change-api-key.php?generate=true">Generate</a>
                                    </div>

                                    <?php

                                        function generate_password($chars = 45) {
                                            $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                                            return substr(str_shuffle($characters), 0, $chars);
                                        }

                                        $random_api_key = generate_password();

                                        if (isset($_GET['generate'])) {

                                    ?>

                                    <div class="col-sm-10">
                                            
                                        <div class="form-group">
                                            <div class="form-line">
                                                <div class="font-12">Change API Key</div>
                                                <input type="text" class="form-control" name="api_key" id="api_key" value="cda11<?php echo $random_api_key;?>" required />
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                        }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            
        </div>

    </section>


<?php include('public/footer.php'); ?>