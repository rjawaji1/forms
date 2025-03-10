const formDesigner = document.getElementById("form-designer")

let counter = 0;

function addChoice() {
    // Generate a random id for radio button group
    const id = crypto.randomUUID();
    counter++;

    const choice = document.createElement("div");
    choice.innerHTML = `
        <h2>Question ${counter}</h2>
        <div class="builder">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${id}" id="${id}">
                <input class="form-control" type="text" placeholder="Choice 1">
                <button class="btn btn-danger" onclick="removeOption(this)">Remove</button>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${id}" id="${id}">
                <input class="form-control" type="text" placeholder="Choice 2">
                <button class="btn btn-danger" onclick="removeOption(this)">Remove</button>
            </div>
        </div>
        <div>
            <button class="btn btn-success" onclick="addOption(this, '${id}')">Add Option</button>
            <button class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>
        </div>
    `;

    formDesigner.appendChild(choice);
}

/**
 * 
 * @param {HTMLButtonElement} element 
 * @param {string} id 
 */
function addOption(element, id) {
    const option = document.createElement("div");
    option.className = "form-check";
    option.innerHTML = `
        <input class="form-check-input" type="radio" name="${id}" id="${id}">
        <input class="form-control" type="text" placeholder="Choice">
        <button class="btn btn-danger" onclick="removeOption(this)">Remove</button>
    `;
    element.previousElementSibling.appendChild(option);
}

/**
 * 
 * @param {HTMLButtonElement} element 
 */
function removeOption(element) {
    element.parentElement.remove();
}

function test() {
    XMLHttpRequest();
}