<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Inscription d'utilisateurs avec mot de passe
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, MailerInterface $mailer): Response
    {

        if ($request->isMethod('POST')) {

            $em = $this->getDoctrine()->getManager();
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $passwordConfirm = $request->request->get('passwordConfirm');
            $user = $em->getRepository(User::class)->findOneBy([
                'email' => $email,
            ]);

            if ($user) {
                $this->addFlash('warning', 'Cet utilisateur est déjà enregistré');
                return $this->redirectToRoute($request->get('_route'));
            }

            if ($password != $passwordConfirm) {
                $this->addFlash('warning', 'Les mots de passe ne correspondent pas');
                return $this->redirectToRoute($request->get('_route'));
            }

            $user = new User();
            $user->setEmail($email);
            $user->setPassword($passwordEncoder->encodePassword($user, $password));
            $em->persist($user);
            $em->flush();

            $email = (new TemplatedEmail())
                ->from(new Address('noreply@dsides.net', "L'équipe dsides"))
                ->to('ampli@dsides.net')
                ->subject('Syllabus - Nouvel utilisateur')
                // ->text($user->getEmail() . " a créé un compte.")
                // ->html("<h1>Nice to meet you {$user->getEmail()}! ❤️</h1>")
                ->htmlTemplate('email/welcome.html.twig')
                ->context([
                    'user' => $user,
                ])
                ;
            $mailer->send($email);

            // $action = 'Register with password';
            // $addTrack = $this->addTrack($action, $user);

            // redirige vers la home 
            $this->addFlash('info', 'Inscription validée. Vous êtes connecté.');
            return $guardHandler->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'main');
        }

        return $this->render('security/register.html.twig');
    }

    /**
     * Modification de mot de passe par l'utilisateur
     * @Route("/password_change", name="app_password_change")
     * @IsGranted("ROLE_USER")
     */
    public function password_change(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {

        if ($request->isMethod('POST')) {

            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $passwordOld = $request->request->get('passwordOld');
            $passwordNew = $request->request->get('passwordNew');
            $passwordNewConfirm = $request->request->get('passwordNewConfirm');

            if (!$passwordEncoder->isPasswordValid($user, $passwordOld)) {
                $this->addFlash('warning', 'Mot de passe erroné');
                return $this->redirectToRoute($request->get('_route')); // current route
            }

            if ($passwordNew != $passwordNewConfirm) {
                $this->addFlash('warning', 'Les mots de passe ne correspondent pas');
                return $this->redirectToRoute($request->get('_route'));
            }

            $user->setPassword($passwordEncoder->encodePassword($user, $passwordNew));
            $em->persist($user);
            $em->flush();
            // return $guardHandler->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'main');
            $this->addFlash('info', 'Mot de passe modifié');
            return $this->redirectToRoute('profil');
        }

        return $this->render('security/password_change.html.twig');
    }

    /**
     * Demande d'envoi de mail pour remise à zéro du mot de passe
     * @Route("/password_request", name="app_password_request")
     */
    public function password_request(Request $request, UserPasswordEncoderInterface $encoder, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {

        if ($request->isMethod('POST')) {

            $em = $this->getDoctrine()->getManager();
            $email = $request->request->get('email');
            $user = $em->getRepository(User::class)->findOneByEmail($email);

            if ($user === null) {
                $this->addFlash('warning', 'Email inconnu');
                return $this->redirectToRoute($request->get('_route'));
            }

            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $em->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('home');
            }

            $url = $this->generateUrl('app_password_reset', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            /* $message = (new \Swift_Message('Learn | Demande de modification du mot de passe'))
                ->setFrom('ampli@dsides.net')
                ->setTo($user->getEmail())
                ->setBcc('ampli@dsides.net')
                // ->setBody($this->renderView('email/moderation.html.twig', ['idea' => $idea]), 'text/html')
                ->setBody(
                    "Rendez-vous à <a href ='" . $url . "'>cette adresse</a> pour modifier votre mot de passe" . $url,
                    'text/html'
                );
            $mailer->send($message); */

            $email = (new TemplatedEmail())
                ->from('ampli@dsides.net')
                ->to($user->getEmail())
                ->bcc('ampli@dsides.net')
                ->replyTo('ampli@dsides.net')
                ->subject('Learn | Demande de modification du mot de passe')
                // ->text('Sending emails is fun again!')
                // ->html('<p>See Twig integration for better HTML integration!</p>')
                // path of the Twig template to render
                ->htmlTemplate('emails/password_request.html.twig')
                // pass variables (name => value) to the template
                ->context([
                    'url' => $url,
                ]);

            $mailer->send($email);


            // forcer la déconnexion ici, au cas où ?

            // Attention, pas de prise en charge des alertes sur la home de Learn
            // $this->addFlash('info', "Email envoyé. Veuillez vérifier votre boîte de réception.");
            // return $this->redirectToRoute('home');
            return $this->render('security/password_requested.html.twig');
        }

        return $this->render('security/password_request.html.twig');
    }

    /**
     * Remise à zéro du mot de passe
     * @Route("/password_reset/{token}", name="app_password_reset")
     */
    public function password_reset(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

        if ($request->isMethod('POST')) {

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneByResetToken($token);
            $password = $request->request->get('password');
            $passwordConfirm = $request->request->get('passwordConfirm');

            if ($user === null) {
                $this->addFlash('warning', "La modification du mot de passe a échoué : Token inconnu");
                return $this->redirectToRoute('home');
            }

            if ($password != $passwordConfirm) {
                $this->addFlash('warning', 'Les mots de passe ne correspondent pas');
                return $this->redirectToRoute($request->get('_route'), ['token' => $token]);
            }

            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $password));
            $em->flush();

            $this->addFlash('info', "Le mot de passe a été modifié, vous pouvez vous connecter");
            return $this->redirectToRoute('app_login_simple');
        }

        return $this->render('security/password_reset.html.twig', ['token' => $token]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
