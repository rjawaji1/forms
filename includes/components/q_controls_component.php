<div class="question-controls">
    <?php switch($question_type): case "multiple_choice" :?>
        <button class="btn btn-primary" data-action="mcq_add_option">Add Option</button>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="<?=$question_id?>-multiple">
            <label class="form-check-label" for="<?=$question_id?>-multiple">Multiple Answers</label>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="<?=$question_id?>-required">
            <label class="form-check-label" for="<?=$question_id?>-required">Required</label>
        </div>
    <?php break; case "text" :?>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="<?=$question_id?>-long">
            <label class="form-check-label" for="<?=$question_id?>-long">Long Answer</label>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="<?=$question_id?>-required">
            <label class="form-check-label" for="<?=$question_id?>-required">Required</label>
        </div>
    <?php endswitch; ?>
</div>