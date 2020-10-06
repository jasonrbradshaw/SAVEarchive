// https://mkyong.com/jquery/how-to-add-remove-textbox-dynamically-with-jquery/
// Add and Remove Schools
$(document).ready(function(){

    var counter = 2;

    $("#addButton").click(function () {

    if(counter>10){
            alert("Only 10 entries allowed");
            return false;
    }

    var newTextBoxDiv = $(document.createElement('div'))
         .attr("id", 'TextBoxDiv' + counter);

    newTextBoxDiv.after().html('<input type="text" name="school_num' + counter +
          '" id="school' + counter + '" value="" required="required" placeholder="School Name">');

    newTextBoxDiv.appendTo("#TextBoxesGroup1");


    counter++;
     });

     $("#removeButton").click(function () {
    if(counter==1){
          alert("No more School's to remove");
          return false;
       }

    counter--;

        $("#TextBoxDiv" + counter).remove();

     });
/*
     $("#getButtonValue").click(function () {

    var msg = '';
    for(i=1; i<counter; i++){
      msg += "\n Textbox #" + i + " : " + $('#textbox' + i).val();
    }
          alert(msg);
     });
     */


// Add or Remove Siblings
    var counters = 1;

    $("#SibaddButton").click(function () {

    if(counters>10){
            alert("Only 10 entries allowed");
            return false;
    }

    var newSibling = $(document.createElement('div'))
         .attr("id", 'SibTextBoxDiv' + counters);

    newSibling.after().html('<label>Name </label><input type="text" name="siblingName' + counters +
          '" id="siblingName' + counters + '" value="" required="required" placeholder="Sibling #'+ counters +' "><br><label>Age </label><input type="text" name="siblingAge' + counters +
          '" id="siblingAge' + counters + '" value="" required="required" placeholder="Sibling #'+ counters +' "><br><label>School </label><input type="text" name="siblingSchool' + counters +
          '" id="siblingSchool' + counters + '" value="" required="required" placeholder="Sibling #'+ counters +' "><br>');

    newSibling.appendTo("#TextBoxesGroup2");


    counters++;
     });

     $("#SibremoveButton").click(function () {
    if(counters==1){
          alert("No more Sibling's to remove");
          return false;
       }

    counters--;

        $("#SibTextBoxDiv" + counters).remove();

     });


// Add or Remove Guardians
    var counterg = 1;

    $("#GaraddButton").click(function () {

    if(counterg>5){
            alert("Only 5 entries allowed");
            return false;
    }

    var newGuardian = $(document.createElement('div'))
         .attr("id", 'GarTextBoxDiv' + counterg);

    newGuardian.after().html('<label>Relation to candidate </label><input type="text" name="garRelation' + counterg +
          '" id="garRelation' + counterg + '" value="" required="required" ><br><label>Surname </label><input type="text" name="garSurname' + counterg +
          '" id="garSurname' + counterg + '" value="" required="required" ><br><label>Given Name(s) </label><input type="text" name="garName' + counterg +
          '" id="garName' + counterg + '" value="" required="required" ><br><label>Address </label><input type="text" name="garAddress' + counterg +
          '" id="garAddress' + counterg + '" value="" required="required" ><br><label>City </label><input type="text" name="garCity' + counterg +
          '" id="garCity' + counterg + '" value="" required="required" ><br><label>Postal Code </label><input type="text" name="garPostal' + counterg +
          '" id="garPostal' + counterg + '" value="" required="required" ><br><label>Home Telephone </label><input type="text" name="garHomePH' + counterg +
          '" id="garHomePH' + counterg + '" value="" ><br><label>Cell Phone </label><input type="text" name="garCellPH' + counterg +
          '" id="garCellPH' + counterg + '" value="" ><br><label>Work Telephone </label><input type="text" name="garWorkPH' + counterg +
          '" id="garWorkPH' + counterg + '" value="" ><br><label>Occupation/Title </label><input type="text" name="garOcc' + counterg +
          '" id="garOcc' + counterg + '" value="" required="required" ><br><label>Employer </label><input type="text" name="garEmploy' + counterg +
          '" id="garEmploy' + counterg + '" value="" required="required" ><br>');

    newGuardian.appendTo("#TextBoxesGroup3");


    counterg++;
     });

     $("#GarremoveButton").click(function () {
    if(counterg==1){
          alert("No more Guardian's to remove");
          return false;
       }

    counterg--;

        $("#GarTextBoxDiv" + counterg).remove();

     });

    // Add or Remove Courses
    var counterc = 1;

    $("#CoraddButton").click(function () {

    if(counterc>10){
            alert("Only 10 entries allowed");
            return false;
    }

    var newCourse = $(document.createElement('div'))
         .attr("id", 'CorTextBoxDiv' + counterc);

    newCourse.after().html('<input type="text" name="course_num' + counterc +
          '" id="courseNum' + counterc + '" value="" required="required" placeholder="Course Name and Code"><br>');

    newCourse.appendTo("#TextBoxesGroup4");


    counterc++;
     });

     $("#CorremoveButton").click(function () {
    if(counterc==1){
          alert("No more Course's to remove");
          return false;
       }

    counterc--;

        $("#CorTextBoxDiv" + counterc).remove();

     });

  });