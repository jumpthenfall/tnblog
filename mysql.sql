
--------------------------创建article表格
create table tn_article(
 id         int          primary key auto_increment comment '主键',
 title      varchar(64)    not null default '' comment '标题',
 summary    varchar(255)   not null default  '摘要',
 content    text	   not null comment '内容',
 create_time   int unsigned not null default 0 comment '创建时间',
 author_id int unsigned not null default 0 comment '作者id',
 author_name  varchar(30) not null default '' comment '作者名', 
 category_id  int unsigned not null default 0 comment '分类id',
 cover  varchar(255) not null default '' comment '封面',
 status enum ('publish','save') comment '状态',
 is_delete tinyint unsigned not null default 0 comment '是否删除',
 tags varchar(255) not null default '' comment '标签'
 )engine=myisam charset=utf8;



 update tn_article set category_id=6 where id =0;




 insert into tn_article values(
 null,
 '生命的跑道

',
 '




在这有力的拥抱中，他意识到自己还活着，而且是站在实实在在的大地上…… 
',
 '
　　




　　1999年3月16日下午6点25分，22岁的见习飞行员亚瑟，驾驶着塞斯拉小型飞机从昆士兰州的莫里机场起飞，准备独自穿越澳大利亚东部的大分水岭山脉，完成他最后一段见习飞行。 
　　飞机飞行半个小时以后，亚瑟进入了大分水岭的边缘区域。这时，一团浓黑的云层从东边太平洋海面上横压过来，转眼之间，狂荡的气流掠过飞机，失衡的机体强烈颠簸起来。 
......
',
unix_timestamp(),
000,
'xiaoming',
000,
'plant1.jpg',
'publish',
0,
'like'
);






--------------------创建category数据表

create table tn_category(
id int unsigned auto_increment primary key comment 'id',
title varchar(64) not null default '' comment '标题',
order_number smallint not null default 100 comment '序列号'
)engine = myisam charset=utf8;


insert into tn_category values(null,'技术交流',101),(null,'资源共享 ',102),(null,'感悟心得',103);


-------------------------创建用户表
create table tn_user(
id int unsigned auto_increment primary key comment 'id',
username varchar(64) unique not null default '' comment '用户名',
password char(32) not null default '' comment '密码',
nickname varchar(64) not null default '' comment '昵称',
logo varchar(128) not null default '' comment '头像',
create_time int unsigned not null default 0 comment '注册时间'
)engine=myisam charset=utf8;


insert into tn_user values (null,'xiaoming',md5('xiaoming'),'chaojixiaoming','avatar2.png',unix_timestamp());
