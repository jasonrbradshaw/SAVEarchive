// https://mkyong.com/jquery/how-to-add-remove-textbox-dynamically-with-jquery/

    // Add or Remove Courses
    var counter = 1;

    $("#addField").click(function () {

    if(counter>10){
            alert("Only 10 entries allowed");
            return false;
    }

    var newCourse = $(document.createElement('div'))
         .attr("id", 'textBoxDiv' + counter);

    newCourse.after().html('<input type="text" name="addField' + counter +
          '" id="addField' + counter + '" value="" required="required" placeholder="Field data goes here"><br>');

    newCourse.appendTo("#addFieldTextBox");


    counter++;
     });

     $("#removeField").click(function () {
    if(counter==1){
          alert("No more Fields to remove");
          return false;
       }

    counter--;

        $("#textBoxDiv" + counter).remove();

     });

  });