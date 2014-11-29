@@ -1,157 +0,0 @@
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php mb_http_input("utf-8");mb_http_output("utf-8");require("cp/viset.php");require("cp/connect.php");?>
<script src="js/jquery.js" type="text/javascript">
</script>
<link href="styles/index.css" rel='stylesheet' type='text/css' />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/fonts/NeoSansArabic.css" rel='stylesheet' type='text/css' />
<title>
<?php 

		$sql="SELECT * FROM title WHERE id=1";

        $query=mysqli_query($con,$sql);

        while($row=mysqli_fetch_array($query)) 
        {

        echo $row['title'];
        }

?>
</title>
</head>

<body>

<style>

html{
	background: url(
	<?php
	$sql19="SELECT * FROM background WHERE id=1";
	
	$query19=mysqli_query($con,$sql19);

	while($row19 = mysqli_fetch_array($query19)){
	
    echo $row19['url'];
		
	}
	?>
	);
	background-size:cover;
}

</style>

<?php		
		
        if($_POST['submit']){
        echo "
        <style>
        #shortform{
        padding-top: 14%; 
        margin-top: -5%;
        }
        </style>
        ";
		
        $link=$_POST['link'];


        function GetShort($url){
        $data = array("longUrl" => $url); // بيانات json                                                                    
        $data_string = json_encode($data);  // تحول ال json ال string                                                                         
        $ch = curl_init('https://www.googleapis.com/urlshortener/v1/url'); // رابط ال api                                                                  
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);       
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST , false);           
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); // ارسال الjson                                                                
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(  // ارسال ال headers                                                                        
                'Content-Type: application/json',                                                                                
    )                                                                       
    );                                                                                                                   
        $result = json_decode(curl_exec($ch),true);// تحويل البيانات الي json
        curl_close($ch); // اغلاق الاتصال
        if($result[id]) // لا يوجد مشاكل
            return $result[id];
    }
	
	$urlshorten=GetShort("$link"); //الإستخدام

    $date = date("D-M-Y"); //الوقت
    
	if(!$urlshorten==""){
		
    $insertsystem="INSERT INTO link (longurl, shorturl, date)
    VALUES ('$link', '$urlshorten','$date')"; //إدخال بيانات الرابط للقاعدة

    
    mysqli_query($con,$insertsystem);
	
    }


    }

?>


<center>

<div id="shortform" style="
    padding: 12%;
">
<?php

if($_POST['submit']){


if(!$link == ""){

if(!$urlshorten== ""){

echo "<p class='ok'>
$urlshorten
</p>";
	
}

else{

	echo "<p class='no'>
يرجى التحقق من وجود الاتصال بالإنترنت  او اعادة تحميل الصفحة	</p>";
	
}

}

else{
echo "<p class='no'>
	يرجى إدخال الرابط
	</p>";
}

}

?>

<img id="logo" src="cp/logo.php"  />

<form style="padding: 1%;" action="<?php echo $PHP_SELF ?>" method="post">

<p><input id="linkinput" type="url" class="textbox2" placeholder="الرابط" name="link" /></p>
<input style="color:#fff;" type="submit" value="إختصار الرابط" name="submit" id="submit" />

</form>

</div>

</center>

</body>
</html>
