<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\CourseRepository;
use App\Repository\LearningObjectiveRepository;
use App\Repository\ModuleRepository;
use App\Repository\SemesterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/module")
 */
class ModuleController extends AbstractController
{
    /**
     * @Route("/", name="module_index", methods={"GET"})
     */
    public function index(ModuleRepository $moduleRepository): Response
    {

        $modules = $moduleRepository->findBy([], ['semester' => 'ASC']);
        return $this->render('module/index.html.twig', [
            'modules' => $modules,
        ]);
    }

    /**
     * @Route("/list", name="module_index_full", methods={"GET"})
     */
    public function list(ModuleRepository $moduleRepository, SemesterRepository $semesterRepository): Response
    {

        $modules = $moduleRepository->findBy([], ['semester' => 'ASC']);
        $semesters = $semesterRepository->findAll();
        return $this->render('module/list_full.html.twig', [
            'modules' => $modules,
            'semesters' => $semesters,
        ]);
    }

    /**
     * @Route("/new", name="module_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $module = new Module();
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($module);
            $entityManager->flush();

            return $this->redirectToRoute('module_index');
        }

        return $this->render('module/new.html.twig', [
            'module' => $module,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="module_show", methods={"GET"})
     */
    public function show(Module $module, CourseRepository $courseRepository): Response
    {

        $lectureHours = $courseRepository->createQueryBuilder('c')
            ->andWhere('c.module = :module')
            ->setParameter('module', $module)
            ->select('SUM(c.lectureHours) as lh')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('module/show.html.twig', [
            'module' => $module,
            'lectureHours' => $lectureHours,
        ]);
    }

    /**
     * @Route("/full/{id}", name="module_show_full", methods={"GET"})
     */
    public function show_full(Module $module, CourseRepository $courseRepository, LearningObjectiveRepository $learningObjectiveRepository): Response
    {

        $lectureHours = $courseRepository->createQueryBuilder('c')
            ->andWhere('c.module = :module')
            ->setParameter('module', $module)
            ->select('SUM(c.lectureHours) as lh')
            ->getQuery()
            ->getSingleScalarResult();

        $learningObjectives = $learningObjectiveRepository->findBy(
            [], 
            ['displayOrder' => 'ASC']
        );

        return $this->render('module/show_full.html.twig', [
            'module' => $module,
            'lectureHours' => $lectureHours,
            'learningObjectives' => $learningObjectives,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="module_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Module $module): Response
    {
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('module_index');
        }

        return $this->render('module/edit.html.twig', [
            'module' => $module,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="module_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Module $module): Response
    {
        if ($this->isCsrfTokenValid('delete'.$module->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($module);
            $entityManager->flush();
        }

        return $this->redirectToRoute('module_index');
    }
}
