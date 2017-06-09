<h1>Landing Framework</h1>
<i>Projet réalisé dans le cadre d'un stage chez Maetva : http://www.maetva.com/</i>
<h2>Réalisation</h2>
<ul>
    <li>Silex 2.0</li>
    <li>ORM (https://github.com/dflydev/dflydev-doctrine-orm-service-provider)</li>
</ul>
<h2>Fonctions</h2>
    <p>Landing Generate permet de générer rapidement des landings pages avec : </p>
    <ul>
        <li>Enregistrement en base de données</li>
        <li>Envoi de Mail soit :</li>
            <ul>
                <li>au mail fournit dans le formulaire</li>
                <li>à un mail défini</li>
            </ul>
    </ul>
    <p>avec différents champs (avec vérification) :</p>
    <ul>
        <li>Mail</li>
        <li>Nom</li>
        <li>Prénom</li>
        <li>Adresse (numéro + nom de la voie + conde postale + ville)</li>
        <li>Numéro de téléphone</li>
        <li>Nom de l'entreprise</li>
        <li>Un champ message</li>
        <li>Optionnel (exemple : newsletters)</li>
    </ul>

<h2>Pré-requis</h2>
    <ul>
        <li>Avoir les droits d'aministrations</li>
        <li>Pour l'envoie de mail, avoir un serveur capable d'envoyer des mail via php_mail</li>
    </ul>
<h2> Installation </h2>

    $ git clone https://github.com/Rydkey/landingGenerate.git
    $ cd landingGenerate/
    $ composer install
    $ composer dump-autoload
    $ sudo chmod 777 src/
    $ sudo chmod 777 -R src/
    $ sudo chmod 777 app/config/prod.php