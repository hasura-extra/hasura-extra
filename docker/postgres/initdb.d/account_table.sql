CREATE TABLE public.account
(
    "id"      serial NOT NULL,
    "name" varchar (64) NOT NULL,
    "email" varchar (512) NOT NULL UNIQUE,
    PRIMARY KEY ("id")
);

INSERT INTO public.account ("name", "email") VALUES
('Test 1', 'email1@example'),
('Test 2', 'email2@example'),
('Test 3', 'email3@example'),
('Test 4', 'email4@example'),
('Test 5', 'email5@example');