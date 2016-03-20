use memegendb;
create table if not exists MemeInfo (
    meme_id bigint auto_increment,
    filename varchar(256) unique,
    upvotes bigint,
    downvotes bigint,
    createdby varchar(256),
    createdon datetime,
    primary key (meme_id)
);

create table if not exists MemeTags (
    tag_id bigint auto_increment primary key,
    meme_id bigint,
    tag varchar(64),
    index using hash (meme_id),
    foreign key (meme_id) references MemeInfo(meme_id) on delete cascade on update cascade
); 
