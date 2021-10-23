<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Product;
use App\Form\BrandType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class BrandController extends AbstractController
{
    #[Route('/brand', name: 'brand_index')]
    public function BrandIndex()
    {
        $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
        return $this->render(
            'brand/index.html.twig',
            [
                'brands' => $brands
            ]
        );
    }

    #[Route('/brand/detail/{id}', name: 'brand_detail')]
    public function brandDetail($id) {
        $brand = $this->getDoctrine()->getRepository(Brand::class)->find($id);
        if ($brand == null) {
            $this->addFlash('Error', 'Brand is not existed');
            return $this->redirectToRoute('brand_index');
        } else {
            return $this->render(
                'brand/detail.html.twig',
                [
                    'brand' => $brand
                ]
            );
        }
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/brand/delete/{id}', name: 'brand_delete')]
    public function brandDelete($id) {
        $brand = $this->getDoctrine()->getRepository(Brand::class)->find($id);
        
        try {
            if ($brand == null) {
                $this->addFlash('Error', 'Brand is not existed');
            } else {
                $manager = $this->getDoctrine()->getManager();

                $manager->remove($brand);

                $manager->flush();
                $this->addFlash('Success', 'Brand has been deleted successfully !');
            }
        } catch(ForeignKeyConstraintViolationException $e) {
            $this->addFlash('Error', 'Still have product in brand');
        }

        return $this->redirectToRoute('brand_index');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/brand/add', name: 'brand_add')]
    public function brandAdd(Request $request){
        $brand = new Brand();
        $form = $this->createForm(BrandType::class,$brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($brand);
            $manager->flush();

            $this->addFlash("Success", "Add new brand successfully !");
            return $this->redirectToRoute('brand_index');
        }

        return $this->render(
            "brand/add.html.twig", 
            [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/brand/edit/{id}', name: 'brand_edit')]
    public function brandEdit(Request $request, $id){
        $brand = $this->getDoctrine()->getRepository(Brand::class)->find($id);
        $form = $this->createForm(BrandType::class,$brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($brand);
            $manager->flush();

            $this->addFlash("Success", "Edit brand successfully !");
            return $this->redirectToRoute('brand_index');
        }

        return $this->render(
            "brand/edit.html.twig", 
            [
                "form" => $form->createView()
            ]
        );
    }
}