<?php 
include ('function.php');
os.system('clear');
echo "
      ██╗   ██╗██████╗ ███████╗
      ╚██╗ ██╔╝██╔══██╗██╔════╝
       ╚████╔╝ ██║  ██║█████╗  
        ╚██╔╝  ██║  ██║██╔══╝  
         ██║   ██████╔╝██║     
         ╚═╝   ╚═════╝ ╚═╝     
\n";
echo "\e[89m▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬\n";


$ydf = new ydf();
/** 
@ step 1
return @type json contain <otpToken> 
*/
echo "NOMOR: ";
$phoneNumber = trim(fgets(STDIN));
echo $ydf->loginRequest($phoneNumber); 
/** 
@ step 2
return @type json contain <access_token> 
*/
echo "OTP: ";
$otpCode = trim(fgets(STDIN));
echo "OTP TOKEN: ";
$otpToken = trim(fgets(STDIN));
echo $ydf->getAuthToken($otpToken, $otpCode);
