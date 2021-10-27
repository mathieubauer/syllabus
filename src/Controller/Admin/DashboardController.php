<?php

namespace App\Controller\Admin;

use App\Entity\Assessment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Module;
use App\Entity\Course;
use App\Entity\Semester;
use App\Entity\User;
use App\Entity\Session;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        return $this->redirect($routeBuilder->setController(CourseCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Syllabus')
            ->setFaviconPath('/img/favicon.svg')
            ->renderContentMaximized()

            ;
    }

    public function configureMenuItems(): iterable
    {

        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            // MenuItem::section('Blog'),
            MenuItem::section('Entities'),
            MenuItem::linkToCrud('Users', 'fa fa-users', User::class),
            MenuItem::linkToCrud('Semester', 'fa fa-calendar', Semester::class),
            MenuItem::linkToCrud('Modules', 'fa fa-copy', Module::class),
            MenuItem::linkToCrud('Courses', 'fa fa-file', Course::class),
            MenuItem::linkToCrud('Sessions', 'fa fa-file-alt', Session::class),
            MenuItem::linkToCrud('Assessments', 'fa fa-file-alt', Assessment::class),

            MenuItem::section('Links'),
            MenuItem::linkToRoute('Home', 'fa fa-home', 'home'),
            MenuItem::linkToRoute('Export CSV', 'fa fa-file-csv', 'export'),

            // MenuItem::section('Users'),
            // MenuItem::linkToCrud('Comments', 'fa fa-comment', Comment::class),
            // MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
        ];

        // yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);

    }
}
