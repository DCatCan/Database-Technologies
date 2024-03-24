select distinct on (sub.full_name) sub.full_name as "Student name", sub.value as "Genre" from (
    select full_name, value, count(r.resource_id) as c from users u 
    join borrowed_resources br on br.user_id=u.user_id 
    join physical_resources pr on pr.physical_resource_id=br.physical_resource_id
    join resources r on r.resource_id=pr.resource_id
    join taggings t on t.resource_id=r.resource_id
    join tags ts on t.tag_id=ts.tag_id
    where key='genre'
    group by full_name, value
) sub
group by sub.full_name, sub.value, sub.c
having sub.c >= max(sub.c)
order by sub.full_name;
