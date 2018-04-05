<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Pristroj;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MeasurementController extends Controller
{
    /**
     * @Route("/", name="device_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(){

        $pristroje = $this->getDoctrine()->getRepository(Pristroj::class)
            ->findAll();

        return $this->render('measurement/index.html.twig', [
            'pristroje' => $pristroje
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

        $Pristroj = new Pristroj;

        $form = $this->createFormBuilder($Pristroj)
            ->add('Nazev', TextType::class, ['label' => 'Název', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('Category', EntityType::class, ['label' => 'Kategorie', 'class' => 'AppBundle:Category', 'choice_label' => 'name', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('CisloUlozeni', TextType::class, ['label' => 'Číslo uložení', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('PridanoUzivatelem', TextType::class, ['label' => 'Přidáno uživatelem', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('save', SubmitType::class, ['label' => 'Přidat přístroj', 'attr' => ['class' => 'btn btn-primary', 'style' => 'margin-bottom:15px']])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Get Data
            $name = $form['Nazev']->getData();
            $Category = $form['Category']->getData();
            $number = $form['CisloUlozeni']->getData();
            $user = $form['PridanoUzivatelem']->getData();

            $now = new\DateTime('now');

            $Pristroj->setNazev($name);
            $Pristroj->setCategory($Category);
            $Pristroj->setCisloUlozeni($number);
            $Pristroj->setDatumPridani($now);
            $Pristroj->setPridanoUzivatelem($user);

            $em = $this->getDoctrine()->getManager();

            $em->persist($Category);
            $em->persist($Pristroj);
            $em->flush();

            $this->addFlash('success','Přístroj přidán s id: '.$Pristroj->getId() .' a kategorií s id: '.$Category->getId());

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

        $Pristroj = $this->getDoctrine()->getRepository(Pristroj::class)->find($id);

        if (!$Pristroj) {
            $this->addFlash('warning','Přístroj s ID '.$id.' nenalezen.');

            return $this->redirectToRoute('device_list');
        }

        $Pristroj->setNazev($Pristroj->getNazev());
        $Pristroj->setCategory($Pristroj->getCategory());
        $Pristroj->setCisloUlozeni($Pristroj->getCisloUlozeni());
        $Pristroj->setPridanoUzivatelem($Pristroj->getPridanoUzivatelem());

        $form = $this->createFormBuilder($Pristroj)
            ->add('Nazev', TextType::class, ['label' => 'Název', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('Category', EntityType::class, ['label' => 'Kategorie', 'class' => 'AppBundle:Category', 'choice_label' => 'name', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])            ->add('CisloUlozeni', TextType::class, ['label' => 'Číslo uložení', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('PridanoUzivatelem', TextType::class, ['label' => 'Přidáno uživatelem', 'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px']])
            ->add('save', SubmitType::class, ['label' => 'Upravit přístroj', 'attr' => ['class' => 'btn btn-primary', 'style' => 'margin-bottom:15px']])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Get Data
            $name = $form['Nazev']->getData();
            $Category = $form['Category']->getData();
            $number = $form['CisloUlozeni']->getData();
            $user = $form['PridanoUzivatelem']->getData();

            $now = new\DateTime('now');

            $em = $this->getDoctrine()->getManager();
            $Pristroj = $em->getRepository('AppBundle:Pristroj')->find($id);

            $Pristroj->setNazev($name);
            $Pristroj->setCategory($Category);
            $Pristroj->setCisloUlozeni($number);
            $Pristroj->setDatumPridani($now);
            $Pristroj->setPridanoUzivatelem($user);

            $em->flush();

            $this->addFlash('primary','Přístroj upraven s id: '.$Pristroj->getId() .' a kategorií s id: '.$Category->getId());

            return $this->redirectToRoute('device_list');
        }

        return $this->render('measurement/edit.html.twig', [
            'Pristroj' => $Pristroj,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pristroje/detaily/{id}", name="device_details")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailsAction($id){
        $Pristroj = $this->getDoctrine()
            ->getRepository(Pristroj::class)
            ->find($id);

        if (!$Pristroj) {
            $this->addFlash('warning','Přístroj s ID '.$id.' nenalezen.');

            return $this->redirectToRoute('device_list');
        }

        return $this->render('measurement/details.html.twig', [
            'Pristroj' => $Pristroj,
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
        $Pristroj = $em->getRepository(Pristroj::class)->find($id);

        if (!$Pristroj) {
            $this->addFlash('warning','Přístroj s ID '.$id.' nenalezen.');

            return $this->redirectToRoute('device_list');
        }

        $em->remove($Pristroj);
        $em->flush();

        $this->addFlash(
            'danger',
            'Přístroj s ID '.$id.' odstraněn'
        );

        return $this->redirectToRoute('device_list');
    }
}
