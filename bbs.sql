CREATE DATABASE IF NOT EXISTS bbs;

use bbs;


create table IF NOT EXISTS t_user(

    f_username      char(50) not null primary key,

    f_password      char(50) not null,

    f_name          char(50) not null,

    f_email         char(50) not null,

    f_logintimes    int not null default 0,

    f_lasttime      datetime,

    f_loginip       char(19), 

    f_loginip       int not null default 0  

);



create table IF NOT EXISTS t_postinfo(

    f_username      char(50) not null primary key,

    f_post_times    int not null default 0,

    f_reply_times   int not null default 0,

    f_enabled       bool not null default true

);



create table IF NOT EXISTS t_board (

    f_id            int not null auto_increment primary key,

    f_name          char(16) not null,

    f_desc          varchar(200) not null,

    f_created_time  datetime not null,

    f_enabled       bool not null default true

);



create table IF NOT EXISTS t_article (

    f_id            int not null auto_increment primary key,

    f_parent_id     int not null default 0,

    f_has_child     bool not null default false,

    f_title         char(50) not null,

    f_username      char(50) not null,

    f_board_id      int not null,

    f_post_time     datetime not null,

    f_ip            char(19) not null,

    f_enabled       bool not null default true

);



create table IF NOT EXISTS t_article_content (

    f_id            int not null auto_increment primary key,

    f_content       text not null,

    f_picture       char(20) null

);





delete from t_user;

insert into t_user(f_username, f_password, f_name, f_email,f_tag)

    values ('admin', md5('123456'), '管理员', 'bob@google.com',1);

insert into t_user(f_username, f_password, f_name, f_email,f_tag)

    values ('bob', md5('123456'), '鲍伯', 'bob@google.com',0);

insert into t_user(f_username, f_password, f_name, f_email,f_tag)

    values ('tom', md5('123456'), '汤姆', 'tom@google.com',0);

insert into t_user(f_username, f_password, f_name, f_email,f_tag)

    values ('rose', md5('123456'), '罗丝', 'rose@hotmail.com',0);

insert into t_user(f_username, f_password, f_name, f_email,f_tag)

    values ('lily', md5('123456'), '丽丽', 'lily@hotmail.com',0);

    

    

delete from t_postinfo;

insert into t_postinfo (f_uname, f_post_times, f_reply_times) values ('bob', 1, 1);

insert into t_postinfo (f_uname, f_post_times, f_reply_times) values ('tom', 1, 1);

insert into t_postinfo (f_uname, f_post_times, f_reply_times) values ('rose', 1, 1);

insert into t_postinfo (f_uname, f_post_times, f_reply_times) values ('lily', 1, 1);

insert into t_postinfo (f_uname, f_post_times, f_reply_times) values ('admin', 0, 0);



delete from t_board;

insert into t_board (f_id, f_name, f_desc, f_created_time)

    values (1, '文艺沙龙', '文艺之事在此畅所欲言吧', now());

insert into t_board (f_id, f_name, f_desc, f_created_time)

    values (2, '绿茵竞技', '绿茵赛场挥洒汗水', now());



delete from t_article;

insert into t_article (f_id, f_parent_id, f_has_child, f_title, f_username, f_board_id, f_post_time, f_ip)

    values (1, 0, 1, '谈谈相声', 'bob', 1, now(), '192.168.0.1');

insert into t_article (f_id, f_parent_id, f_has_child, f_title, f_username, f_board_id, f_post_time, f_ip)

    values (2, 1, 1, 're:谈谈相声', 'tom', 1, now(), '192.168.0.23');

insert into t_article (f_id, f_parent_id, f_has_child, f_title, f_username, f_board_id, f_post_time, f_ip)

    values (3, 1, 0, 're:谈谈相声', 'rose', 1, now(), '192.168.0.22');

insert into t_article (f_id, f_parent_id, f_has_child, f_title, f_username, f_board_id, f_post_time, f_ip)

    values (4, 2, 0, 're:re:谈谈相声', 'bob', 1, now(), '192.168.0.1');

insert into t_article (f_id, f_parent_id, f_has_child, f_title, f_username, f_board_id, f_post_time, f_ip)

    values (5, 2, 0, 're:re:谈谈相声', 'lily', 1, now(), '192.168.0.67');

insert into t_article (f_id, f_parent_id, f_has_child, f_title, f_username, f_board_id, f_post_time, f_ip)

    values (6, 0, 1, '谈谈小品', 'lily', 1, now(), '192.168.0.67');

insert into t_article (f_id, f_parent_id, f_has_child, f_title, f_username, f_board_id, f_post_time, f_ip)

    values (7, 6, 0, 're:谈谈小品', 'bob', 1, now(), '192.168.0.1');

    

delete from t_article_content;

insert into t_article_content (f_id, f_content)

    values(1, '出国了一段时间，回来后发现网上又有了新动向。...');

insert into t_article_content (f_id, f_content)

    values(2, '顶');

insert into t_article_content (f_id, f_content)

    values(3, '顶顶');

insert into t_article_content (f_id, f_content)

    values(4, '顶顶顶');

insert into t_article_content (f_id, f_content)

    values(5, '...此时，争辩这样的作品属于讽刺还是歌颂已经不重要了：他们共同献给你一个神清气爽的夜晚。...');

insert into t_article_content (f_id, f_content)

    values(6, '...要承认...');

insert into t_article_content (f_id, f_content)

    values(7, '顶顶顶顶');

