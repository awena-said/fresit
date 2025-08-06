<?php

namespace App\Controllers;

use App\Models\Application;

class HomeController extends BaseController
{
    private $application = null;

    public function __construct()
    {
        parent::__construct();
    }
    
    private function getApplication()
    {
        if ($this->application === null) {
            $this->application = new Application();
        }
        return $this->application;
    }

    /**
     * Display home page
     */
    public function index()
    {
        $this->render('index.html', [
            'title' => 'Welcome to Fresit Art School'
        ]);
    }

    /**
     * Display reviews page
     */
    public function reviews()
    {
        $reviews = [
            [
                'name' => 'Eleanor Davis',
                'rating' => 5,
                'comment' => 'I really enjoyed my recent course The 10 week Colour and Composition Course run by the Royal Drawing School. It far passed my expectations of what I would learn . The course was really well structured yet there was no pressure. Presentations of Artists\' work were varied and stimulating leaving the student with lots of ideas. Practical help was always on hand and over the 10 weeks it became an open and friendly working experience - yet the outcomes and achievements were exceptionally high. I would definitely recommend this course to anyone who practices',
                'date' => '3 months ago'
            ],
            [
                'name' => 'Bella Foster',
                'rating' => 5,
                'comment' => 'This was the best drawing and writing course I\'ve ever taken. I came in as a total beginner, and the course exceeded my expectations. Emily Haworth Booth is an incredible teacher, and I\'m so grateful I took the plunge signing up',
                'date' => '3 months ago'
            ],
            [
                'name' => 'Deb Daines',
                'rating' => 5,
                'comment' => 'Online monotype life drawing lesson was informative, & fun! (I\'m deaf, & have M.E.) Despite a last minute change of tutor, she was vocally understandable, and gave a well-presented, well-paced class. This schools online learning programme appears to be well executed, and I look forward to joining future classes.',
                'date' => '2 months ago'
            ],
            [
                'name' => 'Reem Soliman',
                'rating' => 5,
                'comment' => 'I did the Drawing and Theory online course with Andy Pankhurst. I loved it so much! To have a 10 week routine with such a knowledgeable and kind tutor like Andy is just so nourishing to anyone wanting to reconnect again in an organic way with art. A wide variety of areas were covered and you slowly start to gain confidence with the continuous drawing practice and the brilliant guidance from Andy along the way.',
                'date' => 'A year ago'
            ],
            [
                'name' => 'Peter Voss-Knude',
                'rating' => 4,
                'comment' => 'A very useful resource for developing the art of drawing as a means for discovery and not just mimicry. Very attentive tutoring, meaningful exercises and well thought out structure to make the best of the online format. Also a pleasure to work with a group of artists based around the globe. Thanks!',
                'date' => 'A year ago'
            ],
            [
                'name' => 'Kazu Oka',
                'rating' => 4,
                'comment' => 'I\'ve really enjoyed the easter painting courses at RDS. The location is good, the tutor was excellent and the people were friendly and inspiring. However I cannot agree with RDS\'s policy to limit to just one solvent brand we all must use. I do understand the reason why they want us to use non-toxic solvent but nowadays there are more than one such solvents exist. And their choice is the least commonly available one. I must say £7.5 for 200ml of this particular solvent was good buy, however, the problem was 200ml wasn\'t enough for most of us. We couldn\'t purchase more than 200ml. At least we should have freedom to purchase additional amount if RDS want to stick with this brand. I hope RDS will reconsider this policy by Summer, then we will be able to clean our brushes more frequently.',
                'date' => 'A year ago'
            ]
        ];

        $this->render('reviews.html', [
            'title' => 'Student Reviews',
            'reviews' => $reviews
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