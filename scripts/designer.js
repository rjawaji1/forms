$(document).ready(function(){
  
  // Form Controls Selectors
  $("[id^=create_]").click(function(){
    let buttonID = $(this).attr("id");
    let questionType = buttonID.replace("create_", "");
    let searchParams = new URLSearchParams(window.location.search);
    let formID = parseInt(searchParams.get("id"));

    if (!formID) return;


    $.ajax({
      type: "post",
      url: "api/form/question/create.php",
      data: {form_id: formID, type: questionType},
      success: (result) => $("#form-designer").append(result),
    });  
  });


  // Multiple Choice Selectors
  $(".question-controls button[data-action='add-option']").click(function(){
    let searchParams = new URLSearchParams(window.location.search);
    let formID = parseInt(searchParams.get("id"));

    let question = $(this).closest("data-question-id");
    let questionID = question.dataset["data-question-id"];
    console.log(questionID);
    
    $.ajax({
      type: "post",
      url: "api/form/question/options/multiple_choice/add",
      data: {form_id: formID, question_id: questionID},
      success: (result) => $(this).closest(".question-choices").append(result),
    })
  })
});
