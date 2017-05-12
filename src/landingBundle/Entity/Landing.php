<?php

/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 07/04/17
 * Time: 10:46
 */

namespace landingBundle\Entity;

//sudo php vendor/bin/doctrine orm:generate:entities --filter=Entity src/
//met à jour l'objet ci-dessous

//sudo php vendor/bin/doctrine orm:schema-tool:create --dump-sql
//affiche la requête de création de la BDD (enlever --dump-sql pour effectué la création)

//sudo php vendor/bin/doctrine orm:schema-tool:update --force --dump-sql
//affiche la requête de MaJ de la BDD (enlever --dump-sql pour effectué le changement)

/**
 * Form
 * @Table(name="/table\")
 * @Entity()
 */

class Landing
{
  /**
   * @Id
   * @Column(type="integer")
   * @GeneratedValue(strategy="AUTO")
   */
  protected $id;
//ecrire_ici
}
