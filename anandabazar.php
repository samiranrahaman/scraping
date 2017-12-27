<?php

$url="http://www.anandabazar.com/";
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
   //echo $nodeList->item($i)->value . "<br/>\n";
	  if (preg_match('/national/',$nodeList->item($i)->value) || preg_match('/entertainment/',$nodeList->item($i)->value)|| preg_match('/editorial/',$nodeList->item($i)->value)|| preg_match('/sport/',$nodeList->item($i)->value))
	  {
		  if(!in_array($nodeList->item($i)->value,$unic_array)):
		   $unic_array[]=$nodeList->item($i)->value;
		   $url2 = "http://www.anandabazar.com".$nodeList->item($i)->value;
			$ch2 = curl_init($url2);
			curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
			$curl_scraped_page_inner = curl_exec($ch2);
			curl_close($ch2);
			$htmlAsString_inner=$curl_scraped_page_inner;

			$doc2 = new DOMDocument();
			$doc2->loadHTML($htmlAsString_inner);
			$xpath2 = new DOMXPath($doc2);
			
			/*  preg_match("'<h1>(.*?)</h1>'si", $htmlAsString_inner, $match1);
			 if($match1) print_r($match1); //$data_array[$i]['title']=$match1[0]; */
              foreach ($xpath2->query('//h1') as $div)
		 {
			$data_array[$i]['title']=$div->nodeValue ;
			//echo $div->nodeValue;
		 }			 
			 
			 foreach ($xpath2->query('//div[contains(@id, "textbody")]') as $div)
			 {
				$data_array[$i]['content']=$div->nodeValue ;
				//echo $div->nodeValue;
			 } 
			 
			 foreach ($xpath2->query('//div[contains(@class, "textwrap_left")]//*[@class="img-responsive"]') as $div)
			 {
				$data_array[$i]['img']=$div->getAttribute('src') ;
				//echo $div->nodeValue;
			 } 
			 
			 
			 
		  endif;
		  
	  }
	
	 
}

//echo "<pre>" ;print_r($data_array);
echo "<pre>" ;print_r($data_array);
exit;


?>
