const formDesigner = document.getElementById("form-designer")

function addChoice() {
    // Generate a random id for radio button group
    const id = crypto.randomUUID();

    // Create the builder
    let choiceBuilder = document.createElement("div");
    choiceBuilder.className = "builder";
    choiceBuilder.id = id;

    let question = document.createElement("input");
    question.type = "text";

    // Append the choices to the builder
    let choiceList = document.createElement("div");
    choiceList.appendChild(question);
    choiceList.appendChild(generateChoiceInput(id))
    choiceList.appendChild(generateChoiceInput(id))
    choiceBuilder.appendChild(choiceList);

    // Add More Options
    let btnAddOption = document.createElement("button");
    btnAddOption.textContent = "Add Option";
    btnAddOption.className = "btn btn-small";
    btnAddOption.onclick = () => {
        choiceList.appendChild(generateChoiceInput(id));
    }
    choiceBuilder.appendChild(btnAddOption);

    // Add the builder to the form designer
    formDesigner.appendChild(choiceBuilder);
}

function generateChoiceInput(name) {
    let input = document.createElement("div");
    input.style.display = "flex";

    let choice = document.createElement("input");
    choice.type = "radio";
    choice.name = name;
    input.appendChild(choice);

    let text = document.createElement("input");
    text.type = "text";
    text.placeholder = `Option`;
    input.appendChild(text);

    let btnRemove = document.createElement("button");
    btnRemove.textContent = "Remove";
    btnRemove.className = "btn btn-small";
    btnRemove.onclick = () => {
        input.remove();
    }
    input.appendChild(btnRemove);

    return input;
}