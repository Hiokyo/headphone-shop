<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\AddToCartType;
use App\Manager\CartManager;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ViewProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'product.detail')]
    public function detail(Product $product, Request $request, CartManager $cartManager): Response
    {
        $form = $this->createForm(AddToCartType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $item->setProduct($product);

            $cart = $cartManager->getCurrentCart();
            $cart
                ->addItem($item)
                ->setUpdatedAt(new \DateTime());

            $cartManager->save($cart);

            $this->addFlash('Success', "Add To Cart successfully !");
            return $this->redirectToRoute('product.detail', ['id' => $product->getId()]);
        }
        return $this->render('view_product/detail.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
