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
