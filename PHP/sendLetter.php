<?php
session_start();
// From data recieved from the user
  $GoogleAPIKey="AIzaSyCn95PEuL7ymK2DkHMYHj9VYUkdgqxwSHM";  
  $from_name= $_POST["name"];
  $from_address_line1= $_POST["address1"];
  $from_address_line2= $_POST["address2"];     
  $from_city= $_POST["city"];
  $from_state= $_POST["state"];
  $from_country= $_POST["country"];
  $from_zipcode= $_POST["code"];
  $message= $_POST["message"];

  // Url for the Google API

  $url='https://www.googleapis.com/civicinfo/v2/representatives?key='.$GoogleAPIKey.'&address='.$from_city;

 // Call the LOB letter API using CURL 
  //ob_start();
  $curl_1 = curl_init();
  curl_setopt($curl_1, CURLOPT_URL,$url);
  curl_setopt($curl_1, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl_1, CURLOPT_VERBOSE, 1);
  curl_setopt($curl_1, CURLOPT_HEADER, true);    // we want headers
  curl_setopt($curl_1, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($curl_1); 
    $header_size = curl_getinfo($curl_1,CURLINFO_HEADER_SIZE); 
    $httpcode_1 = curl_getinfo($curl_1, CURLINFO_HTTP_CODE);
    
    $body = substr($response, $header_size);
    //echo $body;
    //$screen_dump = ob_get_clean();
    $obj = json_decode($body,true);
    if(isset($obj["officials"][5]["name"]))
    {
      $to_name=$obj["officials"][5]["name"];
      $to_address_line1=$obj["officials"][5]["address"][0]["line1"];
          if(isset($obj["officials"][5]["address"][0]["line2"]))
              $to_address_line2=$obj["officials"][5]["address"][0]["line2"];
           else
              $to_address_line2=''; 
      $to_city=$obj["officials"][5]["address"][0]["city"];
      $to_state=$obj["officials"][5]["address"][0]["state"];   
      $to_zipcode=$obj["officials"][5]["address"][0]["zip"];
      $to_country='US'; 

    }
    else
    {
    
      if($httpcode_1 == '400')
               $error   = "Error Code: ".$httpcode_1 ." - Bad Request";
      else if($httpcode_1 == '401')
               $error   = "Error Code: ".$httpcode_1 ." -Invalid Credentials";
        else if($httpcode_1 == '403')       
              $error   = "Error Code: ".$httpcode_1 ." -The user does not have sufficient permissions for file";
        else if($httpcode_1 == '404')       
              $error   = "Error Code: ".$httpcode_1 ." -File not found";
        else if($httpcode_1 == '429')       
              $error   = "Error Code: ".$httpcode_1 ." -Too Many Requests";
        else if($httpcode_1 == '500')       
              $error   = "Error Code: ".$httpcode_1 ." -Backend Error";   
       header("location: error.php?error=".$error);
       exit();
    }
    
    
  curl_close($curl_1);



 // Call the LOB letter API using CURL 
  try{
  //ob_start();
  $curl = curl_init();
  $username="test_75ccd35a5182737c5af2d890cee7ab691ff";
  $password="";
  $data =['description'   => 'Demo Letter',
  'to[name]'              => $to_name,
  'to[address_line1]'     => $to_address_line1,
  'to[address_line2]'     => $to_address_line2,
  'to[address_city]'      => $to_city,
  'to[address_zip]'       => $to_zipcode,
  'to[address_state]'     => $to_state,
  'to[address_country]'   => $to_country,
  'from[name]'            => $from_name,
  'from[address_line1]'   => $from_address_line1,
  'from[address_line2]'   => $from_address_line2,
  'from[address_city]'    => $from_city,
  'from[address_zip]'     => $from_zipcode,
  'from[address_state]'   => $from_state,
  'from[address_country]' => $from_country,
  'file'                  => '<html style="padding-top: 3in; margin: .5in;">'.$message. '</html>',
  'color'                 => true];
  curl_setopt($curl, CURLOPT_URL, 'https://api.lob.com/v1/letters');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_VERBOSE, 1);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_HEADER, true);    // we want headers
  //curl_setopt($curl, CURLOPT_NOBODY, true);    // we don't need body
  curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
 
  $resp = curl_exec($curl);
    $header_size_1 = curl_getinfo($curl,CURLINFO_HEADER_SIZE); 
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    $body_1 = substr($resp, $header_size_1);
  
  //$screen_dump_1 = ob_get_clean();
  
$obj = json_decode($body_1,true);

if(isset($obj["url"]))
{
$url= $obj["url"];  
header("Refresh: 1;url=$url");
exit();
}
      
else
{       if($httpcode == '401')
               $error   = "Error Code: ".$httpcode ." -Authorization error with your API key or account";
        else if($httpcode == '403')       
              $error   = "Error Code: ".$httpcode ." -Forbidden error with your API key or account";
        else if($httpcode == '404')       
              $error   = "Error Code: ".$httpcode ." -The requested item does not exist";
        else if($httpcode == '422')       
              $error   = "Error Code: ".$httpcode ." -Some sort of error with the provided inputs";
        else if($httpcode == '429')       
              $error   = "Error Code: ".$httpcode ." -The user has sent too many requests in a given amout of time";
        else if($httpcode == '500')       
              $error   = "Error Code: ".$httpcode ." -Something is wrong on Lob's end";    
                          
        header("location: error.php?error=".$error);
        exit();
}

}
 catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

curl_close($curl);

?>