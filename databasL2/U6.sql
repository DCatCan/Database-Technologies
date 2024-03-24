select * 
from (
    select 
        title, 
        value as publisher, 
        count(br.borrowed_resource_id) as "Times Borrowed",
        rank() over (partition by value order by count(br.borrowed_resource_id) desc) as rank
    from resources rs
    join taggings ts on rs.resource_id=ts.resource_id
    join tags t on ts.tag_id=t.tag_id
    join physical_resources pr on pr.resource_id=rs.resource_id
    join borrowed_resources br on br.physical_resource_id=pr.physical_resource_id
    where key = 'publisher'
    group by title, value
) x
where x.rank <= 3;