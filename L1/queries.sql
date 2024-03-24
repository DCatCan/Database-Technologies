insert into resources (title, media_type) values ('Cool book', 'book');

delete from resources where resource_id=47;

select resources.* from resources join taggings ts on ts.resource_id=resources.resource_id join tags on tags.tag_id=ts.tag_id where key='genre' and value like '%antasy';

select full_name, phone_number from users where admin=true limit 2;

select distinct full_name from users join borrowed_resources br on br.user_id=users.user_id where return_date is null order by full_name;

select * from resources where resource_id not in (select resource_id from physical_resources pr join borrowed_resources br on br.physical_resource_id=pr.physical_resource_id);

select * from users where date_of_birth < '1990-01-01' and date_of_birth >= '1980-01-01';

select count(distinct title), value from resources rs join taggings ts on rs.resource_id=ts.resource_id join tags on ts.tag_id=tags.tag_id where key='author' group by value;

select round(count(distinct br.user_id)::numeric / count(distinct users.user_id) * 100, 2)  from users, borrowed_resources br where current_date - br.expiry_date > 0 and return_date is null;
