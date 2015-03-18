CREATE EVENT IF NOT EXISTS usuario_removetag
ON SCHEDULE EVERY 60 MINUTE
STARTS '2015-01-01 00:00:00' ON COMPLETION PRESERVE ENABLE 
DO
    DELETE hat.usuario_usertag
    FROM hat.usuario_usertag 
    INNER JOIN usuario_tag ON ( usuario_tag.cod_tag = usuario_usertag.cod_tag) 
    WHERE tag_expires_time IS NOT NULL AND
    (NOW() - dt_tag > tag_expires_time * 86400) ;