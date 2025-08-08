<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* base.html */
class __TwigTemplate_0f3f3c4b5815578f94414588ecf4d2b4 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'extra_css' => [$this, 'block_extra_css'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<!DOCTYPE html>
<html lang=\"en-GB\">
<head>
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>";
        // line 6
        yield from $this->unwrap()->yieldBlock('title', $context, $blocks);
        yield "</title>
    

    <!-- CSS files -->
    <link rel=\"stylesheet\" href=\"/fresit/public/styles/styles.css\">
    <link rel=\"stylesheet\" href=\"/fresit/public/styles/media.css\">
    ";
        // line 12
        yield from $this->unwrap()->yieldBlock('extra_css', $context, $blocks);
        // line 13
        yield "
    <!-- Google Font -->
    <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">
    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
    <link rel=\"preload\" href=\"https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&display=swap\" as=\"style\">
    <link href=\"https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&display=swap\" rel=\"stylesheet\">
</head>

<body>
    <!-- Skip navigation for accessibility -->
    <a href=\"#main-content\" class=\"skip-link\">Skip to main content</a>
    
    <!-- Accessibility controls -->
    <div class=\"accessibility-controls\">
        <button id=\"high-contrast-toggle\" class=\"accessibility-btn\" aria-label=\"Toggle high contrast mode\">
            <span class=\"btn-text\">High Contrast</span>
        </button>
        <button id=\"font-size-toggle\" class=\"accessibility-btn\" aria-label=\"Increase font size\">
            <span class=\"btn-text\">A+</span>
        </button>
    </div>
    
    <!-- Header -->
    <header class=\"header\">
        <nav class=\"button-section\" role=\"navigation\">
            <a class=\"link\" href=\"/fresit/\" tabindex=\"0\" aria-label=\"Go to Home Page\">
                HOME
            </a>
            <a class=\"link\" href=\"/fresit/#story-page\" tabindex=\"0\" aria-label=\"Go to Our Story\">
                OUR STORY
            </a>
            <a class=\"link\" href=\"/fresit/#opening-page\" tabindex=\"0\" aria-label=\"Go to Opening Times\">
                OPENING TIMES
            </a>
            <a class=\"link\" href=\"/fresit/#contact-us-page\" tabindex=\"0\" aria-label=\"Go to Contact Page\">
                CONTACT US
            </a>
            <a class=\"link\" href=\"/fresit/reviews.php\" tabindex=\"0\" aria-label=\"Go to Reviews Page\">
                REVIEWS
            </a>
            <a class=\"link\" href=\"/fresit/booking.php\" tabindex=\"0\" aria-label=\"Book Classes\">
                BOOK CLASSES
            </a>
            <a class=\"link\" href=\"/fresit/staff-login.php\" tabindex=\"0\" aria-label=\"Staff Login\">
                STAFF LOGIN
            </a>
        </nav>
        <img class=\"header-logo\" src=\"/fresit/public/assets/Images%20(resit)/logos/logo_black.png\" alt=\"Royal Drawing School's logo\">
    </header>

    <!-- Main content -->
    <main class=\"content\" id=\"main-content\">
        ";
        // line 65
        yield from $this->unwrap()->yieldBlock('content', $context, $blocks);
        // line 66
        yield "    </main>

    <!-- Footer -->
    <footer class=\"footer\">
        <div class=\"social-media-icons\" role=\"list\">
            <a href=\"https://www.instagram.com/royaldrawingschool\" target=\"_blank\" rel=\"noopener\" aria-label=\"Go to Instagram page\" role=\"listitem\">
                <img class=\"social-icon instagram-icon\" src=\"/fresit/public/assets/icons/instagram-icon.png\" alt=\"Instagram icon\">
            </a>
            <a href=\"https://www.youtube.com/@RoyalDrawingSchool\" target=\"_blank\" rel=\"noopener\" aria-label=\"Go to Youtube Page\" role=\"listitem\">
                <img class=\"social-icon youtube-icon\" src=\"/fresit/public/assets/icons/youtube-icon.png\" alt=\"youtube icon\">
            </a>
            <a href=\"https://www.facebook.com/royaldrawingschool\" target=\"_blank\" rel=\"noopener\" aria-label=\"Go to Facebook Page\" role=\"listitem\">
                <img class=\"social-icon facebook-icon\" src=\"/fresit/public/assets/icons/facebook-icon.png\" alt=\"facebook icon\">
            </a>
            <a href=\"https://www.linkedin.com/company/royaldrawingschool\" target=\"_blank\" rel=\"noopener\" aria-label=\"Go to LinkedIn Page\" role=\"listitem\">
                <img class=\"social-icon linkedin-icon\" src=\"/fresit/public/assets/icons/linkedin-icon.png\" alt=\"linkedin icon\">
            </a>
        </div>
    </footer>

    <script>
        // Accessibility controls
        document.addEventListener('DOMContentLoaded', function() {
            const highContrastToggle = document.getElementById('high-contrast-toggle');
            const fontSizeToggle = document.getElementById('font-size-toggle');
            
            // High contrast toggle
            highContrastToggle.addEventListener('click', function() {
                document.body.classList.toggle('high-contrast');
                const isHighContrast = document.body.classList.contains('high-contrast');
                localStorage.setItem('highContrast', isHighContrast);
                
                // Update button text
                const btnText = this.querySelector('.btn-text');
                btnText.textContent = isHighContrast ? 'Normal Contrast' : 'High Contrast';
            });
            
            // Font size toggle
            fontSizeToggle.addEventListener('click', function() {
                document.body.classList.toggle('large-text');
                const isLargeText = document.body.classList.contains('large-text');
                localStorage.setItem('largeText', isLargeText);
                
                // Update button text
                const btnText = this.querySelector('.btn-text');
                btnText.textContent = isLargeText ? 'A-' : 'A+';
            });
            
            // Load saved preferences
            if (localStorage.getItem('highContrast') === 'true') {
                document.body.classList.add('high-contrast');
                highContrastToggle.querySelector('.btn-text').textContent = 'Normal Contrast';
            }
            
            if (localStorage.getItem('largeText') === 'true') {
                document.body.classList.add('large-text');
                fontSizeToggle.querySelector('.btn-text').textContent = 'A-';
            }
        });
    </script>
</body>
</html> ";
        yield from [];
    }

    // line 6
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((array_key_exists("page_title", $context)) ? (Twig\Extension\CoreExtension::default(($context["page_title"] ?? null), "Royal Drawing School")) : ("Royal Drawing School")), "html", null, true);
        yield from [];
    }

    // line 12
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_extra_css(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 65
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "base.html";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  206 => 65,  196 => 12,  185 => 6,  119 => 66,  117 => 65,  63 => 13,  61 => 12,  52 => 6,  45 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<!DOCTYPE html>
<html lang=\"en-GB\">
<head>
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>{% block title %}{{ page_title|default('Royal Drawing School') }}{% endblock %}</title>
    

    <!-- CSS files -->
    <link rel=\"stylesheet\" href=\"/fresit/public/styles/styles.css\">
    <link rel=\"stylesheet\" href=\"/fresit/public/styles/media.css\">
    {% block extra_css %}{% endblock %}

    <!-- Google Font -->
    <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">
    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
    <link rel=\"preload\" href=\"https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&display=swap\" as=\"style\">
    <link href=\"https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&display=swap\" rel=\"stylesheet\">
</head>

<body>
    <!-- Skip navigation for accessibility -->
    <a href=\"#main-content\" class=\"skip-link\">Skip to main content</a>
    
    <!-- Accessibility controls -->
    <div class=\"accessibility-controls\">
        <button id=\"high-contrast-toggle\" class=\"accessibility-btn\" aria-label=\"Toggle high contrast mode\">
            <span class=\"btn-text\">High Contrast</span>
        </button>
        <button id=\"font-size-toggle\" class=\"accessibility-btn\" aria-label=\"Increase font size\">
            <span class=\"btn-text\">A+</span>
        </button>
    </div>
    
    <!-- Header -->
    <header class=\"header\">
        <nav class=\"button-section\" role=\"navigation\">
            <a class=\"link\" href=\"/fresit/\" tabindex=\"0\" aria-label=\"Go to Home Page\">
                HOME
            </a>
            <a class=\"link\" href=\"/fresit/#story-page\" tabindex=\"0\" aria-label=\"Go to Our Story\">
                OUR STORY
            </a>
            <a class=\"link\" href=\"/fresit/#opening-page\" tabindex=\"0\" aria-label=\"Go to Opening Times\">
                OPENING TIMES
            </a>
            <a class=\"link\" href=\"/fresit/#contact-us-page\" tabindex=\"0\" aria-label=\"Go to Contact Page\">
                CONTACT US
            </a>
            <a class=\"link\" href=\"/fresit/reviews.php\" tabindex=\"0\" aria-label=\"Go to Reviews Page\">
                REVIEWS
            </a>
            <a class=\"link\" href=\"/fresit/booking.php\" tabindex=\"0\" aria-label=\"Book Classes\">
                BOOK CLASSES
            </a>
            <a class=\"link\" href=\"/fresit/staff-login.php\" tabindex=\"0\" aria-label=\"Staff Login\">
                STAFF LOGIN
            </a>
        </nav>
        <img class=\"header-logo\" src=\"/fresit/public/assets/Images%20(resit)/logos/logo_black.png\" alt=\"Royal Drawing School's logo\">
    </header>

    <!-- Main content -->
    <main class=\"content\" id=\"main-content\">
        {% block content %}{% endblock %}
    </main>

    <!-- Footer -->
    <footer class=\"footer\">
        <div class=\"social-media-icons\" role=\"list\">
            <a href=\"https://www.instagram.com/royaldrawingschool\" target=\"_blank\" rel=\"noopener\" aria-label=\"Go to Instagram page\" role=\"listitem\">
                <img class=\"social-icon instagram-icon\" src=\"/fresit/public/assets/icons/instagram-icon.png\" alt=\"Instagram icon\">
            </a>
            <a href=\"https://www.youtube.com/@RoyalDrawingSchool\" target=\"_blank\" rel=\"noopener\" aria-label=\"Go to Youtube Page\" role=\"listitem\">
                <img class=\"social-icon youtube-icon\" src=\"/fresit/public/assets/icons/youtube-icon.png\" alt=\"youtube icon\">
            </a>
            <a href=\"https://www.facebook.com/royaldrawingschool\" target=\"_blank\" rel=\"noopener\" aria-label=\"Go to Facebook Page\" role=\"listitem\">
                <img class=\"social-icon facebook-icon\" src=\"/fresit/public/assets/icons/facebook-icon.png\" alt=\"facebook icon\">
            </a>
            <a href=\"https://www.linkedin.com/company/royaldrawingschool\" target=\"_blank\" rel=\"noopener\" aria-label=\"Go to LinkedIn Page\" role=\"listitem\">
                <img class=\"social-icon linkedin-icon\" src=\"/fresit/public/assets/icons/linkedin-icon.png\" alt=\"linkedin icon\">
            </a>
        </div>
    </footer>

    <script>
        // Accessibility controls
        document.addEventListener('DOMContentLoaded', function() {
            const highContrastToggle = document.getElementById('high-contrast-toggle');
            const fontSizeToggle = document.getElementById('font-size-toggle');
            
            // High contrast toggle
            highContrastToggle.addEventListener('click', function() {
                document.body.classList.toggle('high-contrast');
                const isHighContrast = document.body.classList.contains('high-contrast');
                localStorage.setItem('highContrast', isHighContrast);
                
                // Update button text
                const btnText = this.querySelector('.btn-text');
                btnText.textContent = isHighContrast ? 'Normal Contrast' : 'High Contrast';
            });
            
            // Font size toggle
            fontSizeToggle.addEventListener('click', function() {
                document.body.classList.toggle('large-text');
                const isLargeText = document.body.classList.contains('large-text');
                localStorage.setItem('largeText', isLargeText);
                
                // Update button text
                const btnText = this.querySelector('.btn-text');
                btnText.textContent = isLargeText ? 'A-' : 'A+';
            });
            
            // Load saved preferences
            if (localStorage.getItem('highContrast') === 'true') {
                document.body.classList.add('high-contrast');
                highContrastToggle.querySelector('.btn-text').textContent = 'Normal Contrast';
            }
            
            if (localStorage.getItem('largeText') === 'true') {
                document.body.classList.add('large-text');
                fontSizeToggle.querySelector('.btn-text').textContent = 'A-';
            }
        });
    </script>
</body>
</html> ", "base.html", "C:\\Users\\Admin\\OneDrive\\Documents\\fresit\\app\\views\\base.html");
    }
}
