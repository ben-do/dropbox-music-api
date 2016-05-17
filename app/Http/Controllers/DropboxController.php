<?php

namespace App\Http\Controllers;

use Request;
// use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class DropboxController extends Controller
{
	
	public function noUrl() 
	{
		return response()->json([
			'error' => [
				'message' => "No URL provided!"
			]
		], 404);
	}

	public function drop2json($url)
	{
		
		// var_dump($url);

		// for test
		if($url == 'test') {
			$url = "https://www.dropbox.com/sh/rjs3exx7ngorozx/AADbdQ18gqC6KKNEzrw_csMwa?dl=0";
		} else {
			// $url = urldecode($url);
			$url = str_replace("-slash-", "/", $url);
		}
		
		// var_dump($url);
		// test

		// examine if the link come from dropbox
		if (strpos($url, "https://www.dropbox.com/sh/") !== false) {

		    $data = [];
			$ch_detail = curl_init();
			curl_setopt($ch_detail, CURLOPT_URL, $url);
			curl_setopt($ch_detail, CURLOPT_RETURNTRANSFER, true); 
			$detailResult = curl_exec($ch_detail);

			$detailDom = new \DOMDocument();
			@$detailDom->loadHTML($detailResult);
			// var_dump($detailDom);
			$detailxpath = new \DOMXpath($detailDom);

			// seems dynamic generate, cannot get 
			// //*[@id="browse-location"]/text()
			$folderName = $detailxpath->query("//*[@id='browse-location']");
			// var_dump($folderName->nodeValue);



			// //*[@id="list-view-container"]/ol/li
			$contentQuery = $detailxpath->query("//*[@id='list-view-container']/ol/li/div[1]/div/a");

			$counter = 1;
			foreach ($contentQuery as $link) {
				$arr['title'] = $link->nodeValue;
				$fileType = substr(strrchr($arr['title'],'.'), 1);
				// echo $fileType;
				// if ($fileType != "mp3" || $fileType != "wmv" || $fileType != "ogg") {
				// 	continue;
				// }
				
				if(empty($fileType)){
					continue;
				}

				$arr['id'] = $counter++;
				// $arr['title'] = $link->nodeValue;
				$arr['url'] = explode("?", $link->getAttribute("href"))[0] . "?dl=1";

				array_push($data, $arr);
			}


			curl_close($ch_detail);

			return response()->json($data, 200);
		} else {
			return response()->json([
				'error' => [
					'message' => "Not a valid dropbox share folder link"
				]
			], 404);
		}

	}

}
