<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'product_index')]
    public function productIndex()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render(
            'product/index.html.twig',
            [
                'products' => $products
            ]
        );
    }

    #[Route('/product/detail/{id}', name: 'product_detail')]
    public function productDetail($id) {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if ($product == null) {
            $this->addFlash('Error', 'Product is not existed');
            return $this->redirectToRoute('product_index');
        } else {
            return $this->render(
                'product/detail.html.twig',
                [
                    'product' => $product
                ]
            );
        }
    }

    #[Route('/product/delete/{id}', name: 'product_delete')]
    public function productDelete($id) {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if ($product == null) {
            $this->addFlash('Error', 'Product is not existed');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($product);

            $manager->flush();
            $this->addFlash('Success', 'Product has been deleted successfully !');
        }
        return $this->redirectToRoute('product_index');
    }

    #[Route('/product/add', name: 'product_add')]
    public function productAdd(Request $request){

    }

    #[Route('/product/edit/{id}', name: 'product_edit')]
    public function productEdit(Request $request, $id){

    }
}
