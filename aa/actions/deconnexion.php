<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/04/2018
 * Time: 13:30
 */

include "../init.php";
session_destroy();
header("Location: ../index.php");