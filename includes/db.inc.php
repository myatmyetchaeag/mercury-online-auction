<?php
    $host= "localhost";
    $database = "mercuryonlineauction";
    $db_user = "root";
    $db_password = "";
    
    $imagepath = $_SERVER["DOCUMENT_ROOT"]."/MercuryOnlineAuction/images/items/"; 
    
function html($text)
{
  return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function htmlout($text)
{
  echo html($text);
}

    
    
    

