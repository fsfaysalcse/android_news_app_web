<?php include 'includes/config.php' ?>
<?php require_once 'roles.php'; ?>

<?php

    $username = $_SESSION['user'];
    $sql_query = "SELECT id, username, email FROM tbl_admin WHERE username = ?";
            
    // create array variable to store previous data
    $data = array();
            
    $stmt = $connect->stmt_init();
    if($stmt->prepare($sql_query)) {
        // Bind your variables to replace the ?s
        $stmt->bind_param('s', $username);          
        // Execute query
        $stmt->execute();
        // store result 
        $stmt->store_result();
        $stmt->bind_result(
            $data['id'],
            $data['username'],
            $data['email']
            );
        $stmt->fetch();
        $stmt->close();
    }
            
?>

<!DOCTYPE html>
<html>
 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Android News App</title>
    <!-- Favicon-->
    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="assets/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="assets/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="assets/plugins/morrisjs/morris.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Wait Me Css -->
    <link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="assets/css/theme.css" rel="stylesheet" />

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="assets/css/time-picker.css" rel="stylesheet" />

     <!-- JQuery DataTable Css -->
    <link href="assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    

    <link href="assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <!-- Latest compiled and minified CSS -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css"> -->

    <!-- Sweetalert Css -->
    <link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
       <!-- Light Gallery Plugin Css -->
    <link href="assets/plugins/light-gallery/css/lightgallery.css" rel="stylesheet">

    <link href="assets/css/sticky-footer.css" rel="stylesheet">

    <link href="assets/css/dropify.css" type="text/css" rel="stylesheet">

    <?php if ($ENABLE_RTL_MODE == 'true') { ?>
    <link href="assets/css/rtl.css" rel="stylesheet">
    <?php } ?>

   <script type="text/javascript">
  tinymce.init({
    selector: '#tinymce',
    fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
    theme: "modern",
    menubar: false,
    plugins: [
            'advlist autolink lists charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime nonbreaking save directionality',
            'emoticons template paste textcolor colorpicker textpattern', 'link image'
        ],
        toolbar1: 'insertfile undo redo | forecolor backcolor | bold italic fontselect fontsizeselect  | alignleft aligncenter alignright alignjustify | outdent indent | media link | image',
        
        image_advtab: true, file_browser_callback: RoxyFileBrowser


  });

  function RoxyFileBrowser(field_name, url, type, win) {
              var roxyFileman = 'fileman/index.html';
              if (roxyFileman.indexOf("?") < 0) {     
                roxyFileman += "?type=" + type;   
              }
              else {
                roxyFileman += "&type=" + type;
              }
              roxyFileman += '&input=' + field_name + '&value=' + win.document.getElementById(field_name).value;
              if(tinyMCE.activeEditor.settings.language){
                roxyFileman += '&langCode=' + tinyMCE.activeEditor.settings.language;
              }
              tinyMCE.activeEditor.windowManager.open({
                 file: roxyFileman,
                 title: 'Image Upload',
                 width: 850, 
                 height: 650,
                 resizable: "yes",
                 plugins: "media",
                 inline: "yes",
                 close_previous: "no"  
              }, {     window: win,     input: field_name    });
              return false; 
            }
  </script>

        
</head>

<body class="theme-blue">

    <!-- Page Loader -->
    <!-- <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader pl-size-xl">
                <div class="spinner-layer pl-blue">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div> -->

    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <!-- <div class="overlay"></div> -->
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
<!--     <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div> -->
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="dashboard.php">ANDROID NEWS APP</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->
                    <li><a href="push-notification.php"><i class="material-icons">notifications</i></a></li>
                    <!-- #END# Call Search -->
                    <!-- Notifications -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="edit-member.php?id=<?php echo $data['id']; ?>"><i class="material-icons">person</i>Profile</a></li>
                            <li><a href="logout.php"><i class="material-icons">power_settings_new</i>Logout</a></li>
                        </ul>
                    </li>
                    <!-- #END# Notifications -->

                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div>
                    <img src="assets/images/ic_launcher.png" width="48" height="48" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $data['username'] ?>
                    </div>
                    <div class="email"><?php echo $data['email'] ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="edit-member.php?id=<?php echo $data['id']; ?>"><i class="material-icons">person</i>Profile</a></li>
                            <li><a href="logout.php"><i class="material-icons">power_settings_new</i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MENU</li>
                    <li class="active">
                        <a href="dashboard.php">
                            <i class="material-icons">dashboard</i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="manage-category.php">
                            <i class="material-icons">view_list</i>
                            <span>Manage Category</span>
                        </a>
                    </li>

                    <li>
                        <a href="manage-news.php">
                            <i class="material-icons">library_books</i>
                            <span>Manage News</span>
                        </a>
                    </li>

                    <li>
                        <a href="push-notification.php">
                            <i class="material-icons">notifications</i>
                            <span>Notification</span>
                        </a>
                    </li>

                    <li>
                        <a href="registered-user.php">
                            <i class="material-icons">verified_user</i>
                            <span>Registered Users</span>
                        </a>
                    </li>

                    <li>
                        <a href="members.php">
                            <i class="material-icons">people</i>
                            <span>Administrators</span>
                        </a>
                    </li>

                    <li>
                        <a href="settings.php">
                            <i class="material-icons">settings</i>
                            <span>Settings</span>
                        </a>
                    </li>

                    <li>
                        <a href="logout.php">
                            <i class="material-icons">power_settings_new</i>
                            <span>Logout</span>
                        </a>
                    </li>

                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    Copyright &copy; 2018 <a href="https://www.solodroid.co.id" target="_blank">Solodroid Developer</a>
                </div>
                <div class="version">
                    <b>Version: </b> 3.0.0
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        
    </section>

    