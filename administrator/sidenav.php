<div class="sidenav">
    <aside class="col-xs-12 col-md-12 col-lg-12 side-menu">
        <nav class="left-nav hidden-xs hidden-sm hidden-md">
            <ul class="nolist">
                <li class="nolistHighlight image-container">
                    <a href="profile.php">Hello Admin <?php echo $_SESSION["user_email"] ?></a>
                </li>
                <li>
                    <a href="#">Users</a>
                    <ul class="nolist">
                        <li>
                            <a href="./students.php">Student</a>
                        </li>
                        <li>
                            <a href="./faculties.php">Faculties</a>
                        </li>
                        <li>
                            <a href="./researchers.php">Researchers</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"> Department </a>
                    <ul class="nolist">
                        <li>
                            <a href="./departments.php">Departments</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#/">Course</a>
                    <ul class="nolist">
                        <li>
                            <a href="./courses.php">Courses</a>
                        </li>
                        <li>
                            <a href="./sections.php">Sections</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#"> Prerequisites </a>
                    <ul class="nolist">
                        <li>
                            <a href="./prerequisites.php">Prerequisite</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#">Student</a>
                    <ul class="nolist">
                        <li>
                            <a href="./student-courses.php">Student - Courses</a>
                        </li>
                        <li>
                            <a href="./student-majors.php">Student - Majors</a>
                        </li>
                        <li>
                            <a href="./student-minors.php">Student - Minors</a>
                        </li>
                        <li>
                            <a href="adminViewStudent.php">View Student</a>
                        </li>
                        <li>
                            <a href="adminEditStudent.php">Edit Student</a>
                        </li>
                        <li>
                            <a href="adminViewStudentCourse.php">View Courses</a>
                        </li>
                        <li>
                            <a href="adminAddStudentCourse.php">Add Course</a>
                        </li>
                        <li>
                            <a href="viewStudentMinor.php">View Student Minor</a>
                        </li>
                        <li>
                            <a href="adminAddStudentMinor.php">Add Student Minor</a>
                        </li>
                        <li>
                            <a href="adminRemoveStudentMinor.php">Remove Student Minor</a>
                        </li>
                        <li>
                            <a href="adminAddHold.php">Add Hold</a>
                        </li>
                        <li>
                            <a href="removeHold.php">Remove Hold</a>
                        </li>
                        <li>
                            <a href="adminViewStudentHold.php">View Student Hold</a>
                        </li>
                        <li>
                            <a href="adminAddStudentHold.php">Add Student Hold</a>
                        </li>
                        <li>
                            <a href="adminRemoveStudentHold.php">Remove Student Hold</a>
                        </li>
                        <li>
                            <a href="adminviewAdvisor.php">View Student Advisor</a>
                        </li>

                    </ul>
                </li>
                <li>
                    <a href="#">Time Slots</a>
                    <ul class="nolist">
                        <li>
                            <a href="dueDates.php">Due Dates</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#">Faculty</a>
                    <ul class="nolist">
                        <li>
                            <a href="./faculty_departments.php">Faculty - Departments</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">Advisees</a>
                    <ul class="nolist">
                        <li>
                            <a href="./advisees.php">Advisees</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">Student History</a>
                    <ul class="nolist">
                        <li>
                            <a href="./student_histories.php">All Histories</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </aside>
</div>
