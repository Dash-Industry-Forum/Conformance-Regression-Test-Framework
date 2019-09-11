<?php
// Using JavaScript, write the string argument to the text area element
// $choice = 1 for Write From Scratch
// $choice = 2 for Append
// $choice = 3 for Delete Last Character
function writeToTextArea($text, $choice){
    echo "<script>
    var textArea = document.getElementById('vectors');
    </script>";     

    if ($choice == 1){
        echo "<script>
        textArea.value = $text;
        </script>";
    }
    else if ($choice == 2){
        echo "<script>
        textArea.value += $text;
        </script>";
    }
    else if($choice == 3){
        echo "<script>
        textArea.value = textArea.value.slice(0, textArea.value.length - 1);
        </script>";
    }
    else{
        echo "<script>
        textArea.value = 'Invalid choice selected for writing to text area.
                            Please choose from among 1, 2, or 3.';
        </script>";
    }
}
?>