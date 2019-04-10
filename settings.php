<?php include('session.php'); ?>
<?php include("public/menubar.php"); ?>
<script src="assets/js/ckeditor/ckeditor.js"></script>

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
	        'app_fcm_key' => $_POST['app_fcm_key'],
            'api_key' => $_POST['api_key'],
	        'privacy_policy' => $_POST['privacy_policy']
	    );

	    $update_setting = Update('tbl_settings', $data, "WHERE id = '1'");

	    if ($update_setting > 0) {
	        $_SESSION['msg'] = "";
	        header( "Location:settings.php");
	        exit;
	    }
	}

?>


    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active">Settings</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                	<form method="post" enctype="multipart/form-data">
                    <div class="card">
                        <div class="header">
                            <h2>SETTINGS</h2>
                            <div class="header-dropdown m-r--5">
                                <button type="submit" name="submit" class="btn bg-blue waves-effect">Save Settings</button>
                            </div>
                                <?php if(isset($_SESSION['msg'])) { ?>
                                    <br><div class='alert alert-info'>Successfully Saved...</div>
                                    <?php unset($_SESSION['msg']); } ?>
                        </div>
                        <div class="body">

                        	<div class="row clearfix">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        Your Server Key
                                        <br>
                                        <a href="" data-toggle="modal" data-target="#modal-server-key">How to obtain your FCM Server Key?</a>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                	
                                    <div class="form-group">
                                        <div class="form-line">
                                            <div class="font-12">FCM Server Key</div>
                                            <textarea class="form-control" rows="3" name="app_fcm_key" id="app_fcm_key" required><?php echo $settings_row['app_fcm_key'];?></textarea>
                                            <!-- <label class="form-label">FCM Server Key</label> -->
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        Your API Key
                                        <br>
                                        <a href="" data-toggle="modal" data-target="#modal-api-key">Where I have to put my API Key?</a>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                	
                                    <div class="form-group">
                                        <div class="form-line">
                                            <div class="font-12">API Key</div>
                                            <input type="text" class="form-control" name="api_key" id="api_key" value="<?php echo $settings_row['api_key'];?>" required>
                                            <!-- <label class="form-label">API Key</label> -->
                                        </div>
                                        <br>
                                        <a href="change-api-key.php" class="btn bg-blue waves-effect">Change API Key</a>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        Privacy Policy
                                        <br>
                                        <i>This privacy policy will be displayed in your android app</i>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    
                                    <div class="form-group">
                                        <div class="form-line">
                                            <div class="font-12">Privacy Policy</div>
                                            <textarea class="form-control" name="privacy_policy" id="privacy_policy" class="form-control" cols="60" rows="10" required><?php echo $settings_row['privacy_policy'];?></textarea>

                                            <?php if ($ENABLE_RTL_MODE == 'true') { ?>
                                            <script>                             
                                                CKEDITOR.replace( 'privacy_policy' );
                                                CKEDITOR.config.contentsLangDirection = 'rtl';
                                            </script>
                                            <?php } else { ?>
                                            <script>                             
                                                CKEDITOR.replace( 'privacy_policy' );
                                            </script>
                                            <?php } ?>

                                        </div>
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


<?php include('public/footer.php'); ?>