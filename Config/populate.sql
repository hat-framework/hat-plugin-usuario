INSERT IGNORE INTO `usuario_notify_tipo` (`cod`, `name`) VALUES
(1, 'Comunicado dos administradores do Finance-e'),
(2, 'Divulgação de balanços das empresas favoritas'),
(3, 'Divulgação de comunicados/fatos relevantes das empresas favoritas'),
(4, 'Distribuição de dividendos das empresas favoritas');

INSERT IGNORE INTO `usuario_perfil` (`usuario_perfil_cod`, `usuario_perfil_nome`, `usuario_perfil_pai`, `usuario_perfil_descricao`, `usuario_perfil_default`, `usuario_perfil_tipo`, `display_list`, `path`) VALUES
(1, 'Visitante', 2, 'Perfil destinado aos visitantes do site, qualquer usuário que fizer o próprio cadastro automaticamente', b'1', 'sistema', 's', '/3/2/1'),
(2, 'Administrador', 3, 'Usuários com previlégios administrativos, podem alterar configurações do site', b'0', 'sistema', 's', '/3/2'),
(3, 'Webmaster', NULL, 'Perfil destinado aos Webmasters. Eles terão acesso à todos os dados do site', b'0', 'sistema', 's', '/3'),
(4, 'Assinante Temporário', 2, 'Durante um período pré determinado este usuário terá acesso ao sistema de análise e gestão', b'0', 'usuario', 's', '/3/2/4'),
(5, 'Analista de Informação', 2, 'Usuário para ter Permissão para ver informações do site e todos os recursos de assinantes', b'0', 'usuario', 's', '/3/2/5'),
(6, 'Assinante Analise', 2, 'Usuário com acesso exclusivo para assinantes de analise', b'0', 'usuario', 's', '/3/2/6'),
(7, 'Assinante Gestão', 2, 'Usuário com acesso exclusivo para assinantes de gestão', b'0', 'usuario', 's', '/3/2/7'),
(8, 'Assinante Analise e Gestão', 2, 'Usuário com acesso exclusivo para assinantes de analise e gestão', b'0', 'usuario', 's', '/3/2/8');

/*delimiter |
    CREATE EVENT IF NOT EXISTS usuario_login_upstatus
    ON SCHEDULE EVERY 15 MINUTE
    COMMENT 'Atualiza o status dos usuarios do sistema a cada X minutos'
    DO
       BEGIN
            update usuario set status = 'online'  WHERE (NOW() - user_uacesso) <= 900;
            update usuario set status = 'inativo' WHERE (NOW() - user_uacesso) > 900 AND (NOW() - user_uacesso) <= 3600;
            update usuario set status = 'offline' WHERE (NOW() - user_uacesso) > 3600 OR isnull(user_uacesso) ;
       END |

delimiter ;*/
