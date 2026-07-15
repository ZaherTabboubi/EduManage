<div class="sidebar">

    <div class="brand">
        <img src="../../assets/images/logo.jpg" class="logo-img">
        <span>EduManage</span>
    </div>


    <ul class="menu">


        <li>
            <a href="/edumanage/users/admin/dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-line"></i>
                Dashboard
            </a>
        </li>
        <li><a href="profile.php">
<i class="fa-solid fa-user"></i>
Profile
</a></li>


        <li>
            <a href="/edumanage/users/admin/students.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'students.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-graduate"></i>
                Students
            </a>
        </li>



        <li>
            <a href="/edumanage/users/admin/teachers.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'teachers.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chalkboard-user"></i>
                Teachers
            </a>
        </li>



        <li>
            <a href="/edumanage/users/admin/classes.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'classes.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-school"></i>
                Classes
            </a>
        </li>



        <li>
            <a href="/edumanage/users/admin/subjects.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'subjects.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-book"></i>
                Subjects
            </a>
        </li>



        <li>
            <a href="/edumanage/users/admin/attendance.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'attendance.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-calendar-check"></i>
                Attendance
            </a>
        </li>



        <li>
            <a href="/edumanage/users/admin/attendance_history.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'attendance_history.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-clock"></i>
                Attendance History
            </a>
        </li>



        <li>
            <a href="/edumanage/users/admin/attendance_stats.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'attendance_stats.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-line"></i>
                Attendance Statistics
            </a>
        </li>



        <li>
            <a href="/edumanage/users/admin/grades.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'grades.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-lines"></i>
                Grades
            </a>
        </li>
        <li>

<a href="/EduManage/users/admin/schedule.php">

<i class="fa-solid fa-calendar-days"></i>

Schedule

</a>
<a href="/EduManage/users/admin/view_schedule.php">

    <i class="fa-solid fa-calendar-week"></i>

    View Schedule

</a>

</li>



        <li>
            <a href="/edumanage/users/admin/reports.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-pie"></i>
                Reports
            </a>
        </li>



        <li>
            <a href="/edumanage/users/admin/settings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-gear"></i>
                Settings
            </a>
        </li>


    </ul>



    <a href="/edumanage/logout.php" class="logout">

        <i class="fa-solid fa-right-from-bracket"></i>

        Logout

    </a>


</div>