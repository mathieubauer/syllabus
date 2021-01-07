<?php

namespace App\Controller;

use App\Entity\Assessment;
use App\Entity\Course;
use App\Entity\Session;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/course")
 */
class CourseController extends AbstractController
{
    /**
     * @Route("/", name="course_index", methods={"GET"})
     */
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('course/index.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="course_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();

            for ($i = 0; $i < 5; $i++) {
                $session = new Session();
                $session->setCourse($course);
                $session->setDuration(180);
                $entityManager->persist($session);
                $entityManager->flush();
            }

            for ($i = 0; $i < 2; $i++) {
                $assessment = new Assessment();
                $assessment->setCourse($course);
                $assessment->setDuration(180);
                $entityManager->persist($assessment);
                $entityManager->flush();
            }

            return $this->redirectToRoute('course_index');
        }

        return $this->render('course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="course_show", methods={"GET"})
     */
    public function show(Course $course): Response
    {
        return $this->render('course/show.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="course_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Course $course): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            if ($form->getClickedButton()->getName() === 'save_and_add_session') {
                $this->add_session($course);
                return $this->redirectToRoute('course_edit', ['id' => $course->getId()]);
            }

            if ($form->getClickedButton()->getName() === 'save_and_add_assessment') {
                $this->add_assessment($course);
                return $this->redirectToRoute('course_edit', ['id' => $course->getId()]);
            }

            return $this->redirectToRoute('course_show', ['id' => $course->getId()]);
        }

        return $this->render('course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="course_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Course $course): Response
    {
        if ($this->isCsrfTokenValid('delete' . $course->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($course);
            $entityManager->flush();
        }

        return $this->redirectToRoute('course_index');
    }


    private function add_session(Course $course)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $session = new Session();
        $session->setCourse($course);
        $session->setDuration(180);
        $entityManager->persist($session);
        $entityManager->flush();
    }

    private function add_assessment(Course $course)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $assessment = new Assessment();
        $assessment->setCourse($course);
        $assessment->setDuration(180);
        $assessment->setMethod('Méthode ?');
        $assessment->setStage('Mid term or Final ?');
        $assessment->setIsSupervised(0);
        $entityManager->persist($assessment);
        $entityManager->flush();
    }
}
