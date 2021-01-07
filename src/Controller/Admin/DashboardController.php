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
use App\Entity\User;
use App\Entity\Session;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Syllabus');
    }

    public function configureMenuItems(): iterable
    {

        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            // MenuItem::section('Blog'),
            MenuItem::linkToCrud('Users', 'fa fa-tags', User::class),
            MenuItem::linkToCrud('Modules', 'fa fa-tags', Module::class),
            MenuItem::linkToCrud('Courses', 'fa fa-file-text', Course::class),
            MenuItem::linkToCrud('Sessions', 'fa fa-file-text', Session::class),
            MenuItem::linkToCrud('Assessments', 'fa fa-file-text', Assessment::class),

            // MenuItem::section('Users'),
            // MenuItem::linkToCrud('Comments', 'fa fa-comment', Comment::class),
            // MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
        ];

        // yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);

    }
}
