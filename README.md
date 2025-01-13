# Système de gestion de boutique en ligne (E-commerce Management)
## Description :
Créez une plateforme e-commerce qui permet aux utilisateurs de parcourir des produits, d'ajouter des articles à un panier, de passer des commandes et de suivre leurs achats. Les administrateurs peuvent gérer les produits, les catégories, les commandes et les utilisateurs.

## Diagramme de cas d'utilisation :
- Acteurs :

- Client : Peut parcourir les produits, ajouter des articles au panier, passer une commande, suivre ses commandes.
- Administrateur : Peut gérer les produits, les catégories, les commandes, et les utilisateurs.
## Cas d'utilisation :

- Parcourir les produits : Les clients peuvent voir une liste de produits avec des détails comme le prix, la description et les avis.
- Ajouter au panier : Les clients peuvent ajouter un ou plusieurs articles au panier pour un achat ultérieur.
- Passer une commande : Les clients peuvent finaliser leurs achats en passant une commande après avoir fourni des informations de paiement et de livraison.
- Suivre les commandes : Les clients peuvent consulter l'état de leurs commandes (en cours, expédiée, livrée).
- Gérer les produits : L’administrateur peut ajouter, modifier ou supprimer des produits de la boutique.
- Gérer les catégories : L’administrateur peut organiser les produits en catégories pour une meilleure navigation.
- Gérer les commandes : L’administrateur peut consulter, modifier ou annuler les commandes.
- Gérer les utilisateurs : L’administrateur peut superviser les comptes clients et résoudre les litiges.

## Diagramme de classes :
- Classe "Produit" :

Attributs : ID, nom, description, prix, stock, catégorie.
Méthodes : modifierStock(), afficherDetails().
- Classe "Catégorie" :

Attributs : ID, nom, description.
Méthodes : ajouterProduit(), supprimerProduit().
- Classe "Utilisateur" :

Attributs : ID, nom, email, rôle (client ou administrateur).
Méthodes : seConnecter(), seDéconnecter().
- Classe "Client" (hérite de "Utilisateur") :

Attributs : panier, historiqueCommandes.
Méthodes : ajouterAuPanier(), passerCommande(), suivreCommande().
- Classe "Administrateur" (hérite de "Utilisateur") :

Méthodes : ajouterProduit(), modifierProduit(), supprimerProduit(), gérerCommandes(), gérerUtilisateurs().
- Classe "Commande" :

Attributs : ID, listeProduits, total, statut, date, client.
Méthodes : modifierStatut(), calculerTotal().
- Classe "Panier" :

Attributs : listeProduits, total.
Méthodes : ajouterProduit(), supprimerProduit(), viderPanier(), calculerTotal().

## Partie backend en PHP :
- Implémentez des fonctionnalités :
Affichage des produits depuis une base de données.
Gestion du panier (ajout, suppression, calcul du total).
Création de commandes (insertion dans une base de données avec un lien client-produit).
- Intégrez des validations :
Vérifiez la disponibilité des produits avant de permettre l'ajout au panier.
Empêchez les commandes sans informations de paiement complètes.
- Ajoutez une gestion des rôles :
Accordez des privilèges d’administration uniquement aux utilisateurs ayant le rôle approprié.
- Modalités pédagogiques :
Diagramme de cas d'utilisation : 20 min
Diagramme de classes : 40 min
Implémentation des classes et relations : 30 min
Ajout de fonctionnalités spécifiques comme la gestion du stock et des commandes : 30 min
Durée totale : 2 heures (120 minutes).

## Critères de performance :
Bonne structuration des cas d'utilisation et des classes UML.
Implémentation correcte des fonctionnalités principales (gestion du panier, commandes, administration).
Respect des principes de la POO avec une utilisation efficace des rôles et privilèges.
## Modalités d'évaluation :
Présentation du code et de la structure de la base de données.
Démonstration fonctionnelle des cas d'utilisation principaux (parcourir produits, commander, gérer produits).
Analyse de la qualité du code (réutilisation, modularité).
