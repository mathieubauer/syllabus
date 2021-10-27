<?php

namespace App\Controller;

use App\Entity\Assessment;
use App\Form\AssessmentType;
use App\Repository\AssessmentRepository;
use App\Repository\SemesterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/assessment")
 */
class AssessmentController extends AbstractController
{
    /**
     * @Route("/", name="assessment_index", methods={"GET"})
     */
    public function index(AssessmentRepository $assessmentRepository, SemesterRepository $semesterRepository): Response
    {

        $assessments = $assessmentRepository->findBy([],
            [ 
                'stage' => 'DESC',
                'method' => 'ASC',
            ]
        );

        $semesters = $semesterRepository->findAll();

        return $this->render('assessment/index.html.twig', [
            'assessments' => $assessments,
            'semesters' => $semesters,
        ]);
    }

    /**
     * @Route("/new", name="assessment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $assessment = new Assessment();
        $form = $this->createForm(AssessmentType::class, $assessment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($assessment);
            $entityManager->flush();

            return $this->redirectToRoute('assessment_index');
        }

        return $this->render('assessment/new.html.twig', [
            'assessment' => $assessment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="assessment_show", methods={"GET"})
     */
    public function show(Assessment $assessment): Response
    {
        return $this->render('assessment/show.html.twig', [
            'assessment' => $assessment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="assessment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Assessment $assessment): Response
    {
        $form = $this->createForm(AssessmentType::class, $assessment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('assessment_index');
        }

        return $this->render('assessment/edit.html.twig', [
            'assessment' => $assessment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="assessment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Assessment $assessment): Response
    {
        if ($this->isCsrfTokenValid('delete' . $assessment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($assessment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('assessment_index');
    }

    /**
     * @Route("/{id}/delete", name="assessment_delete_simple", methods={"GET", "POST"})
     */
    public function delete_simple(Request $request, Assessment $assessment): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($assessment);
        $entityManager->flush();

        return $this->redirectToRoute('course_edit', ['id' => $assessment->getCourse()->getId()]);
    }
}
