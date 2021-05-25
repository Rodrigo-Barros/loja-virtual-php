# Dependências do Projeto
 - php 
 - mysql 
 - apache2 com rewrite engine habiltada
 - php com extensão pdo

# Script
  Esse projeto vem com um script que permite a inserção de novos administradores. 
Para configure rode `./configure "email" "email_pass" "display_name"`, sendo que
`email                    é email da conta de administrador(a)`
`email_pass    é a senha para autenticação do administrador(a)` 
`display_name  é o nome de exibição do administrador no painel`

## Funcionamento
  O script considera o usuário padrão do banco de dados, o usuário admin então se o 
MySQL estiver usando outro apenas mude de acordo.
  Passando o parametro `--reset-db` o seu banco de dados será resetado e as imagens
dentro de public/uploads serão deletadas você deverá configurar um novo 
administrador para poder usar atráves do seguinte comando: 
`configure email@domain mysupersecurepassword mydisplayname`.
