<?php
//youtube data scraping//
/*
 $url="https://www.youtube.com/watch?v=ncOVM5nSqRM";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_scraped_page = curl_exec($ch);
curl_close($ch);
$htmlAsString=$curl_scraped_page;
$doc = new DOMDocument();
libxml_use_internal_errors(true);
//var_dump(libxml_use_internal_errors(true));//enable error handle
$doc->loadHTML($htmlAsString);
$xpath = new DOMXPath($doc);*/

/* $contents = $xpath->query('/html/head/meta[@name="description"]/@content');

// If nothing matches the query
if ($contents->length == 0) {
    echo "No description meta tag :(";
// Found one or more descriptions, loop over them
} else {
    foreach ($contents as $content) {
        echo $content->value . PHP_EOL;
    }
} */

 
/* $tags = get_meta_tags('https://www.youtube.com/watch?v=E58ZVwOhj6c&t=3s');

// Notice how the keys are all lowercase now, and
// how . was replaced by _ in the key.
//echo $tags['author'];       // name
//echo $tags['keywords'];     // php documentation
echo $tags['description'];  // a php manual
echo $tags['duration']; // 49.33;-86.59 */


/*$nodes = $xpath->query('//*[@itemprop]');


$new_data = [];
foreach ($nodes as $node) {
    foreach ($node->attributes as $attr) {
        $new_data[$attr->nodeName] []= $attr->nodeValue;
    }
}
var_dump($new_data);*/








if(isset($_POST['submit']))
{
//	echo $_POST['url'];exit;
//$url = "https://play.google.com/store/search?q=usa&c=apps&hl=en";
$url=$_POST['url'];
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_scraped_page = curl_exec($ch);
curl_close($ch);


/* $htmlAsString='<html><body>
<div  class="cluster-heading" style="width: 1020px;" >A</div>
<div>B</div><div>
C</div>
<div class="ssss sss" style="color:red">D</div>
<a href="dddd">ssssssss</a>
</body></html>'; */

$htmlAsString=$curl_scraped_page;



$doc = new DOMDocument();
libxml_use_internal_errors(true);
//var_dump(libxml_use_internal_errors(true));//enable error handle
$doc->loadHTML($htmlAsString);
$xpath = new DOMXPath($doc);
$nodeList = $xpath->query('//a/@href');
$data_array='';
for ($i = 0; $i < $nodeList->length; $i++) 
	//for ($i = 0; $i <= 1; $i++) 
{
    # Xpath query for attributes gives a NodeList containing DOMAttr objects.
    # http://php.net/manual/en/class.domattr.php
   // echo $nodeList->item($i)->value . "<br/>\n";
	if (preg_match('/details/',$nodeList->item($i)->value))
	{
		//echo $nodeList->item($i)->value . "<br/>\n";
		$url2 = "https://play.google.com".$nodeList->item($i)->value;
		//$url2 = "https://play.google.com/store/apps/details?id=in.krsolutions.usanews";
		$ch2 = curl_init($url2);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
		$curl_scraped_page_inner = curl_exec($ch2);
		curl_close($ch2);
		$htmlAsString_inner=$curl_scraped_page_inner;

		$doc2 = new DOMDocument();
		$doc2->loadHTML($htmlAsString_inner);
		$xpath2 = new DOMXPath($doc2);
		
		foreach ($xpath2->query('//div[contains(@class, "id-app-title")]') as $div)
		 {
			$data_array[$i]['title']=$div->nodeValue ;
			//echo $div->nodeValue;
		 }
		 
		/* foreach ($xpath2->query('//div[contains(@class, "score")]') as $div)
		 { 
			$data_array[$i]['score']=$div->nodeValue ;
			//echo $div->nodeValue ;
		 } */
		foreach ($xpath2->query('//div[contains(@class, "score")]') as $div)
			 { 
			 if( is_numeric($div->nodeValue))
			 { 
		           $data_array[$i]['score']=$div->nodeValue ;
			 }
			 
			 
			 
			 
			 } 
		/*  preg_match("'<div class=\"score\" aria-label=\" Rated 4.1 stars out of five stars \">(.*?)</div>'si", $htmlAsString, $match);
               if($match) echo "result=".$match[1]; */
			   
		 preg_match("'<div class=\"content\" itemprop=\"datePublished\">(.*?)</div>'si", $htmlAsString_inner, $match1);
		 // if($match) echo "result=".$match[1];
		 // echo "</br>";
		 if($match1) $data_array[$i]['datePublished']=$match1[1];
		 
		 
		 preg_match("'<div class=\"content\" itemprop=\"numDownloads\">(.*?)</div>'si", $htmlAsString_inner, $match2);
		//if($match) echo "result=".$match[1];
		   if($match2) $data_array[$i]['numDownloads']=$match2[1];
		   
		   
		 preg_match("'<div class=\"content\" itemprop=\"softwareVersion\">(.*?)</div>'si", $htmlAsString_inner, $match3);
		//if($match) echo "result=".$match[1];
		 if($match3) $data_array[$i]['softwareVersion']=$match3[1];
		 
		 
		 preg_match("'<div class=\"content\" itemprop=\"operatingSystems\">(.*?)</div>'si", $htmlAsString_inner, $match4);
		//if($match) echo "result=".$match[1];
		 if($match4) $data_array[$i]['operatingSystems']=$match4[1];
		 
		preg_match("'<div class=\"content physical-address\">(.*?)</div>'si", $htmlAsString_inner, $match5);
		//if($match) echo "result=".$match[1];
		 if($match5) $data_array[$i]['address']=$match5[1];
		
		
		   $pattern = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
            preg_match_all($pattern, $htmlAsString_inner, $matches);
			//echo $matches[0][1];
            $data_array[$i]['email']=$matches[0][1];


	//exit;	
	 // break;
		
	}
	
}

//print_r($data_array);exit;

 header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w'); 

// output the column headings
fputcsv($output, array('Title', 'Score', 'DatePublished','NumDownloads','SoftwareVersion','OperatingSystems','Address','Developer Eamil'));

// fetch the data

foreach($data_array as $data)
{
	//echo $data['title'];
 $list = array ($data['title'], $data['score'], $data['datePublished'], $data['numDownloads'], $data['softwareVersion'], $data['operatingSystems'], $data['address'],$data['email']);
 fputcsv($output, $list); 
}

exit;
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
         <p>Information scraping</p>
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
  background: #F8A434;
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
