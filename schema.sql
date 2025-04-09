CREATE TABLE IF NOT EXISTS users
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS forms
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    questions INT NOT NULL DEFAULT 0
);

CREATE TABLE questions
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    form_id INT NOT NULL REFERENCES forms (id) ON DELETE CASCADE ,
    question TEXT NOT NULL,
    position INT NOT NULL,
    type VARCHAR(25) NOT NULL,
    required BOOLEAN NOT NULL NOT NULL DEFAULT FALSE
);

CREATE TABLE text_questions
(
    id INT PRIMARY KEY REFERENCES questions (id) ON DELETE CASCADE,
    long_answer BOOLEAN DEFAULT FALSE
);

CREATE TABLE multiple_choice_questions
(
    id INT PRIMARY KEY REFERENCES questions(id) ON DELETE CASCADE,
    multiple BOOLEAN NOT NULL DEFAULT FALSE,
    choices INT NOT NULL DEFAULT 2,
    max_choices INT
);

CREATE TABLE multiple_choice_choices
(
    id INT AUTO_INCREMENT PRIMARY KEY ,
    question_id INT NOT NULL REFERENCES questions(id) ON DELETE CASCADE ,
    description TEXT NOT NULL,
    position    INT NOT NULL
);