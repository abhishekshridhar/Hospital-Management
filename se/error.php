<?php
require("include/dbinfo.php");

mysqli_query($con,"delete from Session");
?>