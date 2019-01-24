delimiter |
drop function if exists createUrlPathToImg |
create function createUrlPathToImg (vphotoname
varchar(40)) returns varchar(100) charset utf8
begin
	return (select concat('/media/', vphotoname));
end |
delimiter ;