<div class="sidenav">
            <aside class="col-xs-12 col-md-12 col-lg-12 side-menu">
                <nav class="left-nav hidden-xs hidden-sm hidden-md">
                    <ul class="nolist">
                        <li class="nolistHighlight image-container">
                            <a href="profile.php">Hello Student <?php echo $_SESSION["uemail"] ?></a>
                        </li>
                        <li>
                            <a href="index.php">Home</a>
                        </li>
                        <li>
                            <a href="viewClass.php">Schedule</a>
                        </li>
                        <li>
                            <a href="history.php"> View Transcript </a>
                        </li>
                        <li>

                            <?php
                                $sqlHolds = "SELECT * FROM student_holds WHERE Student_ID = '". $_SESSION["uId"]."'";
                                $queryHolds = mysqli_query($conn, $sqlHolds);
                                $rowHolds = mysqli_num_rows($queryHolds);
                                if($rowHolds == 0){
                                   echo' <a href="addDropClass.php">Add/Drop Class</a>';
                                }else{
                                    echo' <a onclick="alert(\'Cannot access this as you have holds\')">Add/Drop Class</a>';
                                }
                            ?>
                            
                        </li>
                        <!-- <li>
                            <a href="schedule.php">View Schedule</a>
                        </li> -->
                        <li>
                            <a href="holds.php">View Holds</a>
                        </li>
                        <!-- <li>
                            <a href="timeslot.php">Time Slots</a>
                        </li> -->
                        <li>
                            <a href="viewmajor.php">View Major</a>
                        </li>
                        <li>
                            <a href="viewminor.php">View Minor</a>
                        </li>
                        <li>
                            <a href="viewadvisor.php">View Advisor</a>
                        </li>
                        <li>
                            <a href="viewStudentHistories.php">Student History</a>
                        </li>
                    </ul>
                </nav>
            </aside>
        </div>