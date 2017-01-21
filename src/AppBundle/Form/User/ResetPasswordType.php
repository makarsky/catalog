<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.01.17
 * Time: 20:44
 */
namespace AppBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('email', EmailType::class)
            ->add('send', SubmitType::class, [
                'label' => 'Submit',
            ]);
    }
}