<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Department;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,[
                "required" => true
            ])
            ->add('lastName',TextType::class,[
                "required" => true
            ])
            ->add('mail',EmailType::class,[
                "required" => true
            ])
            ->add('message',TextareaType::class,[
                "required" => true
            ])
            ->add('departmentDestination',EntityType::class,[
                'class' => Department::class,
                'choice_label' => function(Department $department){
                    return $department->getNameDepartment();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
