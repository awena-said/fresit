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
        $this->render('index.html', [
            'title' => 'Welcome to Royal Drawing School'
        ]);
    }

    /**
     * Display reviews page
     */
    public function reviews()
    {
        $this->render('reviews.html', [
            'title' => 'Student Reviews'
        ]);
    }

    /**
     * Display booking page
     */
    public function booking()
    {
        $this->render('booking.html', [
            'title' => 'Book Classes'
        ]);
    }

    /**
     * Display booking success page
     */
    public function bookingSuccess()
    {
        $this->render('booking.html', [
            'title' => 'Booking Submitted Successfully',
            'success' => true
        ]);
    }

    /**
     * Display applications page (public view)
     */
    public function applications()
    {
        $this->render('index.html', [
            'title' => 'Recent Applications'
        ]);
    }

    /**
     * Display status page
     */
    public function status()
    {
        $this->render('index.html', [
            'title' => 'System Status'
        ]);
    }
} 