CREATE DATABASE ChatJaiPT;
use ChatJaiPT;

CREATE TABLE [user] (
    id INT IDENTITY NOT NULL,
    email NVARCHAR(180) NOT NULL,
    roles VARCHAR(MAX) NOT NULL,
    password NVARCHAR(255) NOT NULL,
    name NVARCHAR(255) NOT NULL,
    token NVARCHAR(255),
    PRIMARY KEY (id)
);

CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON [user] (email) WHERE email IS NOT NULL;

CREATE TABLE message (
    id INT IDENTITY NOT NULL,
    content NVARCHAR(255) NOT NULL,
    date DATETIME2(6) NOT NULL,
    author_id INT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT FK_B6BD307FF675F31B FOREIGN KEY (author_id) REFERENCES [user] (id)
);

CREATE TABLE messenger_messages (
    id BIGINT IDENTITY NOT NULL,
    body VARCHAR(MAX) NOT NULL,
    headers VARCHAR(MAX) NOT NULL,
    queue_name NVARCHAR(190) NOT NULL,
    created_at DATETIME2(6) NOT NULL,
    available_at DATETIME2(6) NOT NULL,
    delivered_at DATETIME2(6),
    PRIMARY KEY (id)
);

CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name);
CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at);
CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at);
