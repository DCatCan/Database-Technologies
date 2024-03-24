create sequence borrowed_resources_borrowed_resource_id_seq;
create sequence fines_fine_id_seq;
create sequence physical_resources_physical_resource_seq;
create sequence resources_resource_id_seq;
create sequence tags_tag_id_seq;
create sequence users_user_id_seq;

create table resources (
    resource_id integer primary key not null default nextval('resources_resource_id_seq'::regclass),
    title varchar not null,
    media_type varchar not null,
    publication_date date,
    prequel_id integer,
    foreign key (prequel_id) references resources(resource_id)
);

create table tags (
    tag_id integer primary key not null default nextval('tags_tag_id_seq'::regclass),
    key varchar not null,
    value varchar not null
);

create table taggings (
    resource_id integer not null,
    tag_id integer not null,
    foreign key (resource_id) references resources(resource_id),
    foreign key (tag_id) references tags(tag_id)
);

create table physical_resources (
    physical_resource_id integer primary key not null default nextval('physical_resources_physical_resource_seq'::regclass),
    resource_id integer not null,
    foreign key (resource_id) references resources(resource_id)
);

create table users (
    user_id integer primary key not null default nextval('users_user_id_seq'::regclass),
    full_name varchar not null,
    date_of_birth date not null,
    phone_number varchar,
    address varchar not null,
    email varchar not null,
    admin boolean not null default false,
    department_program varchar not null
);

create table borrowed_resources (
    borrowed_resource_id integer primary key not null default nextval('borrowed_resources_borrowed_resource_id_seq'::regclass),
    physical_resource_id integer not null,
    user_id integer not null,
    borrow_date date not null,
    expiry_date date not null,
    return_date date,
    foreign key (physical_resource_id) references physical_resources(physical_resource_id),
    foreign key (user_id) references users(user_id)
);
    
create table fines (
    fine_id integer primary key not null default nextval('fines_fine_id_seq'::regclass),
    borrowed_resource_id integer not null,
    amount integer not null,
    paid boolean not null default false,
    days_overdue integer not null,
    foreign key (borrowed_resource_id) references borrowed_resources(borrowed_resource_id)
);
