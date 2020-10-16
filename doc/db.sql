create database mini_twitter;
use mini_twitter;

create table user(
    id_user int not null auto_increment,
    user_name varchar(35) not null unique,
    email varchar(256) not null unique,
    role varchar(10) not null,
    password_hash varchar(64) not null,
    created_date timestamp not null,
    constraint pk_user primary key(id_user)
);

create table user_data(
    id_user int not null,
    name varchar(64) null,
    lastname varchar(64) null,
    photo_url varchar(256) null,
    genre varchar(1) null,
    constraint pk_user_data primary key(id_user),
    constraint fk_user_data_user foreign key(id_user) references user(id_user)
);

create table tweet(
    id_tweet int not null auto_increment,
    created_date timestamp not null,
    message varchar(256) not null,
    id_user int not null,
    constraint pk_tweet primary key(id_tweet),
    constraint fk_tweet_user foreign key(id_user) references user(id_user)
);

create table tweet_like(
    id_tweet_like int not null auto_increment,
    id_tweet int not null,
    id_user int not null,
    constraint pk_tweet_like primary key(id_tweet_like),
    constraint fk_tweet_like_tweet foreign key(id_tweet) references tweet(id_tweet),
    constraint fk_tweet_like_user foreign key(id_user) references user(id_user)
);

alter table tweet_like add constraint uni_tweet_like_tweet unique(id_tweet, id_user);

alter table tweet_like drop constraint fk_tweet_like_tweet;
alter table tweet_like add constraint fk_tweet_like_tweet foreign key(id_tweet) references tweet(id_tweet) on delete cascade;

insert into tweet(created_date, message, id_user) values (now(), 'Relegaron, pues, al creador y maestro al término de suyo un tanto lejano y oscuro fundador sus coruscantes discípulos y continuadores.', (select id_user from user where user_name = 'carlos.menjivar'));
insert into tweet(created_date, message, id_user) values (now(), 'Sea como fuere, la primera traducción al español de Sein und Zeit está aún contagiada del entusiasmo inicial, hecho que se manifiesta particularmente en el prólogo del traductor', (select id_user from user where user_name = 'carlos.menjivar'));
insert into tweet(created_date, message, id_user) values (now(), 'Después de múltiples ensayos he traducido por realidad de verdad esta palabra, sujeto básico para todo lo que se dice en las obras de Heidegger. ', (select id_user from user where user_name = 'carlos.menjivar'));
insert into tweet(created_date, message, id_user) values (now(), 'La filosofía de Heidegger es una filosofía característicamente filológica o lingüística, en el sentido de que sus filosofemas consisten en considerable proporción en hacer explícito el sentido que encuéntrase implícito en las expresiones.', (select id_user from user where user_name = 'carlos.menjivar'));
insert into tweet(created_date, message, id_user) values (now(), '¿No sería en realidad lo más profundo de concepción y lo más elegante de ejecución, y quizá en definitiva, no precisamente lo menos fiel, sino todo lo contrario, lo más sustancialmente fiel', (select id_user from user where user_name = 'carlos.menjivar'));

insert into tweet_like(id_tweet, id_user) values ((select id_tweet from tweet order by created_date desc limit 1), (select id_user from user where user_name = 'carlos.menjivar'));
