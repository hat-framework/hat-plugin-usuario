CREATE EVENT IF NOT EXISTS usuario_login_upstatus
ON SCHEDULE EVERY 15 MINUTE
COMMENT 'Atualiza o status dos usuarios do sistema a cada X minutos'
DO
        update usuario set status = 'offline' WHERE status != 'online' AND (NOW() - user_uacesso) > 3600 OR isnull(user_uacesso) ;
        update usuario set status = 'inativo' WHERE status = 'online'  AND (NOW() - user_uacesso) > 900 AND (NOW() - user_uacesso) <= 3600;
