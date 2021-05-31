<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class DashboardStats
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getStats()
    {
        $users      = $this->getUsersCount();
        $rentals    = $this->getRentalsCount();
        $bookings   = $this->getBookingsCount();
        $comments   = $this->getCommentsCount();

        return compact('users', 'rentals', 'bookings', 'comments');
    }

    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getRentalsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(r) FROM App\Entity\Rental r')->getSingleScalarResult();
    }

    public function getBookingsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }

    public function getCommentsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    public function getRentalsStats($direction)
    {
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, r.title, r.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c 
            JOIN c.rental r
            JOIN r.author u
            GROUP BY r
            ORDER BY note ' . $direction
        )
            ->setMaxResults(5)
            ->getResult();
    }
}
