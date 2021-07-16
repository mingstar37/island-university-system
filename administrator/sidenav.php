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
                            <a href="./courses.php">Courses</a>
                        </li>
                        <li>
                            <a href="viewDepartments.php">View</a>
                        </li>
                        <li>
                            <a href="addDepartment.php">Add</a>
                        </li>
                        <li>
                            <a href="editDepartment.php">Edit</a>
                        </li>
                        <li>
                            <a href="departments.php">Delete</a>
                        </li>

                    </ul>
                </li>
                <li>
                    <a href="#/">Course</a>
                    <ul class="nolist">
                        <li>
                            <a href="addCourse.php">Add</a>
                        </li>
                        <li>
                            <a href="scheduleCourse.php">Create Section</a>
                        </li>
                        <li>
                            <a href="editCourse.php">Edit</a>
                        </li>
                        <li>
                            <a href="courses.php">Delete</a>
                        </li>

                    </ul>
                </li>

                <li>
                    <a href="#"> Prerequisite </a>
                    <ul class="nolist">
                        <li>
                            <a href="adminViewPrerequisite.php">View Prerequisite</a>
                        </li>
                        <li>
                            <a href="addPrerequisite.php">Add</a>
                        </li>
                        <li>
                            <a href="removePrerequisite.php">Remove</a>
                        </li>

                    </ul>
                </li>

                <li>
                    <a href="#">Student</a>
                    <ul class="nolist">
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
                            <a href="adminViewStudentMajor.php">View Student Major</a>
                        </li>
                        <li>
                            <a href="adminAddStudentMajor.php">Add Student Major</a>
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
                        <li>
                            <a href="adminAddAdvisor.php">Add Student Advisor</a>
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
                            <a href="adminViewFaculty.php">View Faculty</a>
                        </li>
                        <li>
                            <a href="adminViewFacultyDepartment.php">View Faculty Departments</a>
                        </li>
                        <li>
                            <a href="adminAddFacultyDepartment.php">Add Faculty Departments</a>
                        </li>
                        <!-- <li>
                            <a href="adminFacultyStudentHistory.php">View Student History</a>
                        </li> -->
                        <li>
                            <a href="adminFacultyresetPassword.php">Reset Password</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">Advisees</a>
                    <ul class="nolist">
                        <li>
                            <a href="viewAdvisor.php">View Advisees</a>
                        </li>
                        <li>
                            <a href="adminAddAdvisor.php">Add Advisor</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">Student History</a>
                    <ul class="nolist">
                        <li>
                            <a href="viewStudentHistories.php">All Histories</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </aside>
</div>
