
To properly run Bundle you have to do following things:

1. copy content of folder 'web' from bundle into folder "web" symfony

2. add to file 'config.yml' following parameters:

    session:
        cookie_lifetime: 999999

    twig:
        paths:
          '%kernel.root_dir%/../src/ShortLinkBundle/Resources/views': short

3. add to file 'routing.yml' following parameters:

    short:
        resource: "@ShortLinkBundle/Resources/config/routing.yml"



4. for creating table in db execute this command:

    php bin/console doctrine:schema:update --force
