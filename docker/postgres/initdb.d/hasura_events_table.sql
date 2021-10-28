CREATE TABLE public.hasura_events
(
    "id"      serial NOT NULL,
    "payload" jsonb  NOT NULL,
    PRIMARY KEY ("id")
);