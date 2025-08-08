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

/* student/login.html */
class __TwigTemplate_f7863d5bf8a687c2b2123a635510496c extends Template
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
            'description' => [$this, 'block_description'],
            'extra_css' => [$this, 'block_extra_css'],
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
        $this->parent = $this->loadTemplate("base.html", "student/login.html", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Student Login - Fresit Art School";
        yield from [];
    }

    // line 4
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_description(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Login to your student account to manage your applications";
        yield from [];
    }

    // line 6
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_extra_css(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 7
        yield "    <link rel=\"stylesheet\" href=\"/fresit/public/styles/student.css\">
";
        yield from [];
    }

    // line 10
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 11
        yield "<section class=\"student-section\">
    <div class=\"student-container\">
        <div class=\"form-header\">
            <h1>Student Login</h1>
            <p>Welcome back! Login to your account to manage your applications.</p>
        </div>

        ";
        // line 18
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "general", [], "any", false, false, false, 18)) {
            // line 19
            yield "            <div class=\"alert alert-error\">
                ";
            // line 20
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "general", [], "any", false, false, false, 20), "html", null, true);
            yield "
            </div>
        ";
        }
        // line 23
        yield "
        ";
        // line 24
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["_GET"] ?? null), "registered", [], "any", false, false, false, 24)) {
            // line 25
            yield "            <div class=\"alert alert-success\">
                Registration successful! Please login with your email and password.
            </div>
        ";
        }
        // line 29
        yield "
        <form class=\"student-form\" method=\"POST\" action=\"/student/login\">
            <div class=\"form-group\">
                <label for=\"email\">Email Address</label>
                <input type=\"email\" id=\"email\" name=\"email\" required 
                       value=\"";
        // line 34
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "email", [], "any", false, false, false, 34), "html", null, true);
        yield "\">
                ";
        // line 35
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "email", [], "any", false, false, false, 35)) {
            // line 36
            yield "                    <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "email", [], "any", false, false, false, 36), "html", null, true);
            yield "</span>
                ";
        }
        // line 38
        yield "            </div>

            <div class=\"form-group\">
                <label for=\"password\">Password</label>
                <input type=\"password\" id=\"password\" name=\"password\" required>
                ";
        // line 43
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "password", [], "any", false, false, false, 43)) {
            // line 44
            yield "                    <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "password", [], "any", false, false, false, 44), "html", null, true);
            yield "</span>
                ";
        }
        // line 46
        yield "            </div>

            <div class=\"form-actions\">
                <button type=\"submit\" class=\"btn-primary\">Login</button>
                <a href=\"/fresit/student/register\" class=\"btn-secondary\">Create New Account</a>
            </div>
        </form>

        <div class=\"form-footer\">
            <p>Don't have an account? <a href=\"/fresit/student/register\">Register here</a></p>
            <p><a href=\"/fresit/student/forgot-password\">Forgot your password?</a></p>
        </div>
    </div>
</section>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "student/login.html";
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
        return array (  161 => 46,  155 => 44,  153 => 43,  146 => 38,  140 => 36,  138 => 35,  134 => 34,  127 => 29,  121 => 25,  119 => 24,  116 => 23,  110 => 20,  107 => 19,  105 => 18,  96 => 11,  89 => 10,  83 => 7,  76 => 6,  65 => 4,  54 => 3,  43 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends \"base.html\" %}

{% block title %}Student Login - Fresit Art School{% endblock %}
{% block description %}Login to your student account to manage your applications{% endblock %}

{% block extra_css %}
    <link rel=\"stylesheet\" href=\"/fresit/public/styles/student.css\">
{% endblock %}

{% block content %}
<section class=\"student-section\">
    <div class=\"student-container\">
        <div class=\"form-header\">
            <h1>Student Login</h1>
            <p>Welcome back! Login to your account to manage your applications.</p>
        </div>

        {% if errors.general %}
            <div class=\"alert alert-error\">
                {{ errors.general }}
            </div>
        {% endif %}

        {% if _GET.registered %}
            <div class=\"alert alert-success\">
                Registration successful! Please login with your email and password.
            </div>
        {% endif %}

        <form class=\"student-form\" method=\"POST\" action=\"/student/login\">
            <div class=\"form-group\">
                <label for=\"email\">Email Address</label>
                <input type=\"email\" id=\"email\" name=\"email\" required 
                       value=\"{{ form_data.email }}\">
                {% if errors.email %}
                    <span class=\"error-message\">{{ errors.email }}</span>
                {% endif %}
            </div>

            <div class=\"form-group\">
                <label for=\"password\">Password</label>
                <input type=\"password\" id=\"password\" name=\"password\" required>
                {% if errors.password %}
                    <span class=\"error-message\">{{ errors.password }}</span>
                {% endif %}
            </div>

            <div class=\"form-actions\">
                <button type=\"submit\" class=\"btn-primary\">Login</button>
                <a href=\"/fresit/student/register\" class=\"btn-secondary\">Create New Account</a>
            </div>
        </form>

        <div class=\"form-footer\">
            <p>Don't have an account? <a href=\"/fresit/student/register\">Register here</a></p>
            <p><a href=\"/fresit/student/forgot-password\">Forgot your password?</a></p>
        </div>
    </div>
</section>
{% endblock %} ", "student/login.html", "C:\\Users\\Admin\\OneDrive\\Documents\\fresit\\app\\views\\student\\login.html");
    }
}
