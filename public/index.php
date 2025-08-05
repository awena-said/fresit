<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/twig_init.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Initialize Twig
$loader = new FilesystemLoader(__DIR__ . '/../app/views');
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/../cache/twig',
    'debug' => true,
    'auto_reload' => true,
]);

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove trailing slash
$path = rtrim($path, '/');

// Route to appropriate template
switch ($path) {
    case '':
    case '/':
        echo $twig->render('index.html');
        break;
        
    case '/reviews':
        // Sample reviews data
        $reviews = [
            [
                'name' => 'Eleanor Davis',
                'rating' => 5,
                'date' => '3 months ago',
                'text' => 'I really enjoyed my recent course The 10 week Colour and Composition Course run by the Royal Drawing School. It far passed my expectations of what I would learn. The course was really well structured yet there was no pressure. Presentations of Artists\' work were varied and stimulating leaving the student with lots of ideas. Practical help was always on hand and over the 10 weeks it became an open and friendly working experience - yet the outcomes and achievements were exceptionally high. I would definitely recommend this course to anyone who practices.'
            ],
            [
                'name' => 'Bella Foster',
                'rating' => 5,
                'date' => '3 months ago',
                'text' => 'This was the best drawing and writing course I\'ve ever taken. I came in as a total beginner, and the course exceeded my expectations. Emily Haworth Booth is an incredible teacher, and I\'m so grateful I took the plunge signing up.'
            ],
            [
                'name' => 'Deb Daines',
                'rating' => 5,
                'date' => '2 months ago',
                'text' => 'Online monotype life drawing lesson was informative, & fun! (I\'m deaf, & have M.E.) Despite a last minute change of tutor, she was vocally understandable, and gave a well-presented, well-paced class. This schools online learning programme appears to be well executed, and I look forward to joining future classes.'
            ],
            [
                'name' => 'Reem Soliman',
                'rating' => 5,
                'date' => 'A year ago',
                'text' => 'I did the Drawing and Theory online course with Andy Pankhurst. I loved it so much! To have a 10 week routine with such a knowledgeable and kind tutor like Andy is just so nourishing to anyone wanting to reconnect again in an organic way with art. A wide variety of areas were covered and you slowly start to gain confidence with the continuous drawing practice and the brilliant guidance from Andy along the way.'
            ],
            [
                'name' => 'Peter Voss-Knude',
                'rating' => 4,
                'date' => 'A year ago',
                'text' => 'A very useful resource for developing the art of drawing as a means for discovery and not just mimicry. Very attentive tutoring, meaningful exercises and well thought out structure to make the best of the online format. Also a pleasure to work with a group of artists based around the globe. Thanks!'
            ],
            [
                'name' => 'Kazu Oka',
                'rating' => 4,
                'date' => 'A year ago',
                'text' => 'I\'ve really enjoyed the easter painting courses at RDS. The location is good, the tutor was excellent and the people were friendly and inspiring. However I cannot agree with RDS\'s policy to limit to just one solvent brand we all must use. I do understand the reason why they want us to use non-toxic solvent but nowadays there are more than one such solvents exist. And their choice is the least commonly available one. I must say £7.5 for 200ml of this particular solvent was good buy, however, the problem was 200ml wasn\'t enough for most of us. We couldn\'t purchase more than 200ml. At least we should have freedom to purchase additional amount if RDS want to stick with this brand. I hope RDS will reconsider this policy by Summer, then we will be able to clean our brushes more frequently.'
            ]
        ];
        
        echo $twig->render('reviews.html', ['reviews' => $reviews]);
        break;
        
    case '/staff/login':
        echo $twig->render('login.html');
        break;
        
    case '/booking':
        echo $twig->render('booking.html');
        break;
        
    case '/booking-success':
        echo $twig->render('booking-success.html');
        break;
        
    case '/applications':
        echo $twig->render('applications.html');
        break;
        
    case '/staff/dashboard':
        echo $twig->render('staff-dashboard.html');
        break;
        
    default:
        http_response_code(404);
        echo $twig->render('404.html', ['path' => $path]);
        break;
}
?> 