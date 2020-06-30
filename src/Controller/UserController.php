<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Json;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation as Doc;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute('home');
        }
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/api/updateInfo", name="updateInfo", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Retourne success true",
     *
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Retourne une erreur 404 user_not_found",
     *
     * )
     *
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string",
     *     description="Email de l'utilisateur"
     * )
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     type="string",
     *     description="Username de l'utilisateur"
     * )
     * @SWG\Parameter(
     *     name="User",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Object User"
     * )
     *
     * @SWG\Tag(name="User")
     */
    public function updateInfo(Request $request)
    {
        $content = $_POST;
        /** @var User $user */
        $user = $this->getUser();

        if(!$user) {
            return new JsonResponse(array(
                'status' => 'user_not_found'
            ), 404);
        }

        $entityManager = $this->getDoctrine()->getManager();


        $user->setEmail($request->get('email')=== null ? $user->getEmail() : $request->get('email'));
        $user->setUsername($request->get('username')=== null ? $user->getUsername() : $request->get('username'));


        $entityManager->persist($user);

        $entityManager->flush();

        return new JsonResponse(
            [
                'success' => true,
            ]
        );
    }

    /**
     * @Route("/api/getAllUsers", name="apiGetAllUsers", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Retourne la liste de tous les utilisateurs",
     *
     * )
     *
     *
     * @SWG\Tag(name="User")
     */
    public function apiGetAllUsers()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $arrayCollection = [];

        /** @var User $item */
        foreach($users as $item) {
            $arrayCollection[] = array(
                'id' => $item->getId(),
                'username' => $item->getUsername(),
                'email' => $item->getEmail(),
                'enabled' => $item->isEnabled(),
                'roles' => $item->getRoles(),
            );
        }
        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/getUser/{id}", name="apiGetUser", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Retourne l'utilisateur trouvé par id",
     *
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Retourne user_not_found",
     *
     * )
     *
     *
     * @SWG\Tag(name="User")
     */
    public function apiGetUser($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if(!$user) {
            return new JsonResponse(array(
                'status' => 'user_not_found'
            ), 404);
        }

        $arrayCollection[] = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'enabled' => $user->isEnabled(),
            'roles' => $user->getRoles(),
        );

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/createUser", name="apiCreateUser", methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Retourne l'utilisateur nouvellement créé",
     *
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="Retourne username_taken ou email_taken",
     *
     * )
     *
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string",
     *     description="Email de l'utilisateur",
     *     required=true
     * )
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     type="string",
     *     description="Username de l'utilisateur",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     type="string",
     *     description="Mot de passe de l'utilisateur",
     *     required=true
     * )
     *
     * @SWG\Tag(name="User")
     *
     */
    public function apiCreateUser(UserPasswordEncoderInterface $encoder, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();





        $user = new User();

        $password = $encoder->encodePassword($user, $request->get('password'));
        $user->setPassword($password);


        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));
        $user->setEnabled(true);
        $user->addRole('ROLE_USER');

        $arrayCollection[] = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'enabled' => $user->isEnabled(),
            'roles' => $user->getRoles(),
        );

        $entityManager->persist($user);

        $entityManager->flush();

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/manageUser/{id}", name="apiManageUser", methods={"POST"})
     */
    public function apiManageUser($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        /** @var  User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $user->setUsername('modif');
        $user->setEmail('modif@mail.com');
        $user->setEnabled(true);
        $user->setPassword('caca');
        $user->addRole('ROLE_USER');

        $arrayCollection[] = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'enabled' => $user->isEnabled(),
            'roles' => $user->getRoles(),
        );

        $entityManager->persist($user);

        $entityManager->flush();

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/deleteUser/{id}", name="apiDeleteUser", methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Retourne deletion succes",
     *
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Retourne user_not_found",
     *
     * )
     *
     * @SWG\Tag(name="User")
     */
    public function apiDeleteUser($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if(!$user)
        {
            return new JsonResponse(array(
                'status' => 'user_not_found'
            ));
        }

        $entityManager->remove($user);

        $entityManager->flush();

        return new JsonResponse(array("deletion" => "success"));
    }
}
