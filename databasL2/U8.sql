with recursive series(resource_id, length, series_id, path, pages) as (
    select rs.resource_id, 1, coalesce(max(t1.value), rs.title), array[title], coalesce(max(t2.value)::integer, 0)
    from resources rs
    join taggings ts on rs.resource_id=ts.resource_id
    left join tags t1 on ts.tag_id=t1.tag_id and t1.key='series'
    left join tags t2 on ts.tag_id=t2.tag_id and t2.key='pages'
    join prequels p on p.prequel_id=rs.resource_id
    where p.prequel_id not in (select resource_id from prequels)
    group by rs.resource_id
    union all
    select rs.resource_id, t0.length + 1, t0.series_id, array_append(path, rs.title), t0.pages + coalesce(t.value::integer, 0)
    from prequels p
    join series t0 on t0.resource_id=p.prequel_id
    join resources rs on p.resource_id=rs.resource_id
    join taggings ts on rs.resource_id=ts.resource_id
    join tags t on ts.tag_id=t.tag_id
    where t.key='pages'
)
select distinct on (series.series_id) series.series_id as "Series", 
    array_to_string(path, ' -> ') as "Parts", 
    series.length as "Length",
    series.pages as "Pages"
from series
order by series.series_id, series.length desc;