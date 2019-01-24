delimiter |
drop function if exists convertDatetimeToDateString |
create function convertDatetimeToDateString (vdate
datetime) returns varchar(40) charset utf8
begin
	return (select DATE_FORMAT(vdate,'%d/%m/%Y'));
end |
delimiter ;