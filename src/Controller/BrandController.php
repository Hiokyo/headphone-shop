<?php

namespace App\Controller;

use App\Entity\Brand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/brand/delete/{id}', name: 'brand_delete')]
    public function brandDelete($id) {
        $brand = $this->getDoctrine()->getRepository(Brand::class)->find($id);
        if ($brand == null) {
            $this->addFlash('Error', 'Brand is not existed');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($brand);

            $manager->flush();
            $this->addFlash('Success', 'Brand has been deleted successfully !');
        }
        return $this->redirectToRoute('brand_index');
    }

    #[Route('/brand/add', name: 'brand_add')]
    public function brandAdd(Request $request){

    }

    #[Route('/brand/edit/{id}', name: 'brand_edit')]
    public function brandEdit(Request $request, $id){

    }
}