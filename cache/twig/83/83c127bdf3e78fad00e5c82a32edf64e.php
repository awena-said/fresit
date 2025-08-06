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

/* student/register.html */
class __TwigTemplate_2ecc603a564e279b2e4e4b965175301d extends Template
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
        $this->parent = $this->loadTemplate("base.html", "student/register.html", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Student Registration - Fresit Art School";
        yield from [];
    }

    // line 4
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_description(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Create your student account to manage your art class applications";
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
            <h1>Create Your Student Account</h1>
            <p>Join Fresit Art School and start your artistic journey today!</p>
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
        <form class=\"student-form\" method=\"POST\" action=\"/student/register\">
            <div class=\"form-group\">
                <label for=\"name\">Full Name *</label>
                <input type=\"text\" id=\"name\" name=\"name\" required 
                       value=\"";
        // line 28
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "name", [], "any", false, false, false, 28), "html", null, true);
        yield "\" minlength=\"2\">
                ";
        // line 29
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "name", [], "any", false, false, false, 29)) {
            // line 30
            yield "                    <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "name", [], "any", false, false, false, 30), "html", null, true);
            yield "</span>
                ";
        }
        // line 32
        yield "            </div>

            <div class=\"form-group\">
                <label for=\"email\">Email Address *</label>
                <input type=\"email\" id=\"email\" name=\"email\" required 
                       value=\"";
        // line 37
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "email", [], "any", false, false, false, 37), "html", null, true);
        yield "\">
                ";
        // line 38
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "email", [], "any", false, false, false, 38)) {
            // line 39
            yield "                    <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "email", [], "any", false, false, false, 39), "html", null, true);
            yield "</span>
                ";
        }
        // line 41
        yield "            </div>

            <div class=\"form-group\">
                <label for=\"phone\">Phone Number *</label>
                <input type=\"tel\" id=\"phone\" name=\"phone\" required 
                       value=\"";
        // line 46
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "phone", [], "any", false, false, false, 46), "html", null, true);
        yield "\">
                ";
        // line 47
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "phone", [], "any", false, false, false, 47)) {
            // line 48
            yield "                    <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "phone", [], "any", false, false, false, 48), "html", null, true);
            yield "</span>
                ";
        }
        // line 50
        yield "            </div>

            <div class=\"form-group\">
                <label for=\"password\">Password *</label>
                <input type=\"password\" id=\"password\" name=\"password\" required 
                       minlength=\"6\">
                ";
        // line 56
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "password", [], "any", false, false, false, 56)) {
            // line 57
            yield "                    <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "password", [], "any", false, false, false, 57), "html", null, true);
            yield "</span>
                ";
        }
        // line 59
        yield "                <small>Must be at least 6 characters long</small>
            </div>

            <div class=\"form-actions\">
                <button type=\"submit\" class=\"btn-primary\">Create Account</button>
                <a href=\"/fresit/student/login\" class=\"btn-secondary\">Already have an account? Login</a>
            </div>
        </form>

        <div class=\"form-footer\">
            <p>By creating an account, you agree to our <a href=\"/terms\">Terms of Service</a> and <a href=\"/privacy\">Privacy Policy</a>.</p>
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
        return "student/register.html";
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
        return array (  189 => 59,  183 => 57,  181 => 56,  173 => 50,  167 => 48,  165 => 47,  161 => 46,  154 => 41,  148 => 39,  146 => 38,  142 => 37,  135 => 32,  129 => 30,  127 => 29,  123 => 28,  116 => 23,  110 => 20,  107 => 19,  105 => 18,  96 => 11,  89 => 10,  83 => 7,  76 => 6,  65 => 4,  54 => 3,  43 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends \"base.html\" %}

{% block title %}Student Registration - Fresit Art School{% endblock %}
{% block description %}Create your student account to manage your art class applications{% endblock %}

{% block extra_css %}
    <link rel=\"stylesheet\" href=\"/fresit/public/styles/student.css\">
{% endblock %}

{% block content %}
<section class=\"student-section\">
    <div class=\"student-container\">
        <div class=\"form-header\">
            <h1>Create Your Student Account</h1>
            <p>Join Fresit Art School and start your artistic journey today!</p>
        </div>

        {% if errors.general %}
            <div class=\"alert alert-error\">
                {{ errors.general }}
            </div>
        {% endif %}

        <form class=\"student-form\" method=\"POST\" action=\"/student/register\">
            <div class=\"form-group\">
                <label for=\"name\">Full Name *</label>
                <input type=\"text\" id=\"name\" name=\"name\" required 
                       value=\"{{ form_data.name }}\" minlength=\"2\">
                {% if errors.name %}
                    <span class=\"error-message\">{{ errors.name }}</span>
                {% endif %}
            </div>

            <div class=\"form-group\">
                <label for=\"email\">Email Address *</label>
                <input type=\"email\" id=\"email\" name=\"email\" required 
                       value=\"{{ form_data.email }}\">
                {% if errors.email %}
                    <span class=\"error-message\">{{ errors.email }}</span>
                {% endif %}
            </div>

            <div class=\"form-group\">
                <label for=\"phone\">Phone Number *</label>
                <input type=\"tel\" id=\"phone\" name=\"phone\" required 
                       value=\"{{ form_data.phone }}\">
                {% if errors.phone %}
                    <span class=\"error-message\">{{ errors.phone }}</span>
                {% endif %}
            </div>

            <div class=\"form-group\">
                <label for=\"password\">Password *</label>
                <input type=\"password\" id=\"password\" name=\"password\" required 
                       minlength=\"6\">
                {% if errors.password %}
                    <span class=\"error-message\">{{ errors.password }}</span>
                {% endif %}
                <small>Must be at least 6 characters long</small>
            </div>

            <div class=\"form-actions\">
                <button type=\"submit\" class=\"btn-primary\">Create Account</button>
                <a href=\"/fresit/student/login\" class=\"btn-secondary\">Already have an account? Login</a>
            </div>
        </form>

        <div class=\"form-footer\">
            <p>By creating an account, you agree to our <a href=\"/terms\">Terms of Service</a> and <a href=\"/privacy\">Privacy Policy</a>.</p>
        </div>
    </div>
</section>
{% endblock %} ", "student/register.html", "C:\\Users\\Admin\\OneDrive\\Documents\\fresit\\app\\views\\student\\register.html");
    }
}
