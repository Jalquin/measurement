<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Device;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MeasurementController extends Controller
{
    /**
     * @Route("/", name="device_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(){

        $devices = $this->getDoctrine()->getRepository(Device::class)
            ->findAll();

        return $this->render('measurement/index.html.twig', [
            'devices' => $devices
        ]);

    }

    /**
     * @Route("/category_add", name="category_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createCategoryAction(Request $request){

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null);

        $Category = new Category;

        $form = $this->createFormBuilder($Category)
            ->add('name', TextType::class, ['label' => 'Název kategorie', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('save', SubmitType::class, ['label' => 'Přidat kategorii', 'attr' => ['class' => 'btn btn-primary', 'style' => 'margin-bottom:15px']])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $name = $form['name']->getData();

            $Category->setName($name);

            $em = $this->getDoctrine()->getManager();

            $em->persist($Category);
            $em->flush();

            $this->addFlash('success','Kategorie přidána s id: ' .$Category->getId());

            return $this->redirectToRoute('device_list');
        }

        return $this->render('measurement/create_category.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/pristroje/pridat", name="device_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request){

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null);

        $device = new Device;

        $form = $this->createFormBuilder($device)
            ->add('name', TextType::class, ['label' => 'Název', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('category', EntityType::class, ['label' => 'Kategorie', 'class' => 'AppBundle:Category', 'choice_label' => 'name', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('number', TextType::class, ['label' => 'Číslo uložení', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('addedBy', TextType::class, ['label' => 'Přidáno uživatelem', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('save', SubmitType::class, ['label' => 'Přidat přístroj', 'attr' => ['class' => 'btn btn-primary', 'style' => 'margin-bottom:15px']])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Get Data
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $number = $form['number']->getData();
            $user = $form['addedBy']->getData();

            $now = new\DateTime('now');

            $device->setName($name);
            $device->setCategory($category);
            $device->setnumber($number);
            $device->setDate($now);
            $device->setaddedBy($user);

            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->persist($device);
            $em->flush();

            $this->addFlash('success','Přístroj přidán s id: '.$device->getId() .' a kategorií s id: '.$category->getId());

            return $this->redirectToRoute('device_list');
        }

        return $this->render('measurement/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pristroje/upravit/{id}", name="device_edit")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request){

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null);

        $device = $this->getDoctrine()->getRepository(Device::class)->find($id);

        if (!$device) {
            $this->addFlash('warning','Přístroj s ID '.$id.' nenalezen.');

            return $this->redirectToRoute('device_list');
        }

        $device->setName($device->getName());
        $device->setCategory($device->getCategory());
        $device->setNumber($device->getNumber());
        $device->setAddedBy($device->getAddedBy());

        $form = $this->createFormBuilder($device)
            ->add('name', TextType::class, ['label' => 'Název', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('Category', EntityType::class, ['label' => 'Kategorie', 'class' => 'AppBundle:Category', 'choice_label' => 'name', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])            ->add('number', TextType::class, ['label' => 'Číslo uložení', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('addedBy', TextType::class, ['label' => 'Přidáno uživatelem', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('save', SubmitType::class, ['label' => 'Upravit přístroj', 'attr' => ['class' => 'btn btn-primary', 'style' => 'margin-bottom:15px']])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Get Data
            $name = $form['name']->getData();
            $category = $form['Category']->getData();
            $number = $form['number']->getData();
            $user = $form['addedBy']->getData();

            $now = new\DateTime('now');

            $em = $this->getDoctrine()->getManager();
            $device = $em->getRepository(Device::class)->find($id);

            $device->setName($name);
            $device->setCategory($category);
            $device->setNumber($number);
            $device->setDate($now);
            $device->setAddedBy($user);

            $em->flush();

            $this->addFlash('primary','Přístroj upraven s id: '.$device->getId() .' a kategorií s id: '.$category->getId());

            return $this->redirectToRoute('device_list');
        }

        return $this->render('measurement/edit.html.twig', [
            'device' => $device,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pristroje/detaily/{id}", name="device_details")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailsAction($id){
        $device = $this->getDoctrine()
            ->getRepository(Device::class)
            ->find($id);

        if (!$device) {
            $this->addFlash('warning','Přístroj s ID '.$id.' nenalezen.');

            return $this->redirectToRoute('device_list');
        }

        return $this->render('measurement/details.html.twig', [
            'device' => $device,
        ]);
    }

    /**
     * @Route("/pristroje/odstranit/{id}", name="device_delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id){

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null);

        $em = $this->getDoctrine()->getManager();
        $device = $em->getRepository(Device::class)->find($id);

        if (!$device) {
            $this->addFlash('warning','Přístroj s ID '.$id.' nenalezen.');

            return $this->redirectToRoute('device_list');
        }

        $em->remove($device);
        $em->flush();

        $this->addFlash(
            'danger',
            'Přístroj s ID '.$id.' odstraněn'
        );

        return $this->redirectToRoute('device_list');
    }
}
