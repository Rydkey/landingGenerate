<?php

/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 07/04/17
 * Time: 10:19
 */

namespace landingBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class landingType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      if(BOOL['mail']){ // si le booléen est vrai
        $builder
          ->add(NAME['mail'],EmailType::class,array(   //ici le champs
            'attr'=>array('placeholder' => 'email'),         //mail du formulaire
            'label'=>'Email : '));
      }
      if(BOOL['nom']){
        $builder->add(NAME['nom'],TextType::class,array('attr'=> array('placeholder'=>'Nom'),'label'=> 'Nom :'));
      }
      if (BOOL['prenom']){
        $builder->add(NAME['prenom'],TextType::class,array('attr'=> array('placeholder'=>'Prénom'),'label'=> 'Prénom :'));
      }
      if(BOOL['numeroAdresse']){
        $builder->add(NAME['numeroAdresse'],NumberType::class,array('attr'=> array('placeholder'=>'Numéro de rue'),'label'=> 'Numéro de voie :'));
      }
      if(BOOL['voieAdresse']){
        $builder->add(NAME['voieAdresse'],TextType::class,array('attr'=> array('placeholder'=>'Nom de la voie'),'label'=> 'Nom de la voie :'));
      }
      if (BOOL['codePostal']){
        $builder->add(NAME['codePostal'],NumberType::class,array('attr'=> array('placeholder'=>'Code Postal'),'label'=> 'Code Postale :','constraints'=>[new Assert\Length(['min'=>5])]));
      }
      if (BOOL['ville']){
        $builder->add(NAME['ville'],TextType::class,array('attr'=> array('placeholder'=>'Ville'),'label'=> 'Ville :'));
      }
      if (BOOL['telephone']){
        $builder->add(NAME['telephone'],TextType::class,['attr'=>['placeholder'=>'N°téléphone','label'=>'Numéro de téléphone'],'constraints'=>[
          new Assert\Length(['min'=>10]),
          new Assert\Regex('/^(01|02|03|04|05|06|07|09)/'),
          new Assert\Type('numeric')
        ]]);
      }
      if (BOOL['entreprise']){
        $builder->add(NAME['entreprise'],TextType::class,array('attr'=> array('placeholder'=>'Entreprise'),'label'=> 'Entreprise :'));
      }
      if (BOOL['message']){
        $builder->add(NAME['message'],TextareaType::class,array('attr'=> array('placeholder'=>'Message'),'label'=> 'Message :','required'=>FALSE));
      }
      if (BOOL['opt_in']){
        $builder->add(NAME['opt_in'],CheckboxType::class,array('required'=>false));
      }
      
      $builder->getForm()
      ;
    }

    public function setDefaultOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Entity\landing'
        ));
    }

    public function getName()
    {
        return 'landingFormType';
    }
}