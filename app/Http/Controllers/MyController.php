<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;

class MyController extends Controller
{
    //


    public function mydata(){

        // From URL to get webpage contents. 
        $url = "https://api.learnwithyoutube.org/api/v1/get_all_categories"; 
  
          // Initialize a CURL session. 
        $ch = curl_init();  
  
         // Return Page contents. 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  
         //grab URL and pass it to the variable. 
        curl_setopt($ch, CURLOPT_URL, $url); 
  
        $result = curl_exec($ch); 
   
        $obj = json_decode($result);
        $datas=$obj->data;

        
        $collection = new Collection();
        foreach($datas as $data){
            $collection->push([ "id"=> $data->id,
            "title"=> $data->title,
            "level"=> $data->level,
            "parent_id"=> $data->parent_id,
           

                ]);
        }

        $tree = function ($elements, $parentId = 0) use (&$tree) {
            $branch = array();
            foreach ($elements as $element) {
    
                if ($element['parent_id'] == $parentId) {
    
                    $children = $tree($elements, $element['id']);
                    if ($children) {
                        $element['children'] = $children;
                    }  else {
                        //$element['children'] = [];
                    }
                    $branch[] = $element;
                }
    
            }
    
            return $branch;
        };
    
        $tree = $tree($collection);
    
        return (json_encode($tree)); // ouputs tree
    
    
    }
}
