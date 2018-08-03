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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class landingType extends AbstractType
{
  public function __construct()
  {
  }
  
  /**
   *
   * Géré automatiquement, dans l'idéal ne pas toucher.
   * si vous souhaitez ajouter un champs : https://symfony.com/doc/current/forms.html
   * (voir le bas de page pour tous les types de champs possibles.
   *
   * @param FormBuilderInterface $builder
   * @param array                $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    if (BOOL["civilite"]) {
      $builder->add(NAME["civilite"], ChoiceType::class,
        [
          'choices'     => [
            'Madame'   => 'Madame',
            'Monsieur' => 'Monsieur',
          ],
          'choice_attr' => function ($val, $key, $index) {
            if ($key == 'Madame') {
              return ['checked' => 'checked',];
            } else {
              return [];
            }
          },
          'label'       => "Civilité* ",
          'placeholder' => FALSE,
          'expanded'    => TRUE,
          'multiple'    => FALSE,
          'required'    => TRUE,
        ]);
    }
    
    if (BOOL['mail']) { // si le booléen est vrai
      $builder
        ->add(NAME['mail'], EmailType::class, [   //ici le champs
                                                  'attr'  => [
                                                    'placeholder' => FALSE],
                                                  //mail du formulaire
                                                  'label' => 'Email*']);
    }
    if (BOOL['nom']) {
      $builder->add(NAME['nom'], TextType::class, ['attr'  => ['placeholder' => FALSE],
                                                   'label' => 'Nom*']);
    }
    if (BOOL['prenom']) {
      $builder->add(NAME['prenom'], TextType::class, ['attr'  => ['placeholder' => 'Prénom'],
                                                      'label' => 'Prénom :']);
    }
    if (BOOL['numeroAdresse']) {
      $builder->add(NAME['numeroAdresse'], NumberType::class, ['attr'  => ['placeholder' => 'Numéro de rue'],
                                                               'label' => 'Numéro de voie :']);
    }
    if (BOOL['voieAdresse']) {
      $builder->add(NAME['voieAdresse'], TextType::class, ['attr'  => ['placeholder' => 'Nom de la voie'],
                                                           'label' => 'Nom de la voie :']);
    }
    if (BOOL['codePostal']) {
      $builder->add(NAME['codePostal'], IntegerType::class, ['attr'        => ['placeholder'                     => FALSE,
                                                                               'pattern'                         => "[0-9]*",
                                                                               "step"                            => "any",
                                                                               "max"                             => "99999",
                                                                               "data-numeric-input"              => "",
                                                                               "data-numeric-input-nav-disabled" => "",
                                                                               "inputmode"                       => "numeric"],
                                                             'label'       => 'Code Postal*',
                                                             'constraints' => [new Assert\Length(['min' => 5])]]);
    }
    if (BOOL['ville']) {
      $builder->add(NAME['ville'], TextType::class, ['attr'  => ['placeholder' => 'Ville'],
                                                     'label' => 'Ville :']);
    }
    if (BOOL['telephone']) {
      $builder->add(NAME['telephone'], TelType::class,
        [
          'attr'        => [
            'pattern' => '^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$',
          ],
          'label'       => "Téléphone*",
          'required'    => TRUE,
          'constraints' => [
            new Assert\Length(['min' => 10]),
            new Assert\Regex('/^(01|02|03|04|05|06|07|09)/'),
            new Assert\Type('numeric'),
          ],
        ]
      );
    }
    
    
    if (BOOL['entreprise']) {
      $builder->add(NAME['entreprise'], TextType::class, ['attr'  => ['placeholder' => 'Entreprise'],
                                                          'label' => 'Entreprise :']);
    }
    if (BOOL['message']) {
      $builder->add(NAME['message'], TextareaType::class, ['attr'     => ['placeholder' => 'Message'],
                                                           'label'    => 'Message :',
                                                           'required' => FALSE]);
    }
    if (BOOL['opt_in']) {
      $builder->add(NAME['opt_in'], CheckboxType::class, ['required' => FALSE,
                                                          "label"    => "J’accepte de recevoir les Offres de MurGuard par voie électronique."]);
    }
    
    $builder->getForm();
  }
  
  public function setDefaultOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => 'Entity\landing',
    ]);
  }
  
  public function getName()
  {
    return 'landingFormType';
  }
}