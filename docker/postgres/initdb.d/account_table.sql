CREATE TABLE public.account
(
    "id"      serial NOT NULL,
    "name" varchar (64) NOT NULL,
    "email" varchar (512) NOT NULL UNIQUE,
    PRIMARY KEY ("id")
);

INSERT INTO public.account ("name", "email") VALUES
('Test 1', 'email1@example.org'),
('Test 2', 'email2@example.org'),
('Test 3', 'email3@example.org'),
('Test 4', 'email4@example.org'),
('Test 5', 'email5@example.org');