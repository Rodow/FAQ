# FAQ 

## Les docs en plus 
> FAQ/zdocs/

- MCV de la table
- MLD du concepteur PMA

Rendre le projet fonctionnel :

    - `composer install`
    - Modification du .env en .env.local + mise a jour des infos BDD
    - `php bin/console doctrine:database:create`
    - `php bin/console doctrine:migration:migrate`
    - `php bin/console doctrine:fixtures:load`

## Utilisateurs 
Il est possible de se connecter en Utilisateur standard ou en utilisateur Admin

ROLE_USER : 
    username : `user`
    mot de passe : `user`

ROLE_MODERATOR :
    username : `moderator`
    mot de passe : `moderator`

ROLE_ADMIN :
    username : `admin`
    mot de passe : `admin`