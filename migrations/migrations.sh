#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
create table if not exists topic
(
    id serial not null
        constraint topic_pk
            primary key,
    name varchar(500) not null
);

alter table topic owner to parser;

create unique index if not exists topic_name_uindex
    on topic (name);

create table if not exists post
(
    id serial not null
        constraint post_pk
            primary key,
    topic_id integer not null
        constraint post_topic_id_fk
            references topic,
    author varchar(60) not null,
    date timestamp not null,
    text text
);

alter table post owner to parser;


CREATE PROCEDURE insert_post(varchar(500), varchar(100), timestamp, text)
    LANGUAGE plpgsql
AS \$\$
DECLARE
    _topic_id int;
BEGIN
    WITH ins AS (
        INSERT INTO topic (name) VALUES(\$1)
            ON CONFLICT (name) DO NOTHING
            RETURNING id
    )
    SELECT id FROM ins
    UNION ALL
    SELECT id FROM topic WHERE name=\$1
    LIMIT  1
    INTO _topic_id;
    INSERT INTO post(topic_id, author, date, text) VALUES (_topic_id, \$2, \$3, \$4);
END;
\$\$;
EOSQL

