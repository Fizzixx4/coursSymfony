<?php

namespace App\Controller\Admin;

use App\Entity\Conference;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class ConferenceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Conference::class;
    }

    public function configureFields(string $pageName): iterable
    {   
        yield TextField::new('name')->setLabel('Nom');
        yield TextField::new('year','Année');
        yield TextField::new('city','Ville');
        yield BooleanField::new('international','Internationale ?');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Conférence')
            ->setEntityLabelInPlural('Conférences')
            ->setDefaultSort(['year' => 'DESC']);
    }

    public function configureFilters(Filters $filters):Filters{
        return $filters
            ->add(TextFilter::new('city'))
            ->add(TextFilter::new('year'));
    }
}
