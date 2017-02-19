
для запуска проекта необходимо содержимое папки "web" скопировать в папку 'web' symfony
далее в файл config.yml добавить следующие настройки:

session:
    cookie_lifetime: 999999

twig:
    paths:
      '%kernel.root_dir%/../src/ShortLinkBundle/Resources/views': short


и в файл routing.yml добавить:

short:
    resource: "@ShortLinkBundle/Resources/config/routing.yml"


для того чтобы создать таблицу в бд  используйте эту команду:

php bin/console doctrine:schema:update --force
