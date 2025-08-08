<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display home page
     */
    public function index()
    {
        $data = [
            'title' => 'Welcome to Royal Drawing School',
            'user' => $this->getCurrentUser()
        ];
        $this->render('index.html', $data);
    }

    /**
     * Display reviews page
     */
    public function reviews()
    {
        $data = [
            'title' => 'Student Reviews',
            'user' => $this->getCurrentUser()
        ];
        $this->render('reviews.html', $data);
    }

    /**
     * Display booking page
     */
    public function booking()
    {
        $classTypes = [
            ['id' => 'Foundation', 'name' => 'Foundation'],
            ['id' => 'Imagination', 'name' => 'Imagination'],
            ['id' => 'Watercolour', 'name' => 'Watercolour']
        ];

        // Get available classes from database
        $artClass = new \App\Models\ArtClass();
        $availableClasses = $artClass->getUpcoming();

        $data = [
            'title' => 'Apply for Classes',
            'class_types' => $classTypes,
            'available_classes' => $availableClasses,
            'errors' => [],
            'form_data' => [],
            'user' => $this->getCurrentUser()
        ];
        $this->render('booking.html', $data);
    }

    /**
     * Display booking success page
     */
    public function bookingSuccess()
    {
        // Add $this->requireAuth() if confirmation requires login
        $applicationId = $_GET['id'] ?? null;
        
        $data = [
            'title' => 'Booking Submitted Successfully',
            'success' => true,
            'application_id' => $applicationId,
            'user' => $this->getCurrentUser()
        ];
        $this->render('booking-success.html', $data);
    }


}