<?php

namespace App\Controller;

use App\Services\DashboardStats;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     * 
     * @return Response
     */
    public function adminDashboard(DashboardStats $statsService): Response
    {
        $stats          = $statsService->getStats();
        $bestRentals    = $statsService->getRentalsStats('DESC');
        $worstRentals   = $statsService->getRentalsStats('ASC');

        return $this->render('admin/dashboard/admin_dashboard.html.twig', [
            'stats'         => $stats,
            'bestRentals'   => $bestRentals,
            'worstRentals'  => $worstRentals
        ]);
    }
}
