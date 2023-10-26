<?php

namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function generateSlug(string $name): string
    {

        return strtolower(str_replace(' ', '-', $name));
    }

    public function createDefaultCategories(): void
    {
        $categoryData = [
            ['name' => 'image', 'color' => '#00FF00'],
            ['name' => 'video', 'color' => '#0000FF']
        ];

        foreach ($categoryData as $data) {
            $name = $data['name'];
            $color = $data['color'];
            $existingCategory = $this->entityManager->getRepository(Category::class)->findOneBy(['name' => $name]);

            if (!$existingCategory) {
                $category = new Category();
                $category->setName($name);
                $category->setColor($color);


                if (!$category->getSlug()) {
                    $category->setSlug($this->generateSlug($name));
                }

                $this->entityManager->persist($category);
            }
        }

        $this->entityManager->flush();
    }
}