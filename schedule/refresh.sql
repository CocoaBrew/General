use dac3251;

drop table courses;
drop table tutors;
drop table course_for_tutor;
drop table available;

create table courses(
  title varchar(255) not null,
  sun varchar(255),
  mon varchar(255),
  tue varchar(255),
  wed varchar(255),
  thu varchar(255),
  fri varchar(255),
  primary key (title)
);

create table tutors(
  id varchar(255) not null,
  name varchar(255),
  email varchar(255),
  education varchar(255),
  work_hrs varchar(255),
  primary key (id)
);

create table course_for_tutor(
  id varchar(255) not null,
  course varchar(255),
  primary key (id)
);

create table available(
  id varchar(255),
  sbusy varchar(255),
  mbusy varchar(255),
  tbusy varchar(255),
  wbusy varchar(255),
  rbusy varchar(255),
  fbusy varchar(255),
  spref varchar(255),
  mpref varchar(255),
  tpref varchar(255),
  wpref varchar(255),
  rpref varchar(255),
  fpref varchar(255),
  primary key (id)
);
