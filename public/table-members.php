<?php
    include 'fcm.php';
    include 'functions.php';
?>

<?php

  //user role
    $roles = array(
        '100' => 'Super Admin',
        '101' => 'Admin',
        '102' => 'Moderator',
    );

    //$sql_query = "SELECT * FROM tbl_admin ORDER BY id DESC";
    //$result = mysqli_query($connect, $sql_query);

 ?>

 <?php

    if (isset($_GET['id'])) {

        $sql = 'SELECT * FROM tbl_admin WHERE id=\''.$_GET['id'].'\'';
        $result = mysqli_query($connect, $sql);

        Delete('tbl_admin','id='.$_GET['id'].'');

        header("location: members.php");
        exit;

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
            $sql_query = "SELECT id, username, email, full_name FROM tbl_admin ORDER BY id DESC";
        } else {
            $sql_query = "SELECT id, username, email, full_name FROM tbl_admin WHERE username LIKE ? ORDER BY id DESC";
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
                    $data['username'],
                    $data['email'],
                    $data['full_name']
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
            $sql_query = "SELECT id, username, email, full_name FROM tbl_admin ORDER BY id DESC LIMIT ?, ?";
        } else {
            $sql_query = "SELECT id, username, email, full_name FROM tbl_admin WHERE username LIKE ? ORDER BY id DESC LIMIT ?, ?";
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
                $data['username'],
                $data['email'],
                $data['full_name']
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
            <li class="active">Manage Administrator</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>MANAGE ADMINISTRATOR</h2>
                            <div class="header-dropdown m-r--5">
                                <a href="add-member.php"><button type="button" class="btn bg-blue waves-effect">ADD NEW ADMINISTRATOR</button></a>
                            </div>
                        </div>

                        <div class="body table-responsive">
                            
                            <form method="get">
                                <div class="col-sm-10">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="keyword" placeholder="Search by username...">
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
                                        <th>Username</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
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
            <li class="active">Manage Administrator</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>MANAGE ADMINISTRATOR</h2>
                            <div class="header-dropdown m-r--5">
                                <a href="add-member.php"><button type="button" class="btn bg-blue waves-effect">ADD NEW ADMINISTRATOR</button></a>
                            </div>
                        </div>

                        <div class="body table-responsive">
                            
                            <form method="get">
                                <div class="col-sm-10">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="keyword" placeholder="Search by username...">
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
                                        <th>Username</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>

                                <?php 
                                    while ($stmt_paging->fetch()) { ?>
                                        <tr>
                                            <td><span class="label bg-green"><?php echo $data['username'];?></span></td>
                                            <td><?php echo $data['full_name'];?></td>
                                            <td><?php echo $data['email'];?></td>
                                            <td>
                                                <a href="edit-member.php?id=<?php echo $data['id']; ?>">
                                                    <i class="material-icons">mode_edit</i>
                                                </a>
                                                
                                                <?php
                                                    if ($data['id'] == 1) {

                                                    } else {
                                                ?>
                                                    <a href="members.php?id=<?php echo $data['id'];?>" onclick="return confirm('Are you sure want to delete this user?')" >
                                                        <i class="material-icons">delete</i>
                                                    </a>
                                                <?php
                                                    }
                                                ?>

                                            </td>
                                        </tr>
                                <?php 
                                    }
                                ?>
                            </table>

                            <h4><?php $function->doPages($offset, 'members.php', '', $total_records, $keyword); ?></h4>
                            <?php 
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>