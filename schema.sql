CREATE TABLE IF NOT EXISTS users
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    email      VARCHAR(255) NOT NULL,
    password   VARCHAR(255) NOT NULL,
    username   VARCHAR(255) NOT NULL,

    created_at DATETIME     NOT NULL DEFAULT NOW(),
    updated_at DATETIME     NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS forms
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    user_id    INT          NOT NULL,

    FOREIGN KEY (user_id) REFERENCES users (id),

    created_at DATETIME     NOT NULL DEFAULT NOW(),
    updated_at DATETIME     NOT NULL DEFAULT NOW()
);

CREATE TABLE multiple_choice_questions
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    form_id    INT      NOT NULL,
    position   INT      NOT NULL,

    FOREIGN KEY (form_id) REFERENCES forms (id),

    created_at DATETIME NOT NULL DEFAULT NOW(),
    updated_at DATETIME NOT NULL DEFAULT NOW()
);

CREATE TABLE multiple_choice_choices
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT      NOT NULL,
    description TEXT     NOT NULL,
    position    INT      NOT NULL,

    FOREIGN KEY (question_id) REFERENCES multiple_choice_questions (id),

    created_at  DATETIME NOT NULL DEFAULT NOW(),
    updated_at  DATETIME NOT NULL DEFAULT NOW()
);

INSERT INTO forms (name, user_id)
VALUES ('test_form', 1);