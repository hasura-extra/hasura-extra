CREATE DATABASE metadata;
\c metadata;

CREATE TABLE public.account
(
    "id"      serial NOT NULL,
    "name" varchar (64) NOT NULL,
    "email" varchar (512) NOT NULL UNIQUE,
    "country_code" varchar(2),
    PRIMARY KEY ("id")
);

INSERT INTO public.account ("name", "email", "country_code") VALUES
 ('Test 1', 'email1@example.org', 'VN'),
 ('Test 2', 'email2@example.org', 'IN'),
 ('Test 3', 'email3@example.org', 'US'),
 ('Test 4', 'email4@example.org', 'FR'),
 ('Test 5', 'email5@example.org', 'GB');

