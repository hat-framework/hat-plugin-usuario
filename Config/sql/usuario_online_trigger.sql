CREATE TRIGGER usuario_acesso_update_status 
BEFORE INSERT ON usuario_acesso
FOR EACH ROW
BEGIN
    UPDATE usuario 
    SET user_uacesso = NOW(),
        status       = 'online',
    WHERE cod_usuario = NEW.cod_usuario;
END