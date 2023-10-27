<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class ArticleCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
        yield SlugField::new('slug')->setTargetFieldName('title');
        yield Field::new('attachmentFile', 'Média')
            ->onlyWhenCreating()
            ->setFormType(FileType::class)
            ->setFormTypeOptions([
                'label' => 'Média',
                // Le libellé du champ
                'required' => false,
                // Champ facultatif
                'mapped' => true,
                // Champ associé à une propriété de l'entité
                'constraints' => [
                    new File([
                        'maxSize' => '3G',
                        // Correspond à la limite définie dans php.ini
                        'maxSizeMessage' => 'Le fichier est trop volumineux. La taille maximale autorisée est {{ limit }}',
                        // Taille maximale du fichier (1 Mo dans cet exemple)
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'video/mp4',
                            'video/quicktime',
                            'video/x-msvideo',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image (JPEG, PNG) ou une vidéo (MP4, MOV, AVI).',
                    ]),
                ],
            ]);
        yield ImageField::new('attachment', 'Image')
            ->setBasePath('/uploads/attachments')
            ->onlyOnIndex();
        yield DateTimeField::new('createdAt')->hideOnForm();
        yield DateTimeField::new('updatedAt')->hideOnForm();
        yield AssociationField::new('categories')->setRequired(true);
    }

}
