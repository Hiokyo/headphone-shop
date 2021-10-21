<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\AddToCartType;
use App\Manager\CartManager;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\throwException;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductController extends AbstractController
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

            return $this->redirectToRoute('product.detail', ['id' => $product->getId()]);
        }
        return $this->render('product/home-detail.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
    

    #[Route('/product', name: 'product_index')]
    public function productIndex() {
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
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $image = $product->getImage();
            $imgName = uniqid(); //unique id
            $imgExtension = $image->guessExtension();
            $imageName = $imgName . "." . $imgExtension;
            
            try {
              $image->move(
                  $this->getParameter('product_image'), $imageName
              );  
            } catch (FileException $e) {
                throwException($e);
            }
            
            $product->setImage($imageName);
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            $this->addFlash('Success', "Add new Product successfully !");
            return $this->redirectToRoute("product_index");
        }

        return $this->render(
            "product/add.html.twig",
            [
                "form" => $form->createView()
            ]
        );
    }

    #[Route('/product/edit/{id}', name: 'product_edit')]
    public function productEdit(Request $request, $id){
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $file = $form['image']->getData();

            if($file != null){
                
                $image = $product->getImage();
                $imgName = uniqid(); //unique id
                $imgExtension = $image->guessExtension();
                $imageName = $imgName . "." . $imgExtension;
                
                try {
                $image->move(
                    $this->getParameter('product_image'), $imageName
                    
                );  
                } catch (FileException $e) {
                    throwException($e);
                }
                
                $product->setImage($imageName);
            }
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            
            $this->addFlash('Success', "Edit Product successfully !");
            return $this->redirectToRoute("product_index");
        }

        return $this->render(
            "product/edit.html.twig",
            [
                "form" => $form->createView()
            ]
        );
    }
}
