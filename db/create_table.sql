CREATE TABLE clients (
       id serial PRIMARY KEY,
       name text NOT NULL,
       email text NOT NULL UNIQUE,
       cpf text NOT NULL UNIQUE,
       phone text
);
