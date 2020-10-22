<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller{

    /**
     * @Route("/category",name="category_list")
     * @Method({"GET"})
     */
    public function index(){
        // return new Response('<html><body>Hello</body></html>');

        $categorys = $this->getDoctrine()->getRepository(category::class)->findAll();

        return $this->render('categorys/category.html.twig',array('categorys'=>$categorys));
    }
    

    /**
    * @Route("/category/new",name="new_category")
    * Method({"GET","POST"})
    */
public function new(Request $request) {
    $category = new Category();

    $form = $this->createFormBuilder($category)
      ->add('name', TextType::class, array('attr' => array('class' => 'form-control')))
      ->add('description', TextareaType::class, array(
        'required' => false,
        'attr' => array('class' => 'form-control')
      ))
      ->add('save', SubmitType::class, array(
        'label' => 'Create',
        'attr' => array('class' => 'btn btn-primary mt-3')
      ))
      ->getForm();

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()) {
      $category = $form->getData();

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($category);
      $entityManager->flush();

      return $this->redirectToRoute('category_list');
    }

    return $this->render('categorys/new.html.twig', array(
      'form' => $form->createView()
    ));
  }

  
     /**
   * @Route("/category/update/{id}",name="edit_category")
   */
  public function update(Request $request,$id){

    $category = new Category();

    $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

    $form = $this->createFormBuilder($category)
      ->add('name', TextType::class, array('attr' => array('class' => 'form-control')))
      ->add('description', TextareaType::class, array(
        'required' => false,
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
      return $this->redirectToRoute('category_list');
    }

    return $this->render('categorys/edit.html.twig', array(
      'form' => $form->createView()
    ));

}

 /**
     * @Route("/category/{id}", name="category_show")
     */
    public function show($id) {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
  
        return $this->render('categorys/show.html.twig', array('category' => $category));
      }

 /**
     * @Route("/category/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id) {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
  
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
  
        $response = new Response();
        $response->send();
      }


    

}

