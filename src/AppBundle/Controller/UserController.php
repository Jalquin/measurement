<?php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{
    /**
     * @Route("/profil/{id}", name="profile")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function profileAction($id)
    {

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);


        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route("/users", name="user_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUserAction(){

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null);

        $users = $this->getDoctrine()->getRepository(User::class)
            ->findAll();

        return $this->render('user/usersList.html.twig', [
            'users' => $users
        ]);

    }

    /**
     * @Route("/users/pridat", name="user_add")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createUserAction(Request $request, UserPasswordEncoderInterface $passwordEncoder){

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null);

        $user = new User;
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc

            $this->addFlash('success', 'Přidání uživatele proběhlo úspěšně!');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/createUser.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/upravit/{id}", name="user_edit")
     * @param $id
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUserAction($id, Request $request, UserPasswordEncoderInterface $passwordEncoder){

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null);

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if (!$user) {
            $this->addFlash('warning','Uživatel s ID '.$id.' nenalezen.');

            return $this->redirectToRoute('device_list');
        }

        $user->setUsername($user->getUsername());
        $user->setEmail($user->getEmail());

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc

            $this->addFlash('success', 'Upravení uživatele proběhlo úspěšně!');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/editUser.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/detaily/{id}", name="user_details")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailsUserAction($id){
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (!$user) {
            $this->addFlash('warning','Přístroj s ID '.$id.' nenalezen.');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/userDetails.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/users/odstranit/{id}", name="user_delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserAction($id){

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null);

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            $this->addFlash('warning','Přístroj s ID '.$id.' nenalezen.');

            return $this->redirectToRoute('device_list');
        }

        $em->remove($user);
        $em->flush();

        $this->addFlash(
            'danger',
            'Uživatel s ID '.$id.' odstraněn'
        );

        return $this->redirectToRoute('user_list');
    }
}