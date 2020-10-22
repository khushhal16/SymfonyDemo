<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Product;
use AppBundle\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductController extends Controller{

    /**
     * @Route("/product",name="product_list")
     * @Method({"GET"})
     */
    public function index(){
        // return new Response('<html><body>Hello</body></html>');

        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('products/product.html.twig',array('products'=>$products));
    }
    

    /**
    * @Route("/product/new",name="new_product")
    * Method({"GET","POST"})
    */
public function new(Request $request) {
    $product = new Product();

    $form = $this->createFormBuilder($product)
      ->add('name', TextType::class, array('attr' => array('class' => 'form-control')))
      ->add('description', TextareaType::class, array(
        'required' => false,
        'attr' => array('class' => 'form-control')
      ))
      ->add('category', TextType::class, array(
        'required' => false,
        'attr' => array('class' => 'form-control')
      ))
      ->add('price', TextType::class, array(
        'attr' => array('class' => 'form-control')
      ))
      ->add('save', SubmitType::class, array(
        'label' => 'Create',
        'attr' => array('class' => 'btn btn-primary mt-3')
      ))
    ->getForm();

    $form->handleRequest($request);
    
    if($form->isSubmitted() && $form->isValid()) {
      $product = $form->getData();
      $cName = $product->getCategory();
      $category = new Category();
      $category->setName($category);
      $product->setCategory($category);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($product);
      $entityManager->flush();

      return $this->redirectToRoute('product_list');
    }

    return $this->render('products/new.html.twig', array(
      'form' => $form->createView()
    ));
  }

   

     /**
   * @Route("/product/update/{id}",name="edit_product")
   */
  public function update(Request $request,$id){

    $product = new product();

    $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

    $form = $this->createFormBuilder($product)
    ->add('name', TextType::class, array('attr' => array('class' => 'form-control')))
    ->add('description', TextareaType::class, array(
      'required' => false,
      'attr' => array('class' => 'form-control')
    ))
    ->add('category', TextType::class, array(
      'required' => false,
      'attr' => array('class' => 'form-control')
    ))
    ->add('price', TextType::class, array(
      'attr' => array('class' => 'form-control')
    ))
    ->add('save', SubmitType::class, array(
      'label' => 'Create',
      'attr' => array('class' => 'btn btn-primary mt-3')
    ))
    ->getForm();

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->flush();
      return $this->redirectToRoute('product_list');
    }

    return $this->render('products/edit.html.twig', array(
      'form' => $form->createView()
    ));

}

/**
     * @Route("/product/savee")
     */
    public function createProductAction()
    {
        $category = new Category();
        $category->setName('Computer');
        $category->setDescription('Computer Peripherals');

        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(19.99);
        $product->setDescription('Ergonomic and stylish!');

        // relates this product to the category
        $product->setCategory($category);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($category);
        $entityManager->persist($product);
        $entityManager->flush();

        return new Response(
            'Saved new product with id: '.$product->getId()
            .' and new category with id: '.$category->getId()
        );
    } 

 /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show($id) {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
  
        return $this->render('products/show.html.twig', array('product' => $product));
      }

 /**
     * @Route("/product/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id) {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
  
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();
  
        $response = new Response();
        $response->send();
      }

 

}

