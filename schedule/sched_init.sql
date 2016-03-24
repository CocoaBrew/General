use schedule;

create table courses(
  title varchar(255) not null,
  sun varchar(255),
  mon varchar(255),
  tue varchar(255),
  wed varchar(255),
  thu varchar(255),
  fri varchar(255),
  sat varchar(255),
  primary key (title)
);