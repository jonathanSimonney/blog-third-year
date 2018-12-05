<?php

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class RoleListType extends AbstractType
{
    private $roleHierarchy;
//
    public function __construct(RoleHierarchyInterface $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
//        var_dump($this->roleHierarchy->getReachableRoles([new Role('ROLE_ADMIN')]));
//        die;
        $resolver->setDefaults(
            array('choices' =>
                ['ROLE_ADMIN' => 'ROLE_ADMIN', 'ROLE_USER' => 'ROLE_USER', 'ROLE_BLOGGER' => 'ROLE_BLOGGER'],//todo get these with commented lines above.
                'multiple' => true,
            )
        );
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix()
    {
        return 'role_list';
    }
}