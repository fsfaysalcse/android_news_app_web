<?php 
    include('public/fcm.php');
    require_once("public/thumbnail_images.class.php");
    include_once('functions.php');

    if(isset($_POST['submit'])) {

        $video_id = 'cda11up';

        if($_POST['upload_type'] == 'Upload') {

            $news_image = time().'_'.$_FILES['news_image']['name'];
            $pic2            = $_FILES['news_image']['tmp_name'];
            $tpath2          = 'upload/'.$news_image;
            copy($pic2, $tpath2);

            $video  = time().'_'.$_FILES['video']['name'];
            $pic1   = $_FILES['video']['tmp_name'];
            $tpath1 ='upload/video/'.$video;
            copy($pic1, $tpath1);
            $bytes = $_FILES['video']['size'];

            if ($bytes >= 1073741824) {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            }

            else if ($bytes >= 1048576) {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            }

            else if ($bytes >= 1024) {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            }

            else if ($bytes > 1) {
                $bytes = $bytes . ' bytes';
            }

            else if ($bytes == 1) {
                $bytes = $bytes . ' byte';
            } else {
                $bytes = '0 bytes';
            }
            

        } else if ($_POST['upload_type'] == 'Url') {

            $video = $_POST['url_source'];

            $news_image = time().'_'.$_FILES['image']['name'];
            $pic2            = $_FILES['image']['tmp_name'];
            $tpath2          = 'upload/'.$news_image;
            copy($pic2, $tpath2);

        } else if ($_POST['upload_type'] == 'Post') {

            $news_image = time().'_'.$_FILES['post_image']['name'];
            $pic2            = $_FILES['post_image']['tmp_name'];
            $tpath2          = 'upload/'.$news_image;
            copy($pic2, $tpath2);

        } else {
            $video = $_POST['youtube'];
            $news_image = '';       

            function youtube_id_from_url($url) {

                $pattern = 
                '%^# Match any youtube URL
                (?:https?://)?  # Optional scheme. Either http or https
                (?:www\.)?      # Optional www subdomain
                (?:             # Group host alternatives
                  youtu\.be/    # Either youtu.be,
                | youtube\.com  # or youtube.com
                  (?:           # Group path alternatives
                    /embed/     # Either /embed/
                  | /v/         # or /v/
                  | /watch\?v=  # or /watch\?v=
                  )             # End path alternatives.
                )               # End host alternatives.
                ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
                $%x'
                ;

                $result = preg_match($pattern, $url, $matches);

                if (false !== $result) {
                    return $matches[1];
                }
                return false;

            }

            $video_id = youtube_id_from_url($_POST['youtube']);

        }

                $data = array(

                    'cat_id'            => $_POST['cat_id'],            
                    'news_title'        => addslashes($_POST['news_title']),
                    'video_url'         => $video,                                  
                    'video_id'          => $video_id,
                    'news_image'        => $news_image,                                 
                    'news_date'         => $_POST['news_date'],
                    'news_description'  => addslashes($_POST['news_description']),
                    'content_type'      => $_POST['upload_type'],
                    'size'              => $bytes
                    );      

                      $qry = Insert('tbl_news', $data);                                 
                      
                      $_SESSION['msg'] = "";
                      header( "Location:add-news.php");
                      exit;

    }

    $wall_qry = "SELECT * FROM tbl_category";
    $wall_result = mysqli_query($connect, $wall_qry);

?>

<script type="text/javascript">

    $(document).ready(function(e) {

        $("#upload_type").change(function() {
            var type = $("#upload_type").val();

                if (type == "youtube") {
                    $("#video_upload").hide();
                    $("#video_post").hide();
                    $("#direct_url").hide();
                    $("#youtube").show();
                }

                if (type == "Post") {
                    $("#youtube").hide();
                    $("#video_upload").hide();
                    $("#direct_url").hide();
                    $("#video_post").show();
                }

                if (type == "Url") {
                    $("#youtube").hide();
                    $("#video_upload").hide();
                    $("#video_post").hide();
                    $("#direct_url").show();
                }

                if (type == "Upload") {
                    $("#youtube").hide();
                    $("#video_post").hide();
                    $("#direct_url").hide();
                    $("#video_upload").show();
                }                       
        });

        $( window ).load(function() {
        var type=$("#upload_type").val();

            if (type == "youtube")  {
                $("#video_upload").hide();
                $("#direct_url").hide();
                $("#video_post").hide();
                $("#youtube").show();
            }

            if (type == "Url") {
                $("#youtube").hide();
                $("#video_upload").hide();
                $("#video_post").hide();
                $("#direct_url").show();
            }

            if (type == "Upload") {
                $("#youtube").hide();
                $("#direct_url").hide();
                $("#video_post").hide();
                $("#video_upload").show();
            }

            if (type == "Post") {
                $("#youtube").hide();
                $("#direct_url").hide();
                $("#video_upload").hide();
                $("#video_post").show();
            }

        });

    });

</script>

   <section class="content">
   
        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage-news.php">Manage News</a></li>
            <li class="active">Add News</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                	<form id="form_validation" method="post" enctype="multipart/form-data">
                    <div class="card">
                        <div class="header">
                            <h2>ADD NEWS</h2>
                                <?php if(isset($_SESSION['msg'])) { ?>
                                    <br><div class='alert alert-info'>New News Added Successfully...</div>
                                    <?php unset($_SESSION['msg']); } ?>
                        </div>
                        <div class="body">

                        	<div class="row clearfix">
                                
                                <div class="col-sm-5">

                                    <div class="form-group">
                                        <div class="font-12">News Title</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="news_title" id="news_title" placeholder="News Title" required>
                                        </div>
                                    </div>

                                    <?php
                                        $today = date("Y-m-d H:i:s", time()); 
                                    ?>
                                  	
                                    <div class="form-group">
                                        <div class="font-12">News Date</div>
                                        <div class="form-line">
                                            <input type="text" name="news_date" id="date-format" class="datetimepicker form-control" placeholder="News Date" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="font-12">Category</div>
                                        <select class="form-control show-tick" name="cat_id" id="cat_id">
                                            <?php while ($data = mysqli_fetch_array ($wall_result)) { ?>
                                            <option value="<?php echo $data['cid'];?>"><?php echo $data['category_name'];?></option>
                                                <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <div class="font-12">Content Type</div>
                                        <select class="form-control show-tick" name="upload_type" id="upload_type">
                                                <option value="Post">Standard Post</option>
                                                <option value="youtube">Video Post (YouTube)</option>
                                                <option value="Url">Video Post (Url)</option>
                                                <option value="Upload">Video Post (Upload)</option>
                                        </select>
                                    </div>

                                    <div id="video_post">
                                        <div class="form-group">
                                            <input type="file" name="post_image" id="post_image" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" required />
                                        </div>
                                    </div>
                                    
                                    <div id="youtube">
                                        <div class="form-group">
                                            <div class="font-12">Youtube URL</div>
                                            <div class="form-line">
                                                <input type="url" class="form-control" name="youtube" id="youtube" placeholder="https://www.youtube.com/watch?v=33F5DJw3aiU" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="direct_url">
                                        <div class="form-group">
                                            <input type="file" name="image" id="image" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" />
                                        </div>
                                        <div class="form-group">
                                            <div class="font-12">Video URL</div>
                                            <div class="form-line">
                                                <input type="url" class="form-control" name="url_source" id="url_source" placeholder="http://www.xyz.com/news_title.mp4" required/>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="video_upload">
                                        <div class="form-group">
                                            <input type="file" id="news_image" name="news_image" id="news_image" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" required />
                                        </div>

                                        <div class="form-group">
                                            <input type="file" id="video" name="video" id="video" class="dropify-video" data-allowed-file-extensions="3gp mp4 mpg wmv mkv m4v mov flv" required/>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <textarea class="form-control" name="news_description" id="news_description" class="form-control" cols="60" rows="10" required></textarea>
                                        <script>                             
                                            CKEDITOR.replace( 'news_description' );
                                        </script>
                                    </div>

                                    <button type="submit" name="submit" class="btn bg-blue waves-effect pull-right">PUBLISH</button>
                                    
                                </div>

                            </div>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
            
        </div>

    </section>