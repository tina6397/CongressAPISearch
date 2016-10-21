
<h1 style="text-align: center;">Congress Information Search</h1>


    

<form name="form" id="form1" onsubmit="return validateForm(event, this)" method="POST"  style="width: 400px;margin: 0 auto; ">
    
<fieldset><table width="300">
	<p><tr>
		<td><label>Congress Database</label></td><td>
        <select name="CongressDatabase" id="selection" onclick="return alert();">
         <option value="0" disabled selected>Select Your Option</option>
          <option value="1"  onclick="return alert();" <?php if(isset($_POST['reset'])) { echo ""; }
              elseif(isset($_POST['CongressDatabase'])&&($_POST['CongressDatabase'])=='1') { echo 'selected'; }?>>Legislators</option>
          <option value="2" <?php if(isset($_POST['reset'])) { echo ""; }
              elseif(isset($_POST['CongressDatabase'])&&($_POST['CongressDatabase'])=='2') { echo 'selected'; }?>>Committees</option>
          <option value="3" <?php if(isset($_POST['reset'])) { echo ""; }
              elseif(isset($_POST['CongressDatabase'])&&($_POST['CongressDatabase'])=='3') { echo 'selected'; }?>>Bills</option>
          <option value="4" <?php if(isset($_POST['reset'])) { echo ""; }
              elseif(isset($_POST['CongressDatabase'])&&($_POST['CongressDatabase'])=='4') { echo 'selected'; }?>>Amendments</option>
        </select>
	</p>
    </td>
</tr>
    
    <p><tr>
       <td><label>Chamber</label></td>
       <td><input id="camber" type="radio" name="camber" value="senate" checked> Senate
       <input type="radio" name="camber" value="house" <?php 
              if(isset($_POST['reset'])) { echo ""; }
              elseif(isset($_POST['camber'])&&($_POST['camber'])=='house') { echo 'checked'; }?>> House<br></td>
    </p></tr>
    <tr><td><p>
    
		<label id="keyword"><?php if(isset($_POST['reset'])) { echo "Keyword*"; }
              elseif(isset($_POST['CongressDatabase'])&&($_POST['CongressDatabase'])=='1') { echo 'State/Representative*'; }
                elseif(isset($_POST['CongressDatabase'])&&($_POST['CongressDatabase'])=='2') { echo 'Committee ID*'; }elseif(isset($_POST['CongressDatabase'])&&($_POST['CongressDatabase'])=='3') { echo 'Bill ID*'; }elseif(isset($_POST['CongressDatabase'])&&($_POST['CongressDatabase'])=='4') { echo 'Amendment ID*'; }else{
                    echo "Keyword*";
                }
        
        
            ?></label></td>
        <td>
		<input id="keywordInput" type="text" name="keyword" value="<?php if(isset($_POST['reset'])) { echo ""; }elseif(isset($_POST['keyword'])){
                echo htmlentities ($_POST['keyword']);
    
}?>"></td></tr>
        
    </p>
    <tr><td></td><td>
	   <input type="submit" name="submit" value="Search">
       <input type="submit" name="reset" id="reset" value="Clear"><br></td></tr>

    </table>
   <center><p><a href="http://sunlightfoundation.com/" target="_blank">Powered by Sunlight Foundation</a></p></center>
</fieldset> 

</form>



<?php
//include_once("inc.php");
//API Key: 71a6ae9481474d4285f6eaaa6286711e

if(isset($_POST["submit"]) ): 
?>
<pre>

<?php //print_r($_POST); 

$text ="";

if($_POST["CongressDatabase"] == 1){
    
    //match state
    $state = ucfirst(trim($_POST["keyword"])) ;
    $states = array(
        'Alabama'=>'AL',
        'Alaska'=>'AK',
        'Arizona'=>'AZ',
        'Arkansas'=>'AR',
        'California'=>'CA',
        'Colorado'=>'CO',
        'Connecticut'=>'CT',
        'Delaware'=>'DE',
        'Florida'=>'FL',
        'Georgia'=>'GA',
        'Hawaii'=>'HI',
        'Idaho'=>'ID',
        'Illinois'=>'IL',
        'Indiana'=>'IN',
        'Iowa'=>'IA',
        'Kansas'=>'KS',
        'Kentucky'=>'KY',
        'Louisiana'=>'LA',
        'Maine'=>'ME',
        'Maryland'=>'MD',
        'Massachusetts'=>'MA',
        'Michigan'=>'MI',
        'Minnesota'=>'MN',
        'Mississippi'=>'MS',
        'Missouri'=>'MO',
        'Montana'=>'MT',
        'Nebraska'=>'NE',
        'Nevada'=>'NV',
        'New Hampshire'=>'NH',
        'New Jersey'=>'NJ',
        'New Mexico'=>'NM',
        'New York'=>'NY',
        'North Carolina'=>'NC',
        'North Dakota'=>'ND',
        'Ohio'=>'OH',
        'Oklahoma'=>'OK',
        'Oregon'=>'OR',
        'Pennsylvania'=>'PA',
        'Rhode Island'=>'RI',
        'South Carolina'=>'SC',
        'South Dakota'=>'SD',
        'Tennessee'=>'TN',
        'Texas'=>'TX',
        'Utah'=>'UT',
        'Vermont'=>'VT',
        'Virginia'=>'VA',
        'Washington'=>'WA',
        'West Virginia'=>'WV',
        'Wisconsin'=>'WI',
        'Wyoming'=>'WY'
        );

    $json="";
    if(array_key_exists($state,$states)){
        //convert state name into two word in matching array
        $state = $states[$state];
        //get json
        $json = file_get_contents('http://congress.api.sunlightfoundation.com/legislators?chamber='.$_POST["camber"].'&state='.trim($state).'&apikey=71a6ae9481474d4285f6eaaa6286711e');
    }elseif(preg_match('/\s/',$state)){
        
        $name = explode(" ", $state);
        $json = file_get_contents('http://congress.api.sunlightfoundation.com/legislators?chamber='.$_POST["camber"].'&first_name='.$name[0].'&last_name='.$name[1].'&apikey=71a6ae9481474d4285f6eaaa6286711e');
        
    }else{
        //input considered to be part of name
        $json = file_get_contents('http://congress.api.sunlightfoundation.com/legislators?chamber='.$_POST["camber"].'&query='.trim($state).'&apikey=71a6ae9481474d4285f6eaaa6286711e');
        
    }
    
    
    $obj = json_decode($json, true);
    //$doc = $this->domDocument ;
    //echo $json ;
    
    //
    if($obj['count']==0){
        echo "<center>The API returned zero results for the request.</center>";
    }else{
        $obj2 = $obj['results'];

        
        ob_start();
        
        
        echo "<center><div id='table'><table style='border: 1px' width='1000' height='100' border='1'><tr><th>Name</th><th>State</th><th>Chamber</th><th>View Details</th></tr>";


        foreach($obj2 as $i){
            echo "<tr>";
                echo "<td>".$i['first_name']." ".$i['last_name']."</td>";
                echo "<td><center>".$i['state_name']."</center></td>";
                echo "<td><center>".$i['chamber']."</center></td>";
            //echo "<td>"."<img src='"."https://theunitedstates.io/images/congress/225x275/".$i['bioguide_id'].".jpg"."'>"."</td>";
            $bioguide_id = $i['bioguide_id'];
            $title = $i['title'];
            $first_name = $i['first_name'];
            $last_name= $i['last_name'];
            $term_end= $i['term_end'];
            $website= $i['website'];
            $office= $i['office'];
            if(isset($i['facebook_id'])){
                $facebook_id= $i['facebook_id'];
            }else{
                $facebook_id=null;
            }
            
            
            if(isset($i['twitter_id'])){
                $twitter_id= $i['twitter_id'];
            }else{
                $twitter_id=null;
            }
            
            
                
            echo "<td>"."<center><a href='javascript:myFunction(\"$bioguide_id\",\"$title\",\"$first_name\",\"$last_name\",\"$term_end\",\"$website\",\"$office\",\"$facebook_id\",\"$twitter_id\")'>View Details</a></center>"."</td>";
            //echo "<td>"."<a href=''>View Details</a>"."</td>";

                //$text .= $i['bioguide_id']."<br>";
            echo "</tr>";
    }
    
    echo "</table></div></center>";

    }
    
    
    //echo $text;
        //ob_end_clean();

    //document.getElementById('output').innerHTML = $text;;

}elseif($_POST["CongressDatabase"] == 2){
    $up = strtoupper(trim($_POST["keyword"]));
    $json = file_get_contents('http://congress.api.sunlightfoundation.com/committees?committee_id='.$up.'&chamber='.$_POST["camber"].'&apikey=71a6ae9481474d4285f6eaaa6286711e');
    
    
    $obj = json_decode($json, true);
    //$doc = $this->domDocument ;
    //echo $json ;
    
    //
    if($obj['count']==0){
        echo "<center>The API returned zero results for the request.</center>";
    }else{
        
        $obj2 = $obj['results'];

        
        ob_start();
        
        
        echo "<center><div id='table'><table style='border: 1px' width='1000' height='100' border='1'><tr><th>Committee ID</th><th>Committee Name</th><th>Chamber</th></tr>";


        foreach($obj2 as $i){
            echo "<tr>";
                echo "<td>".$i['committee_id']."</td>";
                echo "<td>".$i['name']."</td>";
                echo "<td>".$i['chamber']."</td>";
            echo "</tr>";
    }
    
    echo "</table></div></center>";

    }
    
    
    
}elseif($_POST["CongressDatabase"] == 3){
        $lower = strtolower(trim($_POST["keyword"]));

      $json = file_get_contents('http://congress.api.sunlightfoundation.com/bills?bill_id='.$lower.'&chamber='.$_POST["camber"].'&apikey=71a6ae9481474d4285f6eaaa6286711e');
    
    $obj = json_decode($json, true);
    //$doc = $this->domDocument ;
    //echo $json ;
    
    //
    if($obj['count']==0){
        echo "<center>The API returned zero results for the request.</center>";
    }else{
        $obj2 = $obj['results'];

        
        ob_start();
        
        
        echo "<center><div id='table'><table style='border: 1px' width='1000' height='100' border='1'><tr><th>Bill ID</th><th>Short Title</th><th>Chamber</th><th>Details</th></tr>";


        foreach($obj2 as $i){
            echo "<tr>";
                echo "<td>".$i['bill_id']."</td>";
                echo "<td>".$i['short_title']."</td>";
                echo "<td>".$i['chamber']."</td>";
            
            $bill_id = $i['bill_id'];
            $short_title = $i['short_title'];
            $sponser = $i['sponsor']['title']." ".$i['sponsor']['first_name']." ".$i['sponsor']['last_name'];
            $introduced_on= $i['introduced_on'];
            $last_action= $i['last_version']['version_name'].", ".$i['last_action_at'];
            $bill_url= $i['last_version']['urls']['pdf'];

                
            echo "<td>"."<a href='javascript:myFunction2(\"$bill_id\",\"$short_title\",\"$sponser\",\"$introduced_on\",\"$last_action\",\"$bill_url\")'>View Details</a>"."</td>";
            //echo "<td>"."<a href=''>View Details</a>"."</td>";

                //$text .= $i['bioguide_id']."<br>";
            echo "</tr>";
    }
    
    echo "</table></div></center>";

    }
    
}elseif($_POST["CongressDatabase"] == 4){
    
     $low = strtolower(trim($_POST["keyword"]));
    $json = file_get_contents('http://congress.api.sunlightfoundation.com/amendments?amendment_id='.$low.'&chamber='.$_POST["camber"].'&apikey=71a6ae9481474d4285f6eaaa6286711e');
    

    
    $obj = json_decode($json, true);
    //$doc = $this->domDocument ;
    //echo $json ;
    
    //
    if($obj['count']==0){
        echo "<center>The API returned zero results for the request.</center>";
    }else{
        
        $obj2 = $obj['results'];

        
        ob_start();
        
        
        echo "<center><div id='table'><table style='border: 1px' width='1000' height='100' border='1'><tr><th>Amendment ID</th><th>Amendment Type</th><th>Chamber</th><th>Introduce on</th></tr>";


        foreach($obj2 as $i){
            echo "<tr>";
                echo "<td>".$i['amendment_id']."</td>";
                echo "<td>".$i['amendment_type']."</td>";
                echo "<td>".$i['chamber']."</td>";
                echo "<td>".$i['introduced_on']."</td>";
            echo "</tr>";
    }
    
    echo "</table></div></center>";

    }
    
}



?>
</pre>
<?php endif; 
if(isset($_POST["reset"])){
    $_POST["keyword"] = "TEST";
    //print_r($_POST);  
    
    
    
}




?>
<head>  
    
    <title>Forecast</title>
    <script type="text/javascript">
        
        function resetForm(){
            document.getElementById('keywordInput').value = "HELLO";
            
        }
        
        
       function myFunction(bioguide_id,title,first_name,last_name,term_end,website,office,facebook_id,twitter_id){
       //myFunction(bioguide_id,title){
           //var i = <?php echo 5; ?>;
            // document.getElementById('output').innerHTML = i;
             //document.getElementById('table').innerHTML = bioguide_id,title,first_name,last_name,term_end,website,office,facebook_id,twitter_id;
           
           text = "<center><img src='https://theunitedstates.io/images/congress/225x275/"+bioguide_id +".jpg'><br>";
            //text="bioguide_id" ;
           text += "<table style='border: 0px' width='400' height='100' border='0'><tr><td>Full Name</td><td>"+title+" "+first_name+" "+last_name+"</td></tr>";
           
           text += "<tr><td>Term Ends on</td><td>"+term_end+"</td></tr>";
           text += "<tr><td>Website</td><td><a href=\""+website+"\" target=\"_blank\">"+website+"</a></td></tr>";
           text += "<tr><td>Office</td><td>"+office+"</td></tr>";
           text += "<tr><td>Facebook</td><td><a href=\"https://www.facebook.com/"+facebook_id+"\" target=\"_blank\">"+first_name+" "+last_name+"</a></td></tr>";
           text += "<tr><td>Twitter</td><td><a href=\"https://www.twitter.com/"+twitter_id+"\" target=\"_blank\">"+first_name+" "+last_name+"</a></td></tr>";

           text+="</table></center>";
               
               //+title+first_name+last_name+term_end+website+office+facebook_id+twitter_id;
           document.getElementById('table').innerHTML = text;

           
           
           
       }
        
        //function for view details "BILL ID"
        //\"$bill_id\",\"$short_title\",\"$sponser\",\"$introduced_on\",\"$last_action\",\"$bill_url\")'>View Details</a>"."</td>";
        function myFunction2(bill_id,short_title,sponser,introduced_on,last_action,bill_url){
       //myFunction(bioguide_id,title){
          // var i = <?php echo 5; ?>;
            // document.getElementById('output').innerHTML = i;
             //document.getElementById('table').innerHTML = bioguide_id,title,first_name,last_name,term_end,website,office,facebook_id,twitter_id;
           
           text = "<center>";
            //text="bioguide_id" ;
           text += "<table style='border: 0px' width='600' height='100' border='0'><tr><td>Bill ID</td><td>"+bill_id+"</td></tr>";
           
           text += "<tr><td>Bill Title</td><td>"+short_title+"</td></tr>";
             text += "<tr><td>Sponsor</td><td>"+sponser+"</td></tr>";
             text += "<tr><td>Introduced On</td><td>"+introduced_on+"</td></tr>";
             text += "<tr><td>Last action with date</td><td>"+last_action+"</td></tr>";
            text += "<tr><td>Bill URL</td><td><a href=\""+bill_url+"\" target=\"_blank\">"+short_title+"</a></td></tr>";
             
      

           text+="</table></center>";
               
               //+title+first_name+last_name+term_end+website+office+facebook_id+twitter_id;
           document.getElementById('table').innerHTML = text;

           
           
           
       }
        
        function validateForm(e,f){
            
            submit = e.explicitOriginalTarget.name;
                
            if(submit=="reset"){
                
            }else{
                          x = document.getElementById('selection').value;
                    key = document.getElementById('keywordInput').value;
                    valid=true;
                    alert1 ="Please enter the following missing information:  ";
                    //check if Congress Database is empty
                        if (x=="0") {
                            alert1 += "  *Congress Database*  ";
                            valid = false;
                        }

                    //check if keyword is empty            
                        if (key==""){

                            alert1 +=  "  *keyword*  ";
                            valid = false;

                        }else{
        //                    if(key=="Washington"){
        //                        key.value = "WA";
        //                    }
                        }

                    if(valid){


                        return true;
                    }else{
                        confirm(alert1);
                        
                        return false;
                    }

                           
            }
            

            
           

        }
        
        var keyword="*Keyword*";

        //alert user if form is not valid
        function alert(){
                if(document.getElementById('selection').value !="0"){
                     v = document.getElementById('selection').value;
                    
                    switch(v){

                        case "1":
                            keyword = "State/Representative*";
                            break;
                            
                        case "2":
                            keyword = "Committee ID*";
                            break;
                        case "3":
                            keyword = "Bill ID*";
                            break;
                        case "4":
                            keyword = "Amendment ID*";
                            break;
                            
                    }
                    
                      document.getElementById('keyword').innerHTML = keyword;
                    document.getElementById('keywordInput').value = "";

                    }

        }
        
       

    </script>
    
</head>






