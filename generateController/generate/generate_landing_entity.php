<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 10/05/17
 * Time: 17:50
 *
 * @param $fields
 * @param $dbname
 *
 * @return bool
 */

function generate_landing_entity($fields,$dbname){
  $landing_entity='';
  foreach ($fields as $field){
    if (!empty($field)){
      $landing_entity.='
  /**
  * @Column(type="string", nullable=true)
  *
  */
  protected $'.$field.';';
    }
  }
  $landing_entity.='
  /**
   * @Column(type="datetime", nullable=true)
   *
   */
  protected $date_enregistrement;
  
  public function __construct(){
      $this->date_enregistrement = new \DateTime();
  }
  ';
  $file= __DIR__."/../../src/landingBundle/Entity/Landing.php";
  $content=file_get_contents($file);
  $content = str_replace('/table\\',$dbname,$content);
  $content = str_replace('//ecrire_ici',$landing_entity,$content);
  file_put_contents($file,$content);
  return true;
}