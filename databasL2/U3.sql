select title, count(distinct br.user_id) from resources r
join physical_resources pr on pr.resource_id=r.resource_id
join borrowed_resources br on br.physical_resource_id=pr.physical_resource_id
group by title;