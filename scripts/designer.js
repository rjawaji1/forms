$(function() {

  // Form Controls Selectors
  $("[id^=create_]").on("click", function() {
    let buttonID = $(this).attr("id");
    let questionType = buttonID.replace("create_", "");
    let searchParams = new URLSearchParams(window.location.search);
    let formID = parseInt(searchParams.get("id"));

    if (!formID) return;


    $.ajax({
      method: "POST",
      url: "api/form/question/create.php",
      data: { form_id: formID, type: questionType },
      success: (result) => $("#form-designer").append(result),
    });
  });

  // Handle Multiple Choice Actions
  $("button[data-action^='mco_']").on("click", function() {
    let action = $(this)
      .attr("data-action")
      .replace("mco_", "");

    let questionElement = $(this).closest("div[data-question-id]")
    let questionID = parseInt($(questionElement).attr("data-question-id"));

    let currentChoiceElement = $(this).closest("div[data-choice-id]");
    let currentChoiceID = parseInt(currentChoiceElement.attr("data-choice-id"));
    let currentChoicePos = parseInt(currentChoiceElement.attr("data-choice-position"));

    if (action == "delete") {
      $.ajax({
        method: "POST",
        url: "api/form/question/options/multiple_choice/delete.php",
        data: { question_id: questionID, choice_id: currentChoiceID },
        success: () => currentChoiceElement.remove(),
      });

      return;
    }

    if (action != "move_up" && action != "move_down") return;

    $.ajax({
      method: "POST",
      url: "api/form/question/options/multiple_choice/edit.php",
      data: { question_id: questionID, choice_id: currentChoiceID, action: action },
      success: function() {

        if (action == "move_up") {
          let nextChoiceElement = $(currentChoiceElement)
            .siblings(`div[data-choice-position=${currentChoicePos - 1}]`);

          if (nextChoiceElement.length != 1) return;

          $(currentChoiceElement).after(nextChoiceElement);

          currentChoiceElement.attr("data-choice-position", currentChoicePos - 1);
          nextChoiceElement.attr("data-choice-position", currentChoicePos);
          return;
        }

        if (action == "move_down") {
          let nextChoiceElement = $(currentChoiceElement)
            .siblings(`div[data-choice-position=${currentChoicePos + 1}]`);

          if (nextChoiceElement.length != 1) return;

          $(currentChoiceElement).before(nextChoiceElement);

          currentChoiceElement.attr("data-choice-position", currentChoicePos + 1);
          nextChoiceElement.attr("data-choice-position", currentChoicePos);
          return;
        }
      },
      error: handleError
    });
  });

  $("button[data-action^='q_']").on("click", function() {
    let searchParams = new URLSearchParams(window.location.search);
    let formID = parseInt(searchParams.get("id"));

    let currentQuestionElement = $(this).closest("div[data-question-id]")
    let currentQuestionID = parseInt($(currentQuestionElement).attr("data-question-id"));
    let currentQuestionPos = parseInt($(currentQuestionElement).attr("data-question-position"));

    let action = $(this)
      .attr("data-action")
      .replace("q_", "");

    console.log(action);

    if (action == "delete") {
      $.ajax({
        method: "POST",
        url: "api/form/question/delete.php",
        data: { form_id: formID, question_id: currentQuestionID },
        success: () => {
          currentQuestionElement.remove()
        },
        error: handleError
      });
      return;
    }

    if (action != "move_up" && action != "move_down") return;

    $.ajax({
      method: "POST",
      url: "api/form/question/edit.php",
      data: { form_id: formID, question_id: currentQuestionID, action: action },
      success: () => {
        if (action == "move_up") {
          let nextQuestionElement = $(currentQuestionElement)
            .siblings(`div[data-question-position=${currentQuestionPos - 1}]`);

          if (nextQuestionElement.length != 1) return;

          $(currentQuestionElement).after(nextQuestionElement);

          currentQuestionElement.attr("data-question-position", currentQuestionPos - 1);
          nextQuestionElement.attr("data-question-position", currentQuestionPos);
          return;
        }

        if (action == "move_down") {
          let nextQuestionElement = $(currentQuestionElement)
            .siblings(`div[data-question-position=${currentQuestionPos + 1}]`);

          if (nextQuestionElement.length != 1) return;

          $(currentQuestionElement).before(nextQuestionElement);

          currentQuestionElement.attr("data-question-position", currentQuestionPos + 1);
          nextQuestionElement.attr("data-question-position", currentQuestionPos);
          return;
        }
      },
      error: handleError
    })

  });

  $("button[data-action^='mcq_']").on("click", function() {
    console.log("clicked");
    let searchParams = new URLSearchParams(window.location.search);
    let formID = parseInt(searchParams.get("id"));

    let questionElement = $(this).closest("div[data-question-id]")
    let questionID = parseInt($(questionElement).attr("data-question-id"));

    // let action = $(this)
    //   .attr("data-action")
    //   .replace("mco_", "");

    let question_body = $(this)
      .closest(".question-controls")
      .siblings(".question-body");

    $.ajax({
      method: "POST",
      url: "api/form/question/options/multiple_choice/add.php",
      data: { form_id: formID, question_id: questionID },
      success: (result) => question_body.append(result),
      error: handleError
    });
  });

  $(".question-controls input[type='checkbox']").on("change", function() {
    console.log("clicked");
    let searchParams = new URLSearchParams(window.location.search);
    let formID = parseInt(searchParams.get("id"));

    let checkboxID = $(this).attr("id").split('-');
    let questionID = checkboxID[0];
    let checkboxRole = checkboxID[1];

    let checkBox = $(this);

    $.ajax({
      method: "POST",
      url: "api/form/question/options/multiple_choice/edit.php",
      data: { form_id: formID, question_id: questionID, action: "edit_options", role: checkboxRole },
      success: function() {
        switch (checkboxRole) {
          case "long": {
            let body = checkBox
              .closest(".question-controls")
              .siblings(".question-body");

            body.empty();

            if (checkBox.prop("checked")) {
              let textArea = document.createElement("textarea");
              textArea.disabled = true;
              textArea.className = "form-control"

              body.append(textArea);
            } else {
              let input = document.createElement("input");
              input.disabled = true;
              input.className = "form-control";

              body.append(input);
            }
            break;
          }
          case "multiple": {
            checkBox
              .closest(".question-controls")
              .siblings(".question-body")
              .children()
              .children("input[type]")
              .prop("type", checkBox.prop("checked") ? "checkbox" : "radio");
          }
        }
      },
      error: handleError,
    });
  });

  $(".question-header input").on("change", function() {
    let questionID = parseInt($(this)
      .closest("div[data-question-id]")
      .attr("data-question-id"));

    let newValue = $(this).prop("value");

    $.ajax({
      method: "POST",
      url: "api/form/question/edit.php",
      data: {question_id: questionID, action: "update_header", value: newValue},
      error: handleError
    })
  })

  $("div[data-choice-id] input[type='text']").on("change", function(){
    let choiceID = parseInt($(this)
      .closest("div[data-choice-id]")
      .attr("data-choice-id"));  

    let newValue = $(this).prop("value");

    $.ajax({
      method: "POST",
      url: "api/form/question/options/multiple_choice/edit.php",
      data: {choice_id: choiceID, action: "update_choice_text", value: newValue},
      error: handleError
    });
  });
});


function handleError(jqXHR, textStatus, errorThrown) {
  console.log(jqXHR);
  console.log(textStatus);
  console.log(errorThrown);
}
