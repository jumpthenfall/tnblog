create table tn_category(
id int unsigned auto_increment primary key ,
title varchar(64) not null default '',
order_number smallint not null default 100
)engine = myisam charset=utf8;


insert into tn_category values(null,'life',101),(null,'love',102),(null,'like',103);