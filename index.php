<?php

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');

   if($_GET['streetaddress']!="" && $_GET['city']!="" && $_GET['state']!="")
            {
               $streetaddress= $_GET["streetaddress"];
               $city= $_GET["city"];
                $state=$_GET["state"];
                
                $url= "http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=X1-ZWz1b2pypik3kb_3bebs" ;
                $url .= "&address=" . urlencode($streetaddress);
                $url .= "&citystatezip=" . urlencode($city);
                $url .= "+" . urlencode($state);
                $url .= "&rentzestimate=true";
     
                $xml= simplexml_load_file($url);
                
                 if ($xml->message->code =='0')
                {
                    ( ($propertytype =  $xml->response->results->result->useCode) !="" )? :$propertytype="N/A" ;
                    
                    ""!=($xml->response->results->result->lastSoldPrice)? $lastsoldprice= "$" . number_format(abs((float)$xml->response->results->result->lastSoldPrice),2) :$lastsoldprice="N/A" ;
                     if($lastsoldprice == "$0.00"){
                         $lastsoldprice="N/A";
                     }
                    
                    ( ($lastsolddate = $xml->response->results->result->lastSoldDate) !="")? $lastsolddate = date("d-M-Y", strtotime($xml->response->results->result->lastSoldDate)) :$lastsolddate="N/A";  
                     if($lastsolddate == "01-Jan-1970" || $lastsolddate == "31-Dec-1969"){
                         $lastsolddate="N/A";
                     }
                    
                    ( ($yearbuilt = $xml->response->results->result->yearBuilt) !="" ) ? : $yearbuilt="N/A";
                     
                    ""!=($xml->response->results->result->lotSizeSqFt)? $lotsize = number_format((float)$xml->response->results->result->lotSizeSqFt). " sq. ft.": $lotsize="N/A";
                                                                                                 
                    ( ($propertyestimate =$xml->response->results->result->zestimate->{'last-updated'}) !="" ) ? $propertyestimate= date("d-M-Y",strtotime($xml->response->results->result->zestimate->{'last-updated'})):$propertyestimate="N/A";
                                                                                                 
                    "" !=($xml->response->results->result->zestimate->amount)? $propertyestvalue = "$" . number_format((float)$xml->response->results->result->zestimate->amount, 2):$propertyestvalue="N/A";
                                                                                                 
                    ""!=($xml->response->results->result->finishedSqFt)? $finishedarea = number_format((float)$xml->response->results->result->finishedSqFt) . " sq. ft.":$finishedarea="N/A";
                                                                                                 
                    ""!=($valuechange = $xml->response->results->result->zestimate->valueChange)? : $valuechange="N/A";
                                                                                                 
                    ""!=($xml->response->results->result->zestimate->valueChange)? $valuechangeval = "$" .number_format(abs((float)$xml->response->results->result->zestimate->valueChange),2):$valuechangeval="N/A";
                                                                                                 
                    ( ($bathrooms =$xml->response->results->result->bathrooms) !="" )? :$bathrooms="N/A";
                                                                                                 
                    ""!=($xml->response->results->result->zestimate->valuationRange->low)? $rangelow1 = "$" . number_format((float)$xml->response->results->result->zestimate->valuationRange->low,2):$rangelow1="N/A" ;
                    
                    ""!=($xml->response->results->result->zestimate->valuationRange->high)?  $rangehigh1 = "$" .number_format((float)$xml->response->results->result->zestimate->valuationRange->high,2):$rangehigh1="N/A";
                    
                    ( ($bedrooms = $xml->response->results->result->bedrooms) !="" )? :$bedrooms="N/A";
                    
                    ( ($taxassessmentyear = $xml->response->results->result->taxAssessmentYear) !="" )?  :$taxassessmentyear= "N/A";
                    
                    ""!= ($xml->response->results->result->taxAssessment)?  $taxassessment = "$" .number_format((float)$xml->response->results->result->taxAssessment,2):$taxassessment="N/A";
                    
                    if(isset($xml->response->results->result->rentzestimate))
                    {
                    ""!=($xml->response->results->result->rentzestimate->{'last-updated'})? $rentdate = date("d-M-Y",strtotime($xml->response->results->result->rentzestimate->{'last-updated'})) :$rentdate="N/A";
                    
                    ""!=($xml->response->results->result->rentzestimate->amount)? $rentestval ="$" . number_format((float)$xml->response->results->result->rentzestimate->amount,2):$rentestval="N/A";
                    
                    ( ($rentvalchange = $xml->response->results->result->rentzestimate->valueChange) !="" )? :$rentvalchange="N/A";
                    
                    ""!=($xml->response->results->result->rentzestimate->valueChange)? $rentvaluechangeval= "$" .number_format(abs((float)$xml->response->results->result->rentzestimate->valueChange),2):$rentvaluechangeval="N/A" ;
                    
                    
                    ""!=($rangelow2 =$xml->response->results->result->rentzestimate->valuationRange->low)? $rangelow2 = "$" . number_format((float)$xml->response->results->result->rentzestimate->valuationRange->low,2):$rangelow2="N/A";     
                    
                    ""!=($rangehigh2 =$xml->response->results->result->rentzestimate->valuationRange->high)? $rangehigh2 = "$" .number_format((float)$xml->response->results->result->rentzestimate->valuationRange->high,2) :$rangehigh2="N/A"; 
                    }
                     else{
                    $rentdate=$rentestval=$rentvalchange=$rentvaluechangeval=$rangelow2=$rangehigh2="N/A";
                    }
                     

                     
                     $imgd = "http://www-scf.usc.edu/~csci571/2014Spring/hw6/down_r.gif";
                     $imgu = "http://www-scf.usc.edu/~csci571/2014Spring/hw6/up_g.gif";
                     
                    $estimatevaluechangesign= (float)$valuechange <0 ? "-" : "+";
                    $restimatevaluechangesign= (float)$rentvalchange <0 ? "-":"+";
                 
                     

                $homedetails = $xml->response->results->result->links->homedetails;
                $street = $xml->response->results->result->address->street; 
                $city = $xml->response->results->result->address->city;
                $state = $xml->response->results->result->address->state; 
                $zipcode = $xml->response->results->result->address->zipcode;
        
                $zpid = $xml->response->results->result->zpid;
       
                $year1 ="http://www.zillow.com/app?chartDuration=1year&chartType=partner&height=300&page=webservice%2FGetChart&service=chart&showPercent=true&width=600&zpid=20593083" ;
                
        $year5 ="http://www.zillow.com/app?chartDuration=5years&chartType=partner&height=300&page=webservice%2FGetChart&service=chart&showPercent=true&width=600&zpid=" . $zpid ;
        $year10 = "http://www.zillow.com/app?chartDuration=10years&chartType=partner&height=300&page=webservice%2FGetChart&service=chart&showPercent=true&width=600&zpid=" . $zpid;
                
                
               $mainarr = array( 'result'=>array('homedetails'=>"$homedetails", 'street'=>"$street", 'city'=>"$city", 'state'=>"$state", 'zipcode'=>"$zipcode", 'usecode' =>"$propertytype", 'lastsoldprice' =>"$lastsoldprice", 'lastsolddate' =>"$lastsolddate", 'yearbuilt' =>"$yearbuilt", 'lotsize' => "$lotsize", 'propertyestimate' => "$propertyestimate", 'propertyestvalue' =>"$propertyestvalue", 'finishedarea' =>"$finishedarea", 'estimatevaluechangesign'=>"$estimatevaluechangesign", 'imgd'=>"$imgd", 'imgu' =>"$imgu", 'valuechangeval' => "$valuechangeval", 'bathrooms'=>"$bathrooms", 'rangelow1' =>"$rangelow1", 'rangehigh1' =>"$rangehigh1", 'bedrooms' => "$bedrooms", 'taxassessmentyear' => "$taxassessmentyear", 'taxassessment' =>"$taxassessment", 'rentdate'=>"$rentdate", 'rentestval'=>"$rentestval", 'restimatevaluechangesign'=> "$restimatevaluechangesign", 'rentvalchange'=>"$rentvalchange", 'rentvaluechangeval'=>"$rentvaluechangeval", 'rangelow2'=>"$rangelow2", 'rangehigh2'=>"$rangehigh2" ),'chart' => array('year1'=>array('url'=>"$year1"), 'year5' =>array('url'=>"$year5"), 'year10' =>array('url'=>"$year10")));
        
                    
                     
                    echo json_encode($mainarr);
                 }
        else{
            
            $error="false";
            echo json_encode($error);
        }
    
   }
    

?>