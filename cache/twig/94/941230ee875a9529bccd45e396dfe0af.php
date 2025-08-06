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

/* student/booking.html */
class __TwigTemplate_15a00f673264a77687791294f0aa2908 extends Template
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
        $this->parent = $this->loadTemplate("base.html", "student/booking.html", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Apply for Classes - Fresit Art School";
        yield from [];
    }

    // line 4
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_description(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Apply for art classes at Fresit Art School";
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
            <h1>Apply for Art Classes</h1>
            <p>Choose your preferred class type, date, and time slot. You can apply without creating an account, or login to manage your applications.</p>
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
        <form id=\"booking-form\" class=\"student-form\" method=\"POST\" action=\"/student/apply\">
            <div class=\"form-section\">
                <h2>Class Selection</h2>
                
                <div class=\"form-row\">
                    <div class=\"form-group\">
                        <label for=\"class_type\">Class Type *</label>
                        <select id=\"class_type\" name=\"class_type\" required>
                            <option value=\"\">Select class type</option>
                            ";
        // line 33
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["class_types"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["type"]) {
            // line 34
            yield "                                <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "id", [], "any", false, false, false, 34), "html", null, true);
            yield "\" ";
            if ((CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "class_type", [], "any", false, false, false, 34) == CoreExtension::getAttribute($this->env, $this->source, $context["type"], "id", [], "any", false, false, false, 34))) {
                yield "selected";
            }
            yield ">
                                    ";
            // line 35
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "name", [], "any", false, false, false, 35), "html", null, true);
            yield "
                                </option>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['type'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 38
        yield "                        </select>
                        ";
        // line 39
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "class_type", [], "any", false, false, false, 39)) {
            // line 40
            yield "                            <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "class_type", [], "any", false, false, false, 40), "html", null, true);
            yield "</span>
                        ";
        }
        // line 42
        yield "                    </div>

                    <div class=\"form-group\">
                        <label for=\"start_date\">Preferred Start Date *</label>
                        <input type=\"date\" id=\"start_date\" name=\"start_date\" required 
                               min=\"";
        // line 47
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate("now", "Y-m-d"), "html", null, true);
        yield "\" 
                               max=\"";
        // line 48
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate($this->extensions['Twig\Extension\CoreExtension']->modifyDate("now", "+30 days"), "Y-m-d"), "html", null, true);
        yield "\"
                               value=\"";
        // line 49
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "start_date", [], "any", false, false, false, 49), "html", null, true);
        yield "\">
                        ";
        // line 50
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "start_date", [], "any", false, false, false, 50)) {
            // line 51
            yield "                            <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "start_date", [], "any", false, false, false, 51), "html", null, true);
            yield "</span>
                        ";
        }
        // line 53
        yield "                    </div>
                </div>

                <div class=\"form-group\">
                    <label for=\"class_id\">Available Classes & Cohorts *</label>
                    <select id=\"class_id\" name=\"class_id\" required>
                        <option value=\"\">Select a class (choose type and date first)</option>
                    </select>
                    ";
        // line 61
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "class_id", [], "any", false, false, false, 61)) {
            // line 62
            yield "                        <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "class_id", [], "any", false, false, false, 62), "html", null, true);
            yield "</span>
                    ";
        }
        // line 64
        yield "                    <small>Available time slots and tutors will be shown based on your selection</small>
                </div>
            </div>

            <div class=\"form-section\">
                <h2>Your Details</h2>
                
                <div class=\"form-row\">
                    <div class=\"form-group\">
                        <label for=\"student_name\">Full Name *</label>
                        <input type=\"text\" id=\"student_name\" name=\"student_name\" required 
                               value=\"";
        // line 75
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "student_name", [], "any", false, false, false, 75), "html", null, true);
        yield "\" minlength=\"2\">
                        ";
        // line 76
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "student_name", [], "any", false, false, false, 76)) {
            // line 77
            yield "                            <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "student_name", [], "any", false, false, false, 77), "html", null, true);
            yield "</span>
                        ";
        }
        // line 79
        yield "                    </div>

                    <div class=\"form-group\">
                        <label for=\"student_email\">Email Address *</label>
                        <input type=\"email\" id=\"student_email\" name=\"student_email\" required 
                               value=\"";
        // line 84
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "student_email", [], "any", false, false, false, 84), "html", null, true);
        yield "\">
                        ";
        // line 85
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "student_email", [], "any", false, false, false, 85)) {
            // line 86
            yield "                            <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "student_email", [], "any", false, false, false, 86), "html", null, true);
            yield "</span>
                        ";
        }
        // line 88
        yield "                    </div>
                </div>

                <div class=\"form-row\">
                    <div class=\"form-group\">
                        <label for=\"student_phone\">Phone Number *</label>
                        <input type=\"tel\" id=\"student_phone\" name=\"student_phone\" required 
                               value=\"";
        // line 95
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "student_phone", [], "any", false, false, false, 95), "html", null, true);
        yield "\">
                        ";
        // line 96
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "student_phone", [], "any", false, false, false, 96)) {
            // line 97
            yield "                            <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "student_phone", [], "any", false, false, false, 97), "html", null, true);
            yield "</span>
                        ";
        }
        // line 99
        yield "                    </div>

                    <div class=\"form-group\">
                        <label for=\"experience_level\">Experience Level *</label>
                        <select id=\"experience_level\" name=\"experience_level\" required>
                            <option value=\"\">Select experience level</option>
                            <option value=\"beginner\" ";
        // line 105
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "experience_level", [], "any", false, false, false, 105) == "beginner")) {
            yield "selected";
        }
        yield ">Beginner</option>
                            <option value=\"intermediate\" ";
        // line 106
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "experience_level", [], "any", false, false, false, 106) == "intermediate")) {
            yield "selected";
        }
        yield ">Intermediate</option>
                            <option value=\"advanced\" ";
        // line 107
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "experience_level", [], "any", false, false, false, 107) == "advanced")) {
            yield "selected";
        }
        yield ">Advanced</option>
                        </select>
                        ";
        // line 109
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "experience_level", [], "any", false, false, false, 109)) {
            // line 110
            yield "                            <span class=\"error-message\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["errors"] ?? null), "experience_level", [], "any", false, false, false, 110), "html", null, true);
            yield "</span>
                        ";
        }
        // line 112
        yield "                    </div>
                </div>

                <div class=\"form-group\">
                    <label for=\"additional_notes\">Additional Notes (Optional)</label>
                    <textarea id=\"additional_notes\" name=\"additional_notes\" rows=\"3\" 
                              placeholder=\"Any special requirements, questions, or additional information...\">";
        // line 118
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["form_data"] ?? null), "additional_notes", [], "any", false, false, false, 118), "html", null, true);
        yield "</textarea>
                </div>
            </div>

            <div class=\"form-actions\">
                <button type=\"submit\" class=\"btn-primary\">Submit Application</button>
                <a href=\"/fresit/\" class=\"btn-secondary\">Cancel</a>
            </div>
        </form>

        <div class=\"form-footer\">
            <p>Already have an account? <a href=\"/fresit/student/login\">Login here</a> to manage your applications.</p>
        </div>
    </div>
</section>
";
        yield from [];
    }

    // line 135
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_extra_js(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 136
        yield "<script>
document.addEventListener('DOMContentLoaded', function() {
    const classTypeSelect = document.getElementById('class_type');
    const startDateInput = document.getElementById('start_date');
    const classSelect = document.getElementById('class_id');
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    startDateInput.min = today;
    
    // Set maximum date to 30 days from today
    const maxDate = new Date();
    maxDate.setDate(maxDate.getDate() + 30);
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
        fetch(`/student/api/classes?type=\${classType}&start_date=\${startDate}`)
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
        return "student/booking.html";
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
        return array (  343 => 136,  336 => 135,  315 => 118,  307 => 112,  301 => 110,  299 => 109,  292 => 107,  286 => 106,  280 => 105,  272 => 99,  266 => 97,  264 => 96,  260 => 95,  251 => 88,  245 => 86,  243 => 85,  239 => 84,  232 => 79,  226 => 77,  224 => 76,  220 => 75,  207 => 64,  201 => 62,  199 => 61,  189 => 53,  183 => 51,  181 => 50,  177 => 49,  173 => 48,  169 => 47,  162 => 42,  156 => 40,  154 => 39,  151 => 38,  142 => 35,  133 => 34,  129 => 33,  117 => 23,  111 => 20,  108 => 19,  106 => 18,  97 => 11,  90 => 10,  84 => 7,  77 => 6,  66 => 4,  55 => 3,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends \"base.html\" %}

{% block title %}Apply for Classes - Fresit Art School{% endblock %}
{% block description %}Apply for art classes at Fresit Art School{% endblock %}

{% block extra_css %}
    <link rel=\"stylesheet\" href=\"/fresit/public/styles/student.css\">
{% endblock %}

{% block content %}
<section class=\"student-section\">
    <div class=\"student-container\">
        <div class=\"form-header\">
            <h1>Apply for Art Classes</h1>
            <p>Choose your preferred class type, date, and time slot. You can apply without creating an account, or login to manage your applications.</p>
        </div>

        {% if errors.general %}
            <div class=\"alert alert-error\">
                {{ errors.general }}
            </div>
        {% endif %}

        <form id=\"booking-form\" class=\"student-form\" method=\"POST\" action=\"/student/apply\">
            <div class=\"form-section\">
                <h2>Class Selection</h2>
                
                <div class=\"form-row\">
                    <div class=\"form-group\">
                        <label for=\"class_type\">Class Type *</label>
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
                        <label for=\"start_date\">Preferred Start Date *</label>
                        <input type=\"date\" id=\"start_date\" name=\"start_date\" required 
                               min=\"{{ \"now\"|date(\"Y-m-d\") }}\" 
                               max=\"{{ \"now\"|date_modify(\"+30 days\")|date(\"Y-m-d\") }}\"
                               value=\"{{ form_data.start_date }}\">
                        {% if errors.start_date %}
                            <span class=\"error-message\">{{ errors.start_date }}</span>
                        {% endif %}
                    </div>
                </div>

                <div class=\"form-group\">
                    <label for=\"class_id\">Available Classes & Cohorts *</label>
                    <select id=\"class_id\" name=\"class_id\" required>
                        <option value=\"\">Select a class (choose type and date first)</option>
                    </select>
                    {% if errors.class_id %}
                        <span class=\"error-message\">{{ errors.class_id }}</span>
                    {% endif %}
                    <small>Available time slots and tutors will be shown based on your selection</small>
                </div>
            </div>

            <div class=\"form-section\">
                <h2>Your Details</h2>
                
                <div class=\"form-row\">
                    <div class=\"form-group\">
                        <label for=\"student_name\">Full Name *</label>
                        <input type=\"text\" id=\"student_name\" name=\"student_name\" required 
                               value=\"{{ form_data.student_name }}\" minlength=\"2\">
                        {% if errors.student_name %}
                            <span class=\"error-message\">{{ errors.student_name }}</span>
                        {% endif %}
                    </div>

                    <div class=\"form-group\">
                        <label for=\"student_email\">Email Address *</label>
                        <input type=\"email\" id=\"student_email\" name=\"student_email\" required 
                               value=\"{{ form_data.student_email }}\">
                        {% if errors.student_email %}
                            <span class=\"error-message\">{{ errors.student_email }}</span>
                        {% endif %}
                    </div>
                </div>

                <div class=\"form-row\">
                    <div class=\"form-group\">
                        <label for=\"student_phone\">Phone Number *</label>
                        <input type=\"tel\" id=\"student_phone\" name=\"student_phone\" required 
                               value=\"{{ form_data.student_phone }}\">
                        {% if errors.student_phone %}
                            <span class=\"error-message\">{{ errors.student_phone }}</span>
                        {% endif %}
                    </div>

                    <div class=\"form-group\">
                        <label for=\"experience_level\">Experience Level *</label>
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
                              placeholder=\"Any special requirements, questions, or additional information...\">{{ form_data.additional_notes }}</textarea>
                </div>
            </div>

            <div class=\"form-actions\">
                <button type=\"submit\" class=\"btn-primary\">Submit Application</button>
                <a href=\"/fresit/\" class=\"btn-secondary\">Cancel</a>
            </div>
        </form>

        <div class=\"form-footer\">
            <p>Already have an account? <a href=\"/fresit/student/login\">Login here</a> to manage your applications.</p>
        </div>
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
    
    // Set maximum date to 30 days from today
    const maxDate = new Date();
    maxDate.setDate(maxDate.getDate() + 30);
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
        fetch(`/student/api/classes?type=\${classType}&start_date=\${startDate}`)
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
{% endblock %} ", "student/booking.html", "C:\\Users\\Admin\\OneDrive\\Documents\\fresit\\app\\views\\student\\booking.html");
    }
}
