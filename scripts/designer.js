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
        success: () => choiceElement.remove(),
      });

      return;
    }

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
      }
    });
  });

  $("button[data-action^='mcq_']").on("click", function() {
    let searchParams = new URLSearchParams(window.location.search);
    let formID = parseInt(searchParams.get("id"));

    let questionID = $(this)
      .closest("[data-question-id]")
      .attr("data-question-id");

    // let action = $(this)
    //   .attr("data-action")
    //   .replace("mco_", "");

    let question_body = $(this)
      .parent()
      .siblings(".question-body");

    $.ajax({
      method: "POST",
      url: "api/form/question/options/multiple_choice/add.php",
      data: { form_id: formID, question_id: parseInt(questionID) },
      success: (result) => question_body.append(result),
    });

  });

});
