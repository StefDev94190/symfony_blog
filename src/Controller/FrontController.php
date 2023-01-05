<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    #[Route('/inscription', name: 'app_subscribe')]
    public function subscribe(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $hasher){

        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $password = $user->getPassword();
            $passwordHashed = $hasher->hashPassword($user, $password);
            $user->setPassword($passwordHashed);
            $userRepository->save($user, TRUE);

            $this->addFlash("success", "votre inscription a bien été pris en compte");

            return $this->redirectToRoute('app_index');
        }


        return $this->render('front/subscribe.html.twig', [
            'formUser' => $form
        ]);
    }

    #[Route('/article', name:'app_article')]
    public function allArticles(ArticleRepository $articleRepository){
        return $this->render('front/article/index.html.twig', [
            'article' => $articleRepository->findAll()
        ]);
    }

    #[Route('article/{id}', name: 'app_article')]
    public function article(Article $artiste){
        return$this->render('front/article/article.html.twig', [
            'article' => $article
        ]);
    }

}
