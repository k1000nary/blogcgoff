<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\UserType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Entity\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        //
        $form = $this->get('form.factory')
            ->createNamedBuilder(null)
            ->add('_username', null, ['label' => 'Email'])
            ->add('_password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('ok', SubmitType::class, ['label' => 'Se connecter'])
            ->getForm();
        return $this->render('user/index.html.twig', [
            'mainNavLogin' => true,
            'title' => 'Connexion',
            'form' => $form->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/register", name="registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            //on active par défaut
            $user->setIsActive(true);
            //$user->addRole("ROLE_ADMIN");
            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $this->addFlash('success', 'Votre compte à bien été enregistré.');
            return $this->redirectToRoute('login');
        }
        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
            'mainNavRegistration' => true,
            'title' => 'Inscription'
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */

    public function logout()
    {

    }

    /**
     * @Route("/user/post", name="user_post")
     */
    public function showAll(ArticleRepository $article)
    {
        $articles = $article->findBy(
            ['publication' => true],
            ['date' => 'DESC']
        );
        return $this->render('user/showAll.html.twig', [
                'articles'=>$articles,
        ]);
    }

    /**
     * @Route("/user/post/show/{id}", name="user_post_show")
     */
    public function show(Article $article)
    {

        return $this->render('user/show.html.twig', [
                'article'=>$article,
        ]);
    }

    /**
     * @Route("/user/post/delete/{id}", name="user_post_delete")
     */
    public function delete(Article $article, ObjectManager $manager)
    {
        $article->setPublication(false);
        $manager->persist($article);
        $manager->flush();
        return $this->redirectToRoute('user_post');
    }

    /**
     * @Route("/user/post/new", name="user_post_new")
     * @Route("/user/post/edit/{id}", name="user_post_edit")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager, CategorieRepository $cat)
    {
        if(!$article){
            $article = new Article();
        }
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(!$article->getId()) {
                $article->setDate(new \DateTime());
            }else{
                $article->setDateEdition(new \DateTime());
            }
            $file = $article->getImage()->getUrl();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $fileName);
            $article->getImage()->setUrl($fileName);
            foreach($form->get('categories')->getData() as $a)
            {
                if(!$article->getCategories()->contains($a))
                {
                    // On ajoute cet acteur
                    $article->addCategory($a);
                }
            }
            foreach($article->getCategories() as $a)
            {
                if(!$form->get('categories')->getData()->contains($a))
                {
                    // On ajoute cet acteur
                    $article->removeCategory($a);
                }
            }
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('user_post_show', [
                'id'=>$article->getId(),
            ]);

        }
        return $this->render('user/form.html.twig', [
            'form' => $form->createView(),
            'editMode' => $article->getId()!== null,
        ]);
    }
}
