<?php

$url="http://bengali.news18.com/";
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
    // echo $nodeList->item($i)->value . "<br/>\n";
	 if (preg_match('/news/',$nodeList->item($i)->value))
	{
		
		if(!in_array($nodeList->item($i)->value,$unic_array)):
		$unic_array[]=$nodeList->item($i)->value;
		//$data_array[$i]['url']=$nodeList->item($i)->value;
		$url2 = $nodeList->item($i)->value;
		$ch2 = curl_init($url2);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
		$curl_scraped_page_inner = curl_exec($ch2);
		curl_close($ch2);
		$htmlAsString_inner=$curl_scraped_page_inner;

		$doc2 = new DOMDocument();
		$doc2->loadHTML($htmlAsString_inner);
		$xpath2 = new DOMXPath($doc2);
		
		/* foreach ($xpath2->query('//h1[contains(@class, "arH")]') as $div)
		 {
			$data_array[$i]['title']=$div->nodeValue ;
			//echo $div->nodeValue;
		 } */
		
		/* foreach ($xpath2->query('//div[contains(@class, "_picCon")]') as $div)
		 {
			$data_array[$i]['content']=$div->nodeValue ;
			//echo $div->nodeValue;
		 }  */
		
		
		//$img = $xpath2->query('//*[@class="img-responsive"]');
		//$data_array[$i]['img']=$img->item(0)->getAttribute('src') .'<br />' ;
		 
		 
		  preg_match("'<h1 itemprop=\"headline\">(.*?)</h1>'si", $htmlAsString_inner, $match1);
		 if($match1) $data_array[$i]['title']=$match1[1];
		 
		 foreach ($xpath2->query('//div[contains(@id, "article_body")]') as $div)
		 {
			$data_array[$i]['content']=$div->nodeValue ;
			//echo $div->nodeValue;
		 } 
		 
		 endif;
	}
	 
}

echo "<pre>" ;print_r($data_array);exit;

?>
