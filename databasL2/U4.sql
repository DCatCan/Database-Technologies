--For whole year

select 
    coalesce(br1.weeks, br2.weeks, br3.weeks) as week, 
    coalesce(borrowed, '0') as "Borrowed", 
    coalesce(returned, '0') as "Returned",
    coalesce(missing, '0') as "Missing"
from 
(
    select to_char(borrow_date, 'WW') as weeks, count(physical_resource_id) as borrowed
    from borrowed_resources
    group by weeks
) br1
full outer join (
    select to_char(return_date, 'WW') as weeks, count(physical_resource_id) as returned 
    from borrowed_resources
    where return_date is not null
    group by weeks
) br2 on br1.weeks=br2.weeks
full outer join (
    select to_char(expiry_date, 'WW') as weeks, count(physical_resource_id) as missing
    from borrowed_resources
    where return_date is null
    or expiry_date < return_date
    group by weeks
) br3 on br1.weeks=br3.weeks
group by week, borrowed, returned, missing
order by week;

--Choose month --change month by changing current date to another date(do not forget ::date)
select w.weeks as "Week",
    coalesce(borrowed, '0') as "Borrowed", 
    coalesce(returned, '0') as "Returned",
    coalesce(missing, '0') as "Missing"
from (
    select date_part('week', generate_series( 
    date_trunc('month', current_date)::timestamp, 
    (date_trunc('month', current_date) + interval '1 month' - interval '1 day')::timestamp, 
    '1 week'::interval))::varchar as weeks
) w
left join 
(
    select to_char(borrow_date, 'WW') as weeks, count(physical_resource_id) as borrowed
    from borrowed_resources
    group by weeks
) br1 on w.weeks=br1.weeks
left join (
    select to_char(return_date, 'WW') as weeks, count(physical_resource_id) as returned 
    from borrowed_resources
    where return_date is not null
    group by weeks
) br2 on w.weeks=br2.weeks
left join (
    select to_char(expiry_date, 'WW') as weeks, count(physical_resource_id) as missing
    from borrowed_resources
    where return_date is null
    or expiry_date < return_date
    group by weeks
) br3 on w.weeks=br3.weeks
group by w.weeks, borrowed, returned, missing
order by w.weeks;