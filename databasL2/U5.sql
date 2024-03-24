select row_number() over (order by sum(f.amount) desc) as rank, department_program, sum(f.amount) as fine from fines f
join borrowed_resources br on br.borrowed_resource_id=f.borrowed_resource_id
join users u on br.user_id=u.user_id
group by department_program
order by fine desc
limit 5;
