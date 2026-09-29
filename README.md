https://gsgosselies.be

## Lancer le site avec Docker

Prérequis : Docker Desktop (ou Docker Engine avec le plugin `docker compose`).

```bash
docker compose up --build
```

Au premier démarrage, le conteneur PHP :

1. installe les dépendances (`composer install`, dossier `app/vendor/`) si elles manquent ;
2. applique les migrations (`php spark migrate --all`), puis à chaque démarrage les nouvelles migrations ;
3. ajoute les données de test (`php spark db:seed DatabaseSeeder`), seulement si la base est vide ;
4. démarre php-fpm.

| Adresse | Contenu |
|---|---|
| http://localhost:8080 | le site |
| http://localhost:8025 | Mailpit : les e-mails envoyés par le site (mot de passe oublié, newsletter...) |

Comptes de test (mot de passe = adresse e-mail) :

| Compte | Rôle |
|---|---|
| `super_admin@gmail.com` | super administrateur |
| `guide_admin@gmail.com` | administrateur guide |
| `scout_admin@gmail.com` | administrateur scout |
| `asbl_admin@gmail.com` | administrateur ASBL |

### Site exemple (vitrine)

```bash
docker compose --profile vitrine up --build
```

En plus du démarrage normal, le service `vitrine` remplit la base avec un site exemple, puis s'arrête :

- 26 responsables de section avec photo (pages staff et présentations) ;
- 10 albums, environ 80 photos, dont quelques-unes réservées aux membres connectés ;
- 15 événements passés et à venir, avec images ;
- 8 documents PDF (guide et scout) ;
- des demandes d'inscription dans tous les statuts, des messages de contact, des abonnés à la newsletter et l'historique.

Les images sont dessinées par le seed (`app/Database/Seeds/VitrineImages.php`) : pas besoin d'internet. Les dates suivent la date du jour.
Les responsables ont un compte `prenom.nom@exemple.be` (mot de passe = adresse e-mail).

La vitrine ne s'ajoute qu'une fois (rien n'est fait si des albums existent). Pour repartir de zéro : `docker compose down -v`, puis relancer la commande.
Sans Docker : `php spark db:seed VitrineSeeder`.

### Commandes utiles

```bash
docker compose logs -f php                  # démarrage, migrations, erreurs PHP
docker compose exec php php spark migrate   # lancer les migrations à la main
docker compose down                         # arrêter (la base est conservée)
docker compose down -v                      # arrêter et supprimer la base (repartir de zéro)
```

### Configuration

La configuration Docker est dans `docker-compose.yml` (service `php`, `environment`). Elle a priorité sur un éventuel `app/.env`.

- Base de données : SQLite, dans le volume Docker `database` (`/var/lib/usgg/usgg.db`).
- `USGG_SEED: "false"` : ne pas ajouter les comptes de test.
- Connexion Google : renseigner `GOOGLE_CLIENT_ID` et `GOOGLE_CLIENT_SECRET`.

Le code est monté dans les conteneurs : les modifications de `app/` sont visibles sans reconstruire. Il faut reconstruire (`--build`) seulement après une modification de `.docker/`.
