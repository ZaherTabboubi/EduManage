<?php
// Prevent errors if session data is missing

$username = "Administrator";

if(isset($_SESSION['user'])){

    if(isset($_SESSION['user']['name'])){

        $username = $_SESSION['user']['name'];

    } elseif(isset($_SESSION['user']['full_name'])){

        $username = $_SESSION['user']['full_name'];

    }

}

?>



<div class="topbar">


<div class="profile">

<button type="button" class="dark-toggle">

<i class="fa-solid fa-moon"></i>

</button>


<div class="user">

<span>
<?= htmlspecialchars($username); ?>
</span>

<small>
Administrator
</small>

</div>


</div>



<?php if(!isset($hideSearch) || $hideSearch == false){ ?>

<form class="search" action="../../search.php" method="GET">

<i class="fa-solid fa-search"></i>

<input 
type="text"
name="q"
placeholder="Search students, teachers, classes..."
>

</form>

<?php } ?>


</div>