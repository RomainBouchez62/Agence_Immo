<?php
/**
 * Created by PhpStorm.
 * User: InfoWare31
 * Date: 19/01/2020
 * Time: 17:31
 */

namespace App\Controller\Admin;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Compo\HttpFoundation\Response;
use Symfony\Compo\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminPropertyController extends AbstractController
{
    private $repository;

    public function __construct(PropertyRepository $repository, ObjectManager $em)
    {
        $this->repository=$repository;
        $this->em=$em;
    }

    /**
     * @Route("/admin", name="admin.property.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $properties=$this->repository->findAll();
        return $this->render('admin/property/index.html.twig',compact('properties'));
    }

    /**
     * @Route("/admin/property/create",name="admin.property.new")
     */
    public function new(Request $request)
    {
        $property=new Property();
        $form=$this->createForm(PropertyType::class,$property);
        $form->handleRequest($request);
        //Verif de la validité du form
        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($property);
            $this->em->flush();
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/new.html.twig',[
            'property'=>$property,
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/admin/property/{id}", name="admin.property.edit")
     * @param Property $property
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Property $property, Request $request)
    {
        //Creation du formulaire
        $form=$this->createForm(PropertyType::class,$property);
        $form->handleRequest($request);
        //Verif de la validité du form
        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/edit.html.twig',[
            'property'=>$property,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @param Property $property
     */
    public function delete(Property $property)
    {
        return new \Symfony\Component\HttpFoundation\Response('Supression');
//        $this->em->remove($property);
//        $this->em->flush();
        return $this->redirectToRoute('admin.property.index');
    }
}