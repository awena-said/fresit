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

/* booking.html */
class __TwigTemplate_a01716186e7b9c26c6e43de756c9e469 extends Template
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
            'extra_js' => [$this, 'block_extra_js'],
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
        $this->parent = $this->loadTemplate("base.html", "booking.html", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Book Classes - Royal Drawing School";
        yield from [];
    }

    // line 4
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_description(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Apply for drawing classes at Royal Drawing School";
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
        yield "    <link rel=\"stylesheet\" href=\"/fresit/public/styles/booking.css\">
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
        yield "<section class=\"booking-section\">
    <div class=\"booking-container\">
        <h1>Book Your Class</h1>
        <p class=\"booking-intro\">Choose from our available classes and apply for enrollment. Classes run on a rolling, continuous basis with 10-week blocks.</p>

        ";
        // line 16
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "general", [], "any", false, false, false, 16)) {
            // line 17
            yield "            <div class=\"alert alert-error\">
                ";
            // line 18
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "general", [], "any", false, false, false, 18), "html", null, true);
            yield "
            </div>
        ";
        }
        // line 21
        yield "
        <!-- Class Selection Form -->
        <form id=\"booking-form\" class=\"booking-form\" method=\"POST\" action=\"/booking/apply\">
            <div class=\"form-section\">
                <h2>Select Your Class</h2>
                
                <div class=\"form-row\">
                    <div class=\"form-group\">
                        <label for=\"class_type\">Class Type</label>
                        <select id=\"class_type\" name=\"class_type\" required>
                            <option value=\"\">Select class type</option>
                            ";
        // line 32
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["class_types"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["type"]) {
            // line 33
            yield "                                <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "id", [], "any", false, false, false, 33), "html", null, true);
            yield "\" ";
            if ((CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "class_type", [], "any", false, false, false, 33) == CoreExtension::getAttribute($this->env, $this->source, $context["type"], "id", [], "any", false, false, false, 33))) {
                yield "selected";
            }
            yield ">
                                    ";
            // line 34
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "name", [], "any", false, false, false, 34), "html", null, true);
            yield "
                                </option>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['type'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 37
        yield "                        </select>
                        ";
        // line 38
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "class_type", [], "any", false, false, false, 38)) {
            // line 39
            yield "                            <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "class_type", [], "any", false, false, false, 39), "html", null, true);
            yield "</span>
                        ";
        }
        // line 41
        yield "                    </div>

                    <div class=\"form-group\">
                        <label for=\"start_date\">Preferred Start Date</label>
                        <input type=\"date\" id=\"start_date\" name=\"start_date\" required 
                               min=\"";
        // line 46
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate("now", "Y-m-d"), "html", null, true);
        yield "\" 
                               max=\"";
        // line 47
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate($this->extensions['Twig\Extension\CoreExtension']->modifyDate("now", "+7 days"), "Y-m-d"), "html", null, true);
        yield "\"
                               value=\"";
        // line 48
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "start_date", [], "any", false, false, false, 48), "html", null, true);
        yield "\">
                        ";
        // line 49
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "start_date", [], "any", false, false, false, 49)) {
            // line 50
            yield "                            <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "start_date", [], "any", false, false, false, 50), "html", null, true);
            yield "</span>
                        ";
        }
        // line 52
        yield "                    </div>
                </div>

                <div class=\"form-group\">
                    <label for=\"class_id\">Available Classes</label>
                    <select id=\"class_id\" name=\"class_id\" required>
                        <option value=\"\">Select a class (choose type and date first)</option>
                    </select>
                    ";
        // line 60
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "class_id", [], "any", false, false, false, 60)) {
            // line 61
            yield "                        <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "class_id", [], "any", false, false, false, 61), "html", null, true);
            yield "</span>
                    ";
        }
        // line 63
        yield "                </div>
            </div>

            <div class=\"form-section\">
                <h2>Your Details</h2>
                
                <div class=\"form-row\">
                    <div class=\"form-group\">
                        <label for=\"student_name\">Full Name</label>
                        <input type=\"text\" id=\"student_name\" name=\"student_name\" required 
                               value=\"";
        // line 73
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "student_name", [], "any", false, false, false, 73), "html", null, true);
        yield "\" minlength=\"2\">
                        ";
        // line 74
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "student_name", [], "any", false, false, false, 74)) {
            // line 75
            yield "                            <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "student_name", [], "any", false, false, false, 75), "html", null, true);
            yield "</span>
                        ";
        }
        // line 77
        yield "                    </div>

                    <div class=\"form-group\">
                        <label for=\"student_email\">Email Address</label>
                        <input type=\"email\" id=\"student_email\" name=\"student_email\" required 
                               value=\"";
        // line 82
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "student_email", [], "any", false, false, false, 82), "html", null, true);
        yield "\">
                        ";
        // line 83
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "student_email", [], "any", false, false, false, 83)) {
            // line 84
            yield "                            <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "student_email", [], "any", false, false, false, 84), "html", null, true);
            yield "</span>
                        ";
        }
        // line 86
        yield "                    </div>
                </div>

                <div class=\"form-row\">
                    <div class=\"form-group\">
                        <label for=\"student_phone\">Phone Number</label>
                        <input type=\"tel\" id=\"student_phone\" name=\"student_phone\" required 
                               value=\"";
        // line 93
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "student_phone", [], "any", false, false, false, 93), "html", null, true);
        yield "\">
                        ";
        // line 94
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "student_phone", [], "any", false, false, false, 94)) {
            // line 95
            yield "                            <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "student_phone", [], "any", false, false, false, 95), "html", null, true);
            yield "</span>
                        ";
        }
        // line 97
        yield "                    </div>

                    <div class=\"form-group\">
                        <label for=\"experience_level\">Experience Level</label>
                        <select id=\"experience_level\" name=\"experience_level\" required>
                            <option value=\"\">Select experience level</option>
                            <option value=\"beginner\" ";
        // line 103
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "experience_level", [], "any", false, false, false, 103) == "beginner")) {
            yield "selected";
        }
        yield ">Beginner</option>
                            <option value=\"intermediate\" ";
        // line 104
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "experience_level", [], "any", false, false, false, 104) == "intermediate")) {
            yield "selected";
        }
        yield ">Intermediate</option>
                            <option value=\"advanced\" ";
        // line 105
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "experience_level", [], "any", false, false, false, 105) == "advanced")) {
            yield "selected";
        }
        yield ">Advanced</option>
                        </select>
                        ";
        // line 107
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "experience_level", [], "any", false, false, false, 107)) {
            // line 108
            yield "                            <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "experience_level", [], "any", false, false, false, 108), "html", null, true);
            yield "</span>
                        ";
        }
        // line 110
        yield "                    </div>
                </div>

                <div class=\"form-group\">
                    <label for=\"additional_notes\">Additional Notes (Optional)</label>
                    <textarea id=\"additional_notes\" name=\"additional_notes\" rows=\"3\" 
                              placeholder=\"Any special requirements or questions...\">";
        // line 116
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "additional_notes", [], "any", false, false, false, 116), "html", null, true);
        yield "</textarea>
                </div>
            </div>

            <div class=\"form-actions\">
                <button type=\"submit\" class=\"btn-primary\">Submit Application</button>
                <a href=\"/fresit/\" class=\"btn-secondary\">Cancel</a>
            </div>
        </form>
    </div>
</section>
";
        yield from [];
    }

    // line 129
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_extra_js(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 130
        yield "<script>
document.addEventListener('DOMContentLoaded', function() {
    const classTypeSelect = document.getElementById('class_type');
    const startDateInput = document.getElementById('start_date');
    const classSelect = document.getElementById('class_id');
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    startDateInput.min = today;
    
    // Set maximum date to 7 days from today
    const maxDate = new Date();
    maxDate.setDate(maxDate.getDate() + 7);
    startDateInput.max = maxDate.toISOString().split('T')[0];

    function loadAvailableClasses() {
        const classType = classTypeSelect.value;
        const startDate = startDateInput.value;
        
        if (!classType || !startDate) {
            classSelect.innerHTML = '<option value=\"\">Select a class (choose type and date first)</option>';
            return;
        }

        // Show loading state
        classSelect.innerHTML = '<option value=\"\">Loading available classes...</option>';
        classSelect.disabled = true;

        // Fetch available classes
        fetch(`/api/classes?type=\${classType}&start_date=\${startDate}`)
            .then(response => response.json())
            .then(data => {
                classSelect.innerHTML = '<option value=\"\">Select a class</option>';
                
                if (data.length === 0) {
                    classSelect.innerHTML = '<option value=\"\">No classes available for selected criteria</option>';
                } else {
                    data.forEach(classItem => {
                        const option = document.createElement('option');
                        option.value = classItem.id;
                        option.textContent = `\${classItem.tutor_name} - \${classItem.day_of_week} \${classItem.start_time}-\${classItem.end_time} (\${classItem.available_slots} slots available)`;
                        classSelect.appendChild(option);
                    });
                }
                classSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading classes:', error);
                classSelect.innerHTML = '<option value=\"\">Error loading classes. Please try again.</option>';
                classSelect.disabled = false;
            });
    }

    // Event listeners
    classTypeSelect.addEventListener('change', loadAvailableClasses);
    startDateInput.addEventListener('change', loadAvailableClasses);
});
</script>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "booking.html";
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
        return array (  337 => 130,  330 => 129,  313 => 116,  305 => 110,  299 => 108,  297 => 107,  290 => 105,  284 => 104,  278 => 103,  270 => 97,  264 => 95,  262 => 94,  258 => 93,  249 => 86,  243 => 84,  241 => 83,  237 => 82,  230 => 77,  224 => 75,  222 => 74,  218 => 73,  206 => 63,  200 => 61,  198 => 60,  188 => 52,  182 => 50,  180 => 49,  176 => 48,  172 => 47,  168 => 46,  161 => 41,  155 => 39,  153 => 38,  150 => 37,  141 => 34,  132 => 33,  128 => 32,  115 => 21,  109 => 18,  106 => 17,  104 => 16,  97 => 11,  90 => 10,  84 => 7,  77 => 6,  66 => 4,  55 => 3,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends \"base.html\" %}

{% block title %}Book Classes - Royal Drawing School{% endblock %}
{% block description %}Apply for drawing classes at Royal Drawing School{% endblock %}

{% block extra_css %}
    <link rel=\"stylesheet\" href=\"/fresit/public/styles/booking.css\">
{% endblock %}

{% block content %}
<section class=\"booking-section\">
    <div class=\"booking-container\">
        <h1>Book Your Class</h1>
        <p class=\"booking-intro\">Choose from our available classes and apply for enrollment. Classes run on a rolling, continuous basis with 10-week blocks.</p>

        {% if errors.general %}
            <div class=\"alert alert-error\">
                {{ errors.general }}
            </div>
        {% endif %}

        <!-- Class Selection Form -->
        <form id=\"booking-form\" class=\"booking-form\" method=\"POST\" action=\"/booking/apply\">
            <div class=\"form-section\">
                <h2>Select Your Class</h2>
                
                <div class=\"form-row\">
                    <div class=\"form-group\">
                        <label for=\"class_type\">Class Type</label>
                        <select id=\"class_type\" name=\"class_type\" required>
                            <option value=\"\">Select class type</option>
                            {% for type in class_types %}
                                <option value=\"{{ type.id }}\" {% if form_data.class_type == type.id %}selected{% endif %}>
                                    {{ type.name }}
                                </option>
                            {% endfor %}
                        </select>
                        {% if errors.class_type %}
                            <span class=\"error-message\">{{ errors.class_type }}</span>
                        {% endif %}
                    </div>

                    <div class=\"form-group\">
                        <label for=\"start_date\">Preferred Start Date</label>
                        <input type=\"date\" id=\"start_date\" name=\"start_date\" required 
                               min=\"{{ \"now\"|date(\"Y-m-d\") }}\" 
                               max=\"{{ \"now\"|date_modify(\"+7 days\")|date(\"Y-m-d\") }}\"
                               value=\"{{ form_data.start_date }}\">
                        {% if errors.start_date %}
                            <span class=\"error-message\">{{ errors.start_date }}</span>
                        {% endif %}
                    </div>
                </div>

                <div class=\"form-group\">
                    <label for=\"class_id\">Available Classes</label>
                    <select id=\"class_id\" name=\"class_id\" required>
                        <option value=\"\">Select a class (choose type and date first)</option>
                    </select>
                    {% if errors.class_id %}
                        <span class=\"error-message\">{{ errors.class_id }}</span>
                    {% endif %}
                </div>
            </div>

            <div class=\"form-section\">
                <h2>Your Details</h2>
                
                <div class=\"form-row\">
                    <div class=\"form-group\">
                        <label for=\"student_name\">Full Name</label>
                        <input type=\"text\" id=\"student_name\" name=\"student_name\" required 
                               value=\"{{ form_data.student_name }}\" minlength=\"2\">
                        {% if errors.student_name %}
                            <span class=\"error-message\">{{ errors.student_name }}</span>
                        {% endif %}
                    </div>

                    <div class=\"form-group\">
                        <label for=\"student_email\">Email Address</label>
                        <input type=\"email\" id=\"student_email\" name=\"student_email\" required 
                               value=\"{{ form_data.student_email }}\">
                        {% if errors.student_email %}
                            <span class=\"error-message\">{{ errors.student_email }}</span>
                        {% endif %}
                    </div>
                </div>

                <div class=\"form-row\">
                    <div class=\"form-group\">
                        <label for=\"student_phone\">Phone Number</label>
                        <input type=\"tel\" id=\"student_phone\" name=\"student_phone\" required 
                               value=\"{{ form_data.student_phone }}\">
                        {% if errors.student_phone %}
                            <span class=\"error-message\">{{ errors.student_phone }}</span>
                        {% endif %}
                    </div>

                    <div class=\"form-group\">
                        <label for=\"experience_level\">Experience Level</label>
                        <select id=\"experience_level\" name=\"experience_level\" required>
                            <option value=\"\">Select experience level</option>
                            <option value=\"beginner\" {% if form_data.experience_level == 'beginner' %}selected{% endif %}>Beginner</option>
                            <option value=\"intermediate\" {% if form_data.experience_level == 'intermediate' %}selected{% endif %}>Intermediate</option>
                            <option value=\"advanced\" {% if form_data.experience_level == 'advanced' %}selected{% endif %}>Advanced</option>
                        </select>
                        {% if errors.experience_level %}
                            <span class=\"error-message\">{{ errors.experience_level }}</span>
                        {% endif %}
                    </div>
                </div>

                <div class=\"form-group\">
                    <label for=\"additional_notes\">Additional Notes (Optional)</label>
                    <textarea id=\"additional_notes\" name=\"additional_notes\" rows=\"3\" 
                              placeholder=\"Any special requirements or questions...\">{{ form_data.additional_notes }}</textarea>
                </div>
            </div>

            <div class=\"form-actions\">
                <button type=\"submit\" class=\"btn-primary\">Submit Application</button>
                <a href=\"/fresit/\" class=\"btn-secondary\">Cancel</a>
            </div>
        </form>
    </div>
</section>
{% endblock %}

{% block extra_js %}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const classTypeSelect = document.getElementById('class_type');
    const startDateInput = document.getElementById('start_date');
    const classSelect = document.getElementById('class_id');
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    startDateInput.min = today;
    
    // Set maximum date to 7 days from today
    const maxDate = new Date();
    maxDate.setDate(maxDate.getDate() + 7);
    startDateInput.max = maxDate.toISOString().split('T')[0];

    function loadAvailableClasses() {
        const classType = classTypeSelect.value;
        const startDate = startDateInput.value;
        
        if (!classType || !startDate) {
            classSelect.innerHTML = '<option value=\"\">Select a class (choose type and date first)</option>';
            return;
        }

        // Show loading state
        classSelect.innerHTML = '<option value=\"\">Loading available classes...</option>';
        classSelect.disabled = true;

        // Fetch available classes
        fetch(`/api/classes?type=\${classType}&start_date=\${startDate}`)
            .then(response => response.json())
            .then(data => {
                classSelect.innerHTML = '<option value=\"\">Select a class</option>';
                
                if (data.length === 0) {
                    classSelect.innerHTML = '<option value=\"\">No classes available for selected criteria</option>';
                } else {
                    data.forEach(classItem => {
                        const option = document.createElement('option');
                        option.value = classItem.id;
                        option.textContent = `\${classItem.tutor_name} - \${classItem.day_of_week} \${classItem.start_time}-\${classItem.end_time} (\${classItem.available_slots} slots available)`;
                        classSelect.appendChild(option);
                    });
                }
                classSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading classes:', error);
                classSelect.innerHTML = '<option value=\"\">Error loading classes. Please try again.</option>';
                classSelect.disabled = false;
            });
    }

    // Event listeners
    classTypeSelect.addEventListener('change', loadAvailableClasses);
    startDateInput.addEventListener('change', loadAvailableClasses);
});
</script>
{% endblock %} ", "booking.html", "C:\\Users\\Admin\\OneDrive\\Documents\\fresit\\app\\views\\booking.html");
    }
}
