CREATE TABLE client(
    id_client int NOT NULL AUTO_INCREMENT,
    nom_client varchar(70) NOT NULL,
    prenom_client varchar(70) NOT NULL,
    tel_client varchar(70) NOT NULL,
    email_client varchar(70) NOT NULL,
    password_client varchar(255) NOT NULL,
    PRIMARY KEY (id_client)
);
CREATE TABLE camion(
    id_camion int NOT NULL AUTO_INCREMENT,
    marque_camion varchar(70) NOT NULL,
    numplaque_camion varchar(70) NOT NULL,
    couleur_camion varchar(70) NOT NULL,
    PRIMARY KEY (id_camion)
);
CREATE TABLE commande(
    id_commande int NOT NULL AUTO_INCREMENT,
    id_client int NOT NULL,
    id_camion int NOT NULL,
    datelivraison_commande timestamp NOT NULL,
    lieulivraison_commande varchar(150) NOT NULL,
    quantitesable_commande int NOT NULL,
    prix_commande int NOT NULL,
    date_commande timestamp NOT NULL,
    statut_commande varchar(70) NOT NULL DEFAULT 'pending', -- en attente / validé
    PRIMARY KEY (id_commande),
    FOREIGN KEY (id_client) REFERENCES client(id_client),
    FOREIGN KEY (id_camion) REFERENCES camion(id_camion)
);
CREATE TABLE panne(
    id_panne int NOT NULL AUTO_INCREMENT,
    id_camion int NOT NULL,
    datedebut_panne timestamp NOT NULL,
    datefin_panne timestamp NULL,
    cout_panne int NOT NULL,
    PRIMARY KEY (id_panne),
    FOREIGN KEY (id_camion) REFERENCES camion(id_camion)
);
