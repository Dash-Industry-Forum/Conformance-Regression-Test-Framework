<?php

session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION["loggedIn"]) || $_SESSION['loggedIn'] == false){
    header('Location:login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
<!-- <meta http-equiv="refresh" content=2> -->
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>    
<script src="http://code.jquery.com/jquery-latest.js"></script> -->
<!-- 
 -->
<script src="jquery/jquery.min.js"></script>


<link rel="stylesheet" type="text/css" href="TestFramework.css">
<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body > 
<!-- 
<ul>
  <li><a class="active" href="#home">Home</a></li>
  <li><a href="#news">News</a></li>
  <li><a href="#contact">Contact</a></li>
  <li><a href="#about">About</a></li>
</ul>    -->


<h2> Test Automation </h2>
    <?php if (file_exists("TestReport.xlsx")) {
        unlink("TestReport.xlsx");
    } else {
        // File not found.
    }?>

<script>
    
    
    var page;
    var resultDivNum = 0;
   /* window.onload = function()
    {	
        document.getElementById('vectors').value = <?php $file = file_get_contents( 'DefaultVectorList.txt' ); echo json_encode( $file ); ?>;
    }*/
    
    $(document).on('input', 'textarea', function () {
        $(this).outerHeight(38).outerHeight(this.scrollHeight); // 38 or '1em' -min-height
    });
    function assignurl(mpdfile,page)
        {
        var inturl = '../DASH-IF-Conformance/Conformance-Frontend/';
        var targeturl = inturl+"Conformancetest.php?mpdurl="+mpdfile;
        page.location.href = targeturl;
        }
     function opennewpage(){
      page = window.open("");        
     }

    
    function starttesting()
    {  
        opennewpage();        
        var currentWin;
        var ClearRef;
        //Test if the temp folder is empty, if not, then clean it.
        $.post(
               "checkempty.php",
               {path:'../DASH-IF-Conformance/Conformance-Frontend/temp'}
               ).done(function(response){
                console.log(response);
                if(response == "temp folder not empty")
                {  //Clean temp folder
                 $.post( 
                    "cleanup.php",
                    {path:'../DASH-IF-Conformance/Conformance-Frontend/temp/'},
                    function(response)
                    {
                        console.log(response);  
                    }
                 );
                }
            
            if( document.getElementById('Checkbox').checked)
               ClearRef=1;
            else 
               ClearRef=0;
              //Clean TestResults folder and References depending on the condition.
              $.post(
                     "cleanResults.php",
                     {flag: ClearRef}
                    ).done(function(response){
                        console.log(response);
                        console.log("TestResults cleaned");
                    if(response !== "References present")
                    {
                        document.getElementById('RefMsg').innerHTML=response;
                        document.getElementById('Checkbox').checked=true;
                        testcontroller();   
                    }
                    else
                    {
                        document.getElementById('RefMsg').innerHTML=response;
                        testcontroller();
                    }
                 });
                 
        });
        //Remove old Results division from the webpage
        for (var z=1;z<=resultDivNum;z++){
            if(!(document.getElementById('resultDiv'+z)== null))
                document.getElementById('resultDiv'+z).remove();
        }
        
        var  i=1, j=1;
        var vectorstr = document.getElementById('vectors').value;
        if (vectorstr!='')
        {
            var vectors = vectorstr.split("\n");
            console.log(vectors);
        }
         

        
        function testcontroller()
        {
            if(i<=vectors.length)
            {

                if(!(document.getElementById('Checkbox').checked))
                {  
                   $.post(
                           "CountRef.php"
                         ).done(function(response){
                       var countRef= response;
                       console.log("Folders in References="+countRef);
                                      
                        if(i<= (countRef)){
                            currentWin = assignurl(vectors[i-1],page);  //process the current mpd file
                            document.getElementById('statusContent').innerHTML= "Running vector "+i;
                        }
                       else
                           alert("Testing stopped as there are no more References to compare"); 
                       });
                   
                }
                else{
                    currentWin = assignurl(vectors[i-1],page);  //process the current mpd file
                    document.getElementById('statusContent').innerHTML= "Running vector "+i;
                }
                
                // decide if we should rename and process the next test vector immediately
                if(document.getElementById('Pause').checked === false)
                {
                    //To check progress of Conformance Test and paste results into TestResults folder and References folder accordingly. 
                   
                    $.post(
                        "ProcessResults.php",
                        {length:vectors.length, path:'../DASH-IF-Conformance/Conformance-Frontend/temp', mpdURL:vectors[i-1]}
                    ).done(function(response){
                        var folder=response;
                        console.log(folder);
                        console.log("Successfully tested vector "+i);
                        $.post(
                             "CheckDiff.php",
                              {folder: folder}
                        ).done(function(response){
                            console.log(response);
                            // Success or failure is shown with 'right' or 'wrong' icons with links to errors.  
                            var id='resultDiv'+i; console.log(id);
                            var topn=232+18*i;
                            var top=topn + 'px';
                            if(response== "wrong"){
                                var div = '<div id= '+ id +' style="position: absolute;left:20px; top:'+top+';"></div>';
                                document.body.insertAdjacentHTML('beforeend', div);
                                var y = document.getElementById(id); 
                                y.innerHTML ='<a href="TestResults/'+folder+'_diff.txt" target="_blank"> Check diff</a>';
                                $('#'+id).append('<img id="theImg" src="button_cancel.png" />');
                                document.getElementById('statusContent').innerHTML= "Completed vector "+j;
                            }
                            else{
                                var div = '<div id= '+ id +' style="position: absolute;left:100px; top:'+top+';"></div>';
                                document.body.insertAdjacentHTML('beforeend', div);
                                var y = document.getElementById(id); 
                                $('#'+id).prepend('<img id="theImg" src="right.jpg" />');
                                document.getElementById('statusContent').innerHTML= "Completed vector "+j;
                            }
                            
                            if(response == "right"){
                                response = "passed";
                            }
                            else if(response == "wrong"){
                                response = "failed";
                            }
                            else{
                                response == null;
                            }
                          
                            //Send the response to server
                            $.post('StoreResult.php', {result:response, url:vectors[i-1], function(response){
                                console.log(response);
                            }});
                 
                            if(vectors.length>j)
                                document.getElementById('statusContent').innerHTML= "Running vector "+(j+1);
                            j++;
                            resultDivNum=j;
                            i++;
                            testcontroller();
                        });
                    });
                }
                else  // wait until user close the new tab
                {
                    var _flagCheck = setInterval(function() {
                        if (page.closed) 
                        {
                            clearInterval(_flagCheck);
                            //To check progress of Conformance Test and paste results into TestResults folder and References folder accordingly.             
                            $.post(
                                "ProcessResults.php",
                                {length:vectors.length, path:'../DASH-IF-Conformance/Conformance-Frontend/temp', mpdURL:vectors[i-1]}
                            ).done(function(response){
                                var folder=response;
                                console.log(folder);
                                console.log("Successfully tested vector "+i); 
                                $.post(
                                     "CheckDiff.php",
                                      {folder: folder}
                                ).done(function(response){
                                    console.log(response);
                                    // Success or failure is shown with 'right' or 'wrong' icons with links to errors.  
                                    var id='resultDiv'+i; console.log(id);
                                    var topn = 232 + 18*i;
                                    var top=topn + 'px';
                                    var div = '<div id= '+ id +' style="position: absolute;left:100px; top:'+top+';"></div>';
                                    document.body.insertAdjacentHTML('beforeend', div);
                                    var y = document.getElementById(id); 
                                    if(response== "wrong"){
                                        y.innerHTML ='<a href="TestResults/'+folder+'_diff.txt" target="_blank"> Check differences</a>';
                                        $('#'+id).prepend('<img id="theImg" src="button_cancel.png" />');
                                        document.getElementById('statusContent').innerHTML= "Completed vector "+j;
                                    }
                                    else{
                                        $('#'+id).prepend('<img id="theImg" src="right.jpg" />');
                                        document.getElementById('statusContent').innerHTML= "Completed vector "+j;
                                    }
                        
                            if(response == "right"){
                                response = "passed";
                            }
                            else if(response == "wrong"){
                                response = "failed";
                            }
                            else{
                                response == null;
                            }

                            //Send the response to server
                            $.post('StoreResult.php', {result:response, url:vectors[i-1], function(response){
                                console.log(response);
                            }});



                                    if(vectors.length>j)
                                        document.getElementById('statusContent').innerHTML= "Running vector "+(j+1);
                                    j++;
                                    resultDivNum=j;
                                    i++;
                                    testcontroller();
                                });
                            });
                            if(i<vectors.length){
                            opennewpage(); 
                            }
                        }
                    }, 10); // interval set at 10 milliseconds
                           
                  }
            }
            else  // Creating Reference results.
            {
                if (document.getElementById('Checkbox').checked)
                {
                    $.post(
                     "CreateRef.php"
                    ).done(function(response){
                        console.log("Referenced");
                  });

                }
            }
        }
    }
</script>



<br>
<p id="Testvectors">Test vectors :</p><br>
<textarea name="Text1" cols="110" rows="30" style="overflow:hidden" id='vectors' ></textarea>
<br><input type=button id="Start" value="Start Testing" onclick="starttesting()">  
<div id="tick" style="position: absolute; left: 900px"></div>
<p id="status">Status :</p>
<p id="statusContent"></p>
<p id="results">Results :</p>
<input type="checkbox" id="Checkbox">
<p id="ChecboxTitle">Create Reference</p>
<input type="checkbox" id="Pause">
<p id="PauseTitle">Continue only when the current test is closed by user</p>
<p id="RefMsg"></p>

<a href="logout.php"><button id="button_logout">Logout</button></a>





<?php
// Start of PHP script.
require_once('ConnectToDb.php');
require_once('logException.php');
require_once('writeToTextArea.php');

// Clear the <textarea> element.
// \'\' is empty string('') with escape sequences
writeToTextArea('\'\'',1);

try{
    $test_vectors =$db->test_testVectors->find();
}
catch(MongoDB\Driver\Exception\Exception $catchedException){
    
    logException(get_class($catchedException)." : ".$catchedException->getMessage());
}

// Check if find() has returned any document from the collection.
$isDeadFlag = $test_vectors->isDead();

// If $isDeadFlag is true, then show error to user.
if($isDeadFlag) {
    writeToTextArea('Error: The selected collection is empty.',1);
} // Else, populate the <textarea> element one by one with the URLs of test vectors.
else {
    foreach($test_vectors as $vector){
        // Variable 'textArea' is defined in the Javascript code in the 'echo' statement above.
        // += is the JavaScript concatenation operator.
        // \$vector['url'] chooses the 'url' field from each test vector.
        // '+ \\n' adds a newline character at the end of each url.
        writeToTextArea("'{$vector['url']}' + '\\n'",2);
    }
    // Remove the last character which is a newline character.
    // So that the DASH validator does not interpret the final empty line in the text area as a vector.
    // The value of the string provided as argument does not affect the intended functionality for 'Option 3'.
    writeToTextArea("",3);
}
// End of PHP script
?>



</body>

</html>
