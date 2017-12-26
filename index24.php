<?php


if(isset($_POST['submit']))
{

$url=$_POST['url'];
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_scraped_page = curl_exec($ch);
curl_close($ch);


$htmlAsString=$curl_scraped_page;



$doc = new DOMDocument();
libxml_use_internal_errors(true);
//var_dump(libxml_use_internal_errors(true));//enable error handle
$doc->loadHTML($htmlAsString);
$xpath = new DOMXPath($doc);
$nodeList = $xpath->query('//a/@href');
//$nodeList=$xpath->query('//div[contains(@class, "id-app-title")]'
$data_array='';
$unic_array[]='';
for ($i = 0; $i < $nodeList->length; $i++) 
{
	//echo $nodeList->item($i)->value. "<br/>\n";;
	if (preg_match('/state/',$nodeList->item($i)->value))
	{
              // echo $nodeList->item($i)->value . "<br/>\n";
				
				 $url2 = "http://zeenews.india.com".$nodeList->item($i)->value;
				 $data_array[$i]['url']=$url2;
				$ch2 = curl_init($url2);
				curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
				$curl_scraped_page_inner = curl_exec($ch2);
				if($curl_scraped_page_inner!=''):
				curl_close($ch2);
				$htmlAsString_inner=$curl_scraped_page_inner;

				$doc2 = new DOMDocument();
				$doc2->loadHTML($htmlAsString_inner);
				$xpath2 = new DOMXPath($doc2);
				 foreach ($xpath2->query('//h1[contains(@class, "article-heading margin-bt10px")]') as $div)
				 {
					$data_array[$i]['title']=$div->nodeValue ;
					//echo $div->nodeValue;
				 } 
				 foreach ($xpath2->query('//div[contains(@class, "article")]') as $div)
				 {
					$data_array[$i]['content']=$div->nodeValue ;
					//echo $div->nodeValue;
				 } 
	            endif;
	}
	/* if (preg_match('/sports/',$nodeList->item($i)->value))
	{
    echo $nodeList->item($i)->value . "<br/>\n";
	}
	if (preg_match('/nation/',$nodeList->item($i)->value))
	{
    echo $nodeList->item($i)->value . "<br/>\n";
	} */
}

echo "<pre>" ;print_r($data_array);exit;
//exit;
}
?>
<html>
  <head>
    <meta charset="utf-8">
    <title>Information scraping</title>
    <link rel="stylesheet" href="css/style.css">
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
  </head>
  <body>
    <form action="#" method="post" name="sign up for beta form">
      <div class="header">
         <p>api</p>
      </div>
      <div class="description">
        <p>Test Preoject</p>
      </div>
      <div class="input">
        <input type="text" class="button" id="email" name="url" placeholder="Url Enter">
        <input type="submit" name="submit" class="button" id="submit" value="Scraping">
      </div>
    </form>
  </body>
</html>
<style>

body {
  background: #fff;
  font-family: 'Lato', sans-serif;
  color: #FDFCFB;
  text-align: center;
}


form {
  width: 450px;
  margin: 17% auto;
}


.header {
  font-size: 35px;
  text-transform: uppercase;
  letter-spacing: 5px;
}


.description {
  font-size: 14px;
  letter-spacing: 1px;
  line-height: 1.3em;
  margin: -2px 0 45px;
}


.input {
  display: flex;
  align-items: center;
}


.button {
  height: 44px;
  border: none;
}

  
#email {
  width: 75%;
  background: #FDFCFB;
  font-family: inherit;
  color: #737373;
  letter-spacing: 1px;
  text-indent: 5%;
  border-radius: 5px 0 0 5px;
}


#submit {
  width: 25%;
  height: 46px;
  background: #E86C8D;
  font-family: inherit;
  font-weight: bold;
  color: inherit;
  letter-spacing: 1px;
  border-radius: 0 5px 5px 0;
  cursor: pointer;
  transition: background .3s ease-in-out;
}
  

#submit:hover {
  background: #d45d7d;
}
  

input:focus {
  outline: none;
  outline: 2px solid #E86C8D;
  box-shadow: 0 0 2px #E86C8D;
}
</style>
