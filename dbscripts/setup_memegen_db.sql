use memegendb;
create table MemeInfo if not exists (
   id bigint auto_increment primary key ,
   filename varchar(256) unique,
   upvotes bigint,
   downvotes bigint,
   createdby varchar(256),
   createdon datetime
);

create table MemeTags if not exists (
   tagid bigint auto_increment primary key,
   memeid bigint,
       index (memeid),
       foreign key (memeid) refenreces MemeInfo(id),
   tag varchar(64)
); 
