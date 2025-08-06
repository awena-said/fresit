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

/* reviews.html */
class __TwigTemplate_30f0716b8f718a4fbd879c59f76329a7 extends Template
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

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "base.html";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("base.html", "reviews.html", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Reviews - Royal Drawing School";
        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 6
        yield "<section id=\"reviews-page\">
    <h1>STUDENT REVIEWS</h1>
    <div class=\"reviews-container\">
        ";
        // line 9
        $context["reviews"] = [["name" => "Eleanor Davis", "rating" => 5, "comment" => "I really enjoyed my recent course The 10 week Colour and Composition Course run by the Royal Drawing School. It far passed my expectations of what I would learn . The course was really well structured yet there was no pressure. Presentations of Artists' work were varied and stimulating leaving the student with lots of ideas. Practical help was always on hand and over the 10 weeks it became an open and friendly working experience - yet the outcomes and achievements were exceptionally high. I would definitely recommend this course to anyone who practices", "date" => "3 months ago"], ["name" => "Bella Foster", "rating" => 5, "comment" => "This was the best drawing and writing course I've ever taken. I came in as a total beginner, and the course exceeded my expectations. Emily Haworth Booth is an incredible teacher, and I'm so grateful I took the plunge signing up", "date" => "3 months ago"], ["name" => "Deb Daines", "rating" => 5, "comment" => "Online monotype life drawing lesson was informative, & fun! (I'm deaf, & have M.E.) Despite a last minute change of tutor, she was vocally understandable, and gave a well-presented, well-paced class. This schools online learning programme appears to be well executed, and I look forward to joining future classes.", "date" => "2 months ago"], ["name" => "Reem Soliman", "rating" => 5, "comment" => "I did the Drawing and Theory online course with Andy Pankhurst. I loved it so much! To have a 10 week routine with such a knowledgeable and kind tutor like Andy is just so nourishing to anyone wanting to reconnect again in an organic way with art. A wide variety of areas were covered and you slowly start to gain confidence with the continuous drawing practice and the brilliant guidance from Andy along the way.", "date" => "A year ago"], ["name" => "Peter Voss-Knude", "rating" => 4, "comment" => "A very useful resource for developing the art of drawing as a means for discovery and not just mimicry. Very attentive tutoring, meaningful exercises and well thought out structure to make the best of the online format. Also a pleasure to work with a group of artists based around the globe. Thanks!", "date" => "A year ago"], ["name" => "Kazu Oka", "rating" => 4, "comment" => "I've really enjoyed the easter painting courses at RDS. The location is good, the tutor was excellent and the people were friendly and inspiring. However I cannot agree with RDS's policy to limit to just one solvent brand we all must use. I do understand the reason why they want us to use non-toxic solvent but nowadays there are more than one such solvents exist. And their choice is the least commonly available one. I must say £7.5 for 200ml of this particular solvent was good buy, however, the problem was 200ml wasn't enough for most of us. We couldn't purchase more than 200ml. At least we should have freedom to purchase additional amount if RDS want to stick with this brand. I hope RDS will reconsider this policy by Summer, then we will be able to clean our brushes more frequently.", "date" => "A year ago"]];
        // line 47
        yield "        
        ";
        // line 48
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["reviews"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["review"]) {
            // line 49
            yield "        <div class=\"review-card\">
            <div class=\"review-header\">
                <h3>";
            // line 51
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["review"], "name", [], "any", false, false, false, 51), "html", null, true);
            yield "</h3>
                <div class=\"rating\">
                    ";
            // line 53
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(range(1, 5));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 54
                yield "                        ";
                if (($context["i"] <= CoreExtension::getAttribute($this->env, $this->source, $context["review"], "rating", [], "any", false, false, false, 54))) {
                    // line 55
                    yield "                            ★
                        ";
                } else {
                    // line 57
                    yield "                            ☆
                        ";
                }
                // line 59
                yield "                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['i'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 60
            yield "                </div>
                <span class=\"review-date\">";
            // line 61
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["review"], "date", [], "any", false, false, false, 61), "html", null, true);
            yield "</span>
            </div>
            <p class=\"review-text\">";
            // line 63
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["review"], "comment", [], "any", false, false, false, 63), "html", null, true);
            yield "</p>
        </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['review'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 66
        yield "    </div>
</section>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "reviews.html";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  131 => 66,  122 => 63,  117 => 61,  114 => 60,  108 => 59,  104 => 57,  100 => 55,  97 => 54,  93 => 53,  88 => 51,  84 => 49,  80 => 48,  77 => 47,  75 => 9,  70 => 6,  63 => 5,  52 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends \"base.html\" %}

{% block title %}Reviews - Royal Drawing School{% endblock %}

{% block content %}
<section id=\"reviews-page\">
    <h1>STUDENT REVIEWS</h1>
    <div class=\"reviews-container\">
        {% set reviews = [
            {
                'name': 'Eleanor Davis',
                'rating': 5,
                'comment': 'I really enjoyed my recent course The 10 week Colour and Composition Course run by the Royal Drawing School. It far passed my expectations of what I would learn . The course was really well structured yet there was no pressure. Presentations of Artists\\' work were varied and stimulating leaving the student with lots of ideas. Practical help was always on hand and over the 10 weeks it became an open and friendly working experience - yet the outcomes and achievements were exceptionally high. I would definitely recommend this course to anyone who practices',
                'date': '3 months ago'
            },
            {
                'name': 'Bella Foster',
                'rating': 5,
                'comment': 'This was the best drawing and writing course I\\'ve ever taken. I came in as a total beginner, and the course exceeded my expectations. Emily Haworth Booth is an incredible teacher, and I\\'m so grateful I took the plunge signing up',
                'date': '3 months ago'
            },
            {
                'name': 'Deb Daines',
                'rating': 5,
                'comment': 'Online monotype life drawing lesson was informative, & fun! (I\\'m deaf, & have M.E.) Despite a last minute change of tutor, she was vocally understandable, and gave a well-presented, well-paced class. This schools online learning programme appears to be well executed, and I look forward to joining future classes.',
                'date': '2 months ago'
            },
            {
                'name': 'Reem Soliman',
                'rating': 5,
                'comment': 'I did the Drawing and Theory online course with Andy Pankhurst. I loved it so much! To have a 10 week routine with such a knowledgeable and kind tutor like Andy is just so nourishing to anyone wanting to reconnect again in an organic way with art. A wide variety of areas were covered and you slowly start to gain confidence with the continuous drawing practice and the brilliant guidance from Andy along the way.',
                'date': 'A year ago'
            },
            {
                'name': 'Peter Voss-Knude',
                'rating': 4,
                'comment': 'A very useful resource for developing the art of drawing as a means for discovery and not just mimicry. Very attentive tutoring, meaningful exercises and well thought out structure to make the best of the online format. Also a pleasure to work with a group of artists based around the globe. Thanks!',
                'date': 'A year ago'
            },
            {
                'name': 'Kazu Oka',
                'rating': 4,
                'comment': 'I\\'ve really enjoyed the easter painting courses at RDS. The location is good, the tutor was excellent and the people were friendly and inspiring. However I cannot agree with RDS\\'s policy to limit to just one solvent brand we all must use. I do understand the reason why they want us to use non-toxic solvent but nowadays there are more than one such solvents exist. And their choice is the least commonly available one. I must say £7.5 for 200ml of this particular solvent was good buy, however, the problem was 200ml wasn\\'t enough for most of us. We couldn\\'t purchase more than 200ml. At least we should have freedom to purchase additional amount if RDS want to stick with this brand. I hope RDS will reconsider this policy by Summer, then we will be able to clean our brushes more frequently.',
                'date': 'A year ago'
            }
        ] %}
        
        {% for review in reviews %}
        <div class=\"review-card\">
            <div class=\"review-header\">
                <h3>{{ review.name }}</h3>
                <div class=\"rating\">
                    {% for i in 1..5 %}
                        {% if i <= review.rating %}
                            ★
                        {% else %}
                            ☆
                        {% endif %}
                    {% endfor %}
                </div>
                <span class=\"review-date\">{{ review.date }}</span>
            </div>
            <p class=\"review-text\">{{ review.comment }}</p>
        </div>
        {% endfor %}
    </div>
</section>
{% endblock %}
", "reviews.html", "C:\\Users\\Admin\\OneDrive\\Documents\\fresit\\app\\views\\reviews.html");
    }
}
