--Prints only genres that have been borrowed at least
select full_name "Student Name", value "Genre", count(r.resource_id) "Amount Borrowed" from users u 
join borrowed_resources br on br.user_id=u.user_id 
join physical_resources pr on pr.physical_resource_id=br.physical_resource_id
join resources r on r.resource_id=pr.resource_id
join taggings t on t.resource_id=r.resource_id
join tags ts on t.tag_id=ts.tag_id
where key='genre'
group by full_name, value;


--Prints all genres
select u.full_name as "Student Name", t.value as "Genre", coalesce(g.amount, 0) as "Amount Borrowed"
from (
    select tag_id, value from tags where key='genre'
) t
cross join users u
left join (
    select full_name, value, count(r.resource_id) amount from users u 
    join borrowed_resources br on br.user_id=u.user_id 
    join physical_resources pr on pr.physical_resource_id=br.physical_resource_id
    join resources r on r.resource_id=pr.resource_id
    join taggings t on t.resource_id=r.resource_id
    join tags ts on t.tag_id=ts.tag_id
    where key='genre'
    group by full_name, value
) g on u.full_name=g.full_name and t.value=g.value
order by u.full_name;