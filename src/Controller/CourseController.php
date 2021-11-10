<?php

namespace App\Controller;

use App\Entity\Assessment;
use App\Entity\Course;
use App\Entity\Session;
use App\Entity\User;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use App\Repository\ModuleRepository;
use App\Repository\SemesterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @Route("/course")
 */
class CourseController extends AbstractController
{
    /**
     * @Route("/", name="course_index", methods={"GET"})
     */
    public function index(CourseRepository $courseRepository, ModuleRepository $moduleRepository, SemesterRepository $semesterRepository): Response
    {

        $modules = $moduleRepository->findBy([], ['name' => 'ASC']);
        $semesters = $semesterRepository->findAll();
        
        return $this->render('course/index.html.twig', [
            'modules' => $modules,
            'semesters' => $semesters,
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
        $form
        ->remove('learningOutcomes')
        ->remove('sessions')
        ->remove('assessments')
        ->remove('save_and_add_session')
        ->remove('save_and_add_assessment')
        ->remove('eLearning')
        ->remove('textbook')
        ->remove('bibliography')
        ->remove('linksWithCompanies')
        ->remove('teachingMethods')
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();

            for ($i = 0; $i < 5; $i++) {
                $this->add_session($course);
            }

            for ($i = 0; $i < 2; $i++) {
                $this->add_assessment($course);
            } 

            $this->addFlash('success', 'Course created');
            return $this->redirectToRoute('course_index');
        }

        return $this->render('course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="course_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Course $course): Response
    {
        return $this->render('course/show.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * @Route("/full/{id}", name="course_show_full", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show_full(Course $course): Response
    {
        return $this->render('course/show_full.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="course_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Course $course, MailerInterface $mailer): Response
    {

        $user = $this->getUser();
        $form = $this->createForm(CourseType::class, $course);
        $form
        ->remove('name')
        ->remove('lectureHours')
        ->remove('teachingProfessor')
        ->remove('module')
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            

            if ($form->get('save_and_add_session')->isClicked()) {
                $this->add_session($course);
                if ($user->getEmail() != 'mat.bauer@gmail.com') {
                    $this->send_email($mailer, $course, $user);
                }
                $this->addFlash('success', 'Session added');
                return $this->redirectToRoute('course_edit', ['id' => $course->getId()]);
            }

            if ($form->get('save_and_add_assessment')->isClicked()) {
                $this->add_assessment($course);
                if ($user->getEmail() != 'mat.bauer@gmail.com') {
                    $this->send_email($mailer, $course, $user);
                }
                $this->addFlash('success', 'Assessment added');
                return $this->redirectToRoute('course_edit', ['id' => $course->getId()]);
            }

            if ($user->getEmail() != 'mat.bauer@gmail.com') {
                $this->send_email($mailer, $course, $user);
            }
            $this->addFlash('success', 'Syllabus updated');
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

    private function send_email(MailerInterface $mailer, Course $course, User $user)
    {
        $email = (new Email())
            ->from('noreply@dsides.net')
            ->to('ampli@dsides.net')
            ->subject('Syllabus modifié')
            ->text($user->getEmail() . " a modifié le cours " . $course->getName());
        $mailer->send($email);
    }

    /**
     * @Route("/export", name="export")
     * @IsGranted("ROLE_ADMIN")
     */
    public function export(CourseRepository $courseRepository)
    {

        // export_csv

        $courses = $courseRepository->findBy(
            [],
            [
                'name' => 'ASC'
            ],
        );

        date_default_timezone_set('Europe/Paris');

        $fp = fopen("php://output", 'w');
        $filename = 'courses_' . date('YmdHis');
        $delimiter = ";";
        $enclosure = '"';

        $response = new StreamedResponse();

        $response->setCallback(function () use ($courses, $delimiter, $enclosure) {
            $handle = fopen('php://output', 'w+');
            fputs($handle, "\xEF\xBB\xBF"); // hack nécessaire à l'encodage
            fputcsv($handle, [
                'Semester',
                'Module',
                'Course',
                'Teaching Professors',
                'Lecture Hours',
                'Learning Outcomes',
                'Sessions Themes',
                'Assessments',
                'Teaching Methods',
                'Textbook',
                'Bibliography',
                'E-Learning',
                'Links with companies',
            ], $delimiter, $enclosure);
            foreach ($courses as $course) {
                $data[] = $course->getModule()->getSemester()->getName();
                $data[] = $course->getModule()->getName();
                $data[] = $course->getName();
                $professors = '';
                foreach ($course->getTeachingProfessor() as $professor) {
                    $professors .= $professor->getLastName() . " ";
                    // ne pas en mettre au dernier ; ou \n
                }
                $data[] = $professors;
                $data[] = $course->getLectureHours();
                $data[] = html_entity_decode(str_replace("&#39;", "'", $course->getLearningOutcomes())); // conversion des apostrophes
                $sessions = '';
                $i = 1;
                foreach ($course->getSessions() as $session) {
                    $sessions .= 'Session ' . $i . ' - ' . $session->getDuration() . "\n";
                    $sessions .= $session->getTheme() . "\n";
                    $sessions .= $session->getBibliographyReferences() . "\n";
                    $sessions .= $session->getAssignment() . "\n\n";
                    $i++;
                    // ne pas en mettre au dernier
                }
                $data[] = $sessions;
                $assessments = '';
                $i = 1;
                foreach ($course->getAssessments() as $assessment) {
                    $assessments .= 'Assessment ' . $i . "\n";
                    $assessments .= $assessment->getStage() . "\n";
                    $assessments .= $assessment->getMethod() . "\n";
                    $assessments .= $assessment->getDescription() . "\n\n";
                    $i++;
                    // ne pas en mettre au dernier
                }
                $data[] = $assessments;
                $data[] = $course->getTeachingMethods();
                $data[] = $course->getTextbook();
                $data[] = $course->getBibliography();
                $data[] = $course->getELearning();
                $data[] = $course->getLinksWithCompanies();

                $data = array_map('strip_tags', $data);             // strip_tags — Supprime les balises HTML et PHP d'une chaîne
                $data = array_map('html_entity_decode', $data);     // html_entity_decode — Convertit les entités HTML à leurs caractères correspondant
                $data = str_replace('&#39;', "'", $data);           // problème avec les apostrophes
                fputcsv($handle, $data, $delimiter, $enclosure);

                // fputcsv($handle, $data = (array_map('strip_tags', $data)), $delimiter, $enclosure);
                // fputcsv($handle, $data, $delimiter, $enclosure);
                $data = [];
            }
            fclose($handle);
        });
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');
        return $response;
    }
}
