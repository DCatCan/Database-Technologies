select * from (
    select rank() over (order by sum(f.amount) desc) as rank, full_name, sum(f.amount) as fines from users u 
    join borrowed_resources br on br.user_id=u.user_id
    join fines f on br.borrowed_resource_id=f.borrowed_resource_id
    group by full_name
) a
group by full_name, fines, rank
having rank <= ceil(max(rank)::decimal/10)
order by rank;