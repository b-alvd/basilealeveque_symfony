# Projet Symfony: Basile ALEVEQUE-DESSOLIN

## Vue d'ensemble

Ce projet est un micro-réseau qui permet aux utilisateurs de créer, voir, éditer, aimer, commenter et supprimer des publications. L'application inclut des fonctionnalités d'inscription, d'authentification, d'amitiés et de gestion de profil.

## Routes et fonctionnalités importantes

### Accueil

- **Route** : `/`
  - **Description** : Affiche la page d'accueil avec une liste de publications classées par date de création.
  - **Fonctionnalité** : Cette page permet aux utilisateurs de visualiser les dernières publications ajoutées à la plateforme. 

### Publication

- **Créer une publication**
  - **Route** : `/post/new`
  - **Description** : Affiche un formulaire pour créer une nouvelle publication.
  - **Fonctionnalité** : Les utilisateurs peuvent créer et publier de nouveaux contenus. Après la soumission du formulaire, la publication est enregistrée dans la base de données et affichée sur la page d'accueil.

- **Voir une publication**
  - **Route** : `/post/{id}`
  - **Description** : Affiche une publication et ses commentaires.
  - **Fonctionnalité** : Cette page permet aux utilisateurs de voir les détails d'une publication spécifique ainsi que les commentaires associés. Les utilisateurs peuvent également ajouter des commentaires.

- **Aimer une publication**
  - **Route** : `/post/{id}/like`
  - **Description** : Permet d'aimer et de ne plus aimer une publication.
  - **Fonctionnalité** : Les utilisateurs peuvent exprimer leur appréciation pour une publication en cliquant sur un bouton "J'aime" dans la page `/post/{id}`. Ils peuvent également retirer leur "J'aime".

- **Éditer une publication**
  - **Route** : `/post/{id}/edit`
  - **Description** : Affiche un formulaire pour éditer une publication existante.
  - **Fonctionnalité** : Les utilisateurs peuvent modifier le contenu de leurs publications après les avoir créées. Les modifications sont enregistrées et mises à jour dans la base de données.

- **Supprimer une publication**
  - **Route** : `/post/{id}/delete`
  - **Description** : Supprime une publication de la base de données.
  - **Fonctionnalité** : Les utilisateurs peuvent supprimer leurs propres publications. Une fois supprimée, la publication n'apparaît plus sur la plateforme.

### Profil Utilisateur

- **Voir un profil**
  - **Route** : `/profile/{username}`
  - **Description** : Affiche le profil d'un utilisateur.
  - **Fonctionnalité** : Les utilisateurs peuvent voir les informations de profil des autres utilisateurs, y compris leurs publications et interactions.

- **Ajouter un utilisateur dans sa liste d'amis**
    - **Route** : `/relationship/send-request/{username}`
    - **Description** : Demander un utilisateur à rejoindre sa liste d'amis.
    - **Fonctionnalité** : Les utilisateurs peuvent demander aux autres utilisateurs de rejoindre leur liste d'amis. Une fois la demande envoyée, l'utilisateur peut accepter ou refuser la demande.

- **Voir la liste de toutes les demandes d'amis**
    - **Route** : `/relationship/all-requests`
    - **Description** : Affiche la liste de toutes les demandes d'amis.
    - **Fonctionnalité** : Les utilisateurs peuvent voir la liste de toutes les demandes d'amis.

- **Refuser un utilisateur de sa liste d'amis**
    - **Route** : `/relationship/reject-request/{username}`
    - **Description** : Refuser une demande d'amité d'un utilisateur.
    - **Fonctionnalité** : Les utilisateurs peuvent refuser les demandes d'amité d'autres utilisateurs.

- **Accepter un utilisateur de sa liste d'amis**
    - **Route** : `/relationship/accept-request/{username}`
    - **Description** : Accepter une demande d'amité d'un utilisateur.
    - **Fonctionnalité** : Les utilisateurs peuvent accepter les demandes d'amité d'autres utilisateurs.

-**Supprimer un utilisateur de sa liste d'amis**
    - **Route** : `/relationship/delete/{username}`
    - **Description** : Supprimer un utilisateur de sa liste d'amis.
    - **Fonctionnalité** : Les utilisateurs peuvent supprimer des utilisateurs de leur liste d'amis.


### Inscription et Authentification

- **Inscription**
  - **Route** : `/register`
  - **Description** : Affiche un formulaire pour créer un nouveau compte utilisateur.
  - **Fonctionnalité** : Les nouveaux utilisateurs peuvent créer un compte en fournissant les informations nécessaires comme l'email, le mot de passe, etc.

- **Connexion**
  - **Route** : `/login`
  - **Description** : Affiche un formulaire pour se connecter.
  - **Fonctionnalité** : Les utilisateurs existants peuvent se connecter à leur compte pour accéder à toutes les fonctionnalités de la plateforme.

- **Déconnexion**
  - **Route** : `/logout`
  - **Description** : Déconnecte l'utilisateur.
  - **Fonctionnalité** : Permet aux utilisateurs de se déconnecter de leur compte.
