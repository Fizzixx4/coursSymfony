<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureFields(string $pageName): iterable
    {   
        yield AssociationField::new('conference','Conférence');
        yield TextField::new('author','Auteur');
        yield EmailField::new('email','Mail');
        yield TextEditorField::new('text','Commentaire')->hideOnIndex();
        yield NumberField::new('note','Note');
        $createdAt = DateTimeField::new('createdAt','Créé le')->setFormTypeOptions(
            [
                'html5' => true,
                'years' => range(date('Y'),date('Y')+5),
                'widget' => 'single_text',
            ]
        );
        if(Crud::PAGE_EDIT === $pageName){
            yield $createdAt->setFormTypeOption('disabled',true);
        }
        else{
            yield $createdAt;
        }
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commentaire d\'une conférence')
            ->setEntityLabelInPlural('Commentaires d\'une conférence')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFilters(Filters $filters):Filters{
        return $filters
            ->add(EntityFilter::new('conference'))
            ->add(TextFilter::new('author'))
            ->add(TextFilter::new('email'));
    }
}
