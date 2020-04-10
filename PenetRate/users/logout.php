<?php
session_start();
if (session_destroy()) {
   header("Location: ../users/login.html");
}
