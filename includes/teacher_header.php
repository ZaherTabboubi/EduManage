<?php

$username="Teacher";


if(isset($_SESSION['user'])){


if(isset($_SESSION['user']['name'])){


$username=$_SESSION['user']['name'];


}elseif(isset($_SESSION['user']['full_name'])){


$username=$_SESSION['user']['full_name'];


}


}



?>





<div class="topbar">





<div class="profile">













<button class="dark-toggle">


<i class="fa-solid fa-moon"></i>


</button>










<div class="user">


<span>

<?= htmlspecialchars($username); ?>

</span>



<small>

Teacher

</small>



</div>






</div>





</div>