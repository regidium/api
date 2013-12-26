<?php

/* NelmioApiDocBundle::method.html.twig */
class __TwigTemplate_d18cdbfc05aded567726e725d43d13643ce44b14a44956ba241853bf6eb46182 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<li class=\"";
        echo twig_escape_filter($this->env, twig_lower_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "method")), "html", null, true);
        echo " operation\" id=\"";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "id"), "html", null, true);
        echo "\">
    <a class=\"heading toggler";
        // line 2
        if ($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "deprecated")) {
            echo " deprecated";
        }
        echo "\" href=\"#";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "id"), "html", null, true);
        echo "\">
        <h3>
            <span class=\"http_method\">
                <i>";
        // line 5
        echo twig_escape_filter($this->env, twig_upper_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "method")), "html", null, true);
        echo "</i>
            </span>

            ";
        // line 8
        if ($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "deprecated")) {
            // line 9
            echo "            <span class=\"deprecated\">
                <i>DEPRECATED</i>
            </span>
            ";
        }
        // line 13
        echo "
            ";
        // line 14
        if ($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "https")) {
            // line 15
            echo "                <span class=\"icon lock\" title=\"HTTPS\"></span>
            ";
        }
        // line 17
        echo "            ";
        if ($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "authentication")) {
            // line 18
            echo "                <span class=\"icon keys\" title=\"Needs ";
            echo twig_escape_filter($this->env, (((twig_length_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "authenticationRoles")) > 0)) ? (twig_join_filter($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "authenticationRoles"), ", ")) : ("authentication")), "html", null, true);
            echo "\"></span>
            ";
        }
        // line 20
        echo "
            <span class=\"path\">
                ";
        // line 22
        if ($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "host", array(), "any", true, true)) {
            // line 23
            echo (($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "https")) ? ("https://") : ("http://"));
            // line 24
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "host"), "html", null, true);
        }
        // line 26
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "uri"), "html", null, true);
        echo "
            </span>
        </h3>
        <ul class=\"options\">
            ";
        // line 30
        if ($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "description", array(), "any", true, true)) {
            // line 31
            echo "                <li>";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "description"), "html", null, true);
            echo "</li>
            ";
        }
        // line 33
        echo "        </ul>
    </a>

    <div class=\"content\" style=\"display: ";
        // line 36
        if ((array_key_exists("displayContent", $context) && ((isset($context["displayContent"]) ? $context["displayContent"] : $this->getContext($context, "displayContent")) == true))) {
            echo "display";
        } else {
            echo "none";
        }
        echo ";\">
        <ul class=\"tabs\">
            <li class=\"selected\" data-pane=\"content\">Documentation</li>
            ";
        // line 39
        if ((isset($context["enableSandbox"]) ? $context["enableSandbox"] : $this->getContext($context, "enableSandbox"))) {
            // line 40
            echo "                <li data-pane=\"sandbox\">Sandbox</li>
            ";
        }
        // line 42
        echo "        </ul>

        <div class=\"panes\">
            <div class=\"pane content selected\">
            ";
        // line 46
        if (($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "documentation", array(), "any", true, true) && (!twig_test_empty($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "documentation"))))) {
            // line 47
            echo "                <h4>Documentation</h4>
                <div>";
            // line 48
            echo $this->env->getExtension('nelmio_api_doc')->markdown($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "documentation"));
            echo "</div>
            ";
        }
        // line 50
        echo "
            ";
        // line 51
        if (($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "link", array(), "any", true, true) && (!twig_test_empty($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "link"))))) {
            // line 52
            echo "                <h4>Link</h4>
                <div><a href=\"";
            // line 53
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "link"), "html", null, true);
            echo "\" target=\"_blank\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "link"), "html", null, true);
            echo "</a></div>
            ";
        }
        // line 55
        echo "
            ";
        // line 56
        if (($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "requirements", array(), "any", true, true) && (!twig_test_empty($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "requirements"))))) {
            // line 57
            echo "                <h4>Requirements</h4>
                <table class=\"fullwidth\">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Requirement</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        ";
            // line 68
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "requirements"));
            foreach ($context['_seq'] as $context["name"] => $context["infos"]) {
                // line 69
                echo "                            <tr>
                                <td>";
                // line 70
                echo twig_escape_filter($this->env, (isset($context["name"]) ? $context["name"] : $this->getContext($context, "name")), "html", null, true);
                echo "</td>
                                <td>";
                // line 71
                echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["infos"]) ? $context["infos"] : null), "requirement", array(), "any", true, true)) ? ($this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "requirement")) : ("")), "html", null, true);
                echo "</td>
                                <td>";
                // line 72
                echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["infos"]) ? $context["infos"] : null), "dataType", array(), "any", true, true)) ? ($this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "dataType")) : ("")), "html", null, true);
                echo "</td>
                                <td>";
                // line 73
                echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["infos"]) ? $context["infos"] : null), "description", array(), "any", true, true)) ? ($this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "description")) : ("")), "html", null, true);
                echo "</td>
                            </tr>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['name'], $context['infos'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 76
            echo "                    </tbody>
                </table>
            ";
        }
        // line 79
        echo "
            ";
        // line 80
        if (($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "filters", array(), "any", true, true) && (!twig_test_empty($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "filters"))))) {
            // line 81
            echo "                <h4>Filters</h4>
                <table class=\"fullwidth\">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Information</th>
                        </tr>
                    </thead>
                    <tbody>
                    ";
            // line 90
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "filters"));
            foreach ($context['_seq'] as $context["name"] => $context["infos"]) {
                // line 91
                echo "                        <tr>
                            <td>";
                // line 92
                echo twig_escape_filter($this->env, (isset($context["name"]) ? $context["name"] : $this->getContext($context, "name")), "html", null, true);
                echo "</td>
                            <td>
                                <table>
                                ";
                // line 95
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")));
                foreach ($context['_seq'] as $context["key"] => $context["value"]) {
                    // line 96
                    echo "                                    <tr>
                                        <td>";
                    // line 97
                    echo twig_escape_filter($this->env, twig_title_string_filter($this->env, (isset($context["key"]) ? $context["key"] : $this->getContext($context, "key"))), "html", null, true);
                    echo "</td>
                                        <td>";
                    // line 98
                    echo twig_escape_filter($this->env, trim(strtr(twig_jsonencode_filter((isset($context["value"]) ? $context["value"] : $this->getContext($context, "value"))), array("\\\\" => "\\")), "\""), "html", null, true);
                    echo "</td>
                                    </tr>
                                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['key'], $context['value'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 101
                echo "                                </table>
                            </td>
                        </tr>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['name'], $context['infos'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 105
            echo "                    </tbody>
                </table>
            ";
        }
        // line 108
        echo "
            ";
        // line 109
        if (($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "parameters", array(), "any", true, true) && (!twig_test_empty($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "parameters"))))) {
            // line 110
            echo "                <h4>Parameters</h4>
                <table class='fullwidth'>
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required?</th>
                            <th>Format</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        ";
            // line 122
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "parameters"));
            foreach ($context['_seq'] as $context["name"] => $context["infos"]) {
                // line 123
                echo "                            ";
                if ((!$this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "readonly"))) {
                    // line 124
                    echo "                                <tr>
                                    <td>";
                    // line 125
                    echo twig_escape_filter($this->env, (isset($context["name"]) ? $context["name"] : $this->getContext($context, "name")), "html", null, true);
                    echo "</td>
                                    <td>";
                    // line 126
                    echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["infos"]) ? $context["infos"] : null), "dataType", array(), "any", true, true)) ? ($this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "dataType")) : ("")), "html", null, true);
                    echo "</td>
                                    <td>";
                    // line 127
                    echo (($this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "required")) ? ("true") : ("false"));
                    echo "</td>
                                    <td>";
                    // line 128
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "format"), "html", null, true);
                    echo "</td>
                                    <td>";
                    // line 129
                    echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["infos"]) ? $context["infos"] : null), "description", array(), "any", true, true)) ? ($this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "description")) : ("")), "html", null, true);
                    echo "</td>
                                </tr>
                            ";
                }
                // line 132
                echo "                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['name'], $context['infos'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 133
            echo "                    </tbody>
                </table>
            ";
        }
        // line 136
        echo "
            ";
        // line 137
        if (($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "response", array(), "any", true, true) && (!twig_test_empty($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "response"))))) {
            // line 138
            echo "                <h4>Return</h4>
                <table class='fullwidth'>
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Versions</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        ";
            // line 149
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "response"));
            foreach ($context['_seq'] as $context["name"] => $context["infos"]) {
                // line 150
                echo "                            <tr>
                                <td>";
                // line 151
                echo twig_escape_filter($this->env, (isset($context["name"]) ? $context["name"] : $this->getContext($context, "name")), "html", null, true);
                echo "</td>
                                <td>";
                // line 152
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "dataType"), "html", null, true);
                echo "</td>
                                <td>";
                // line 153
                $this->env->loadTemplate("NelmioApiDocBundle:Components:version.html.twig")->display(array("sinceVersion" => $this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "sinceVersion"), "untilVersion" => $this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "untilVersion")));
                echo "</td>
                                <td>";
                // line 154
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "description"), "html", null, true);
                echo "</td>
                            </tr>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['name'], $context['infos'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 157
            echo "                    </tbody>
                </table>
            ";
        }
        // line 160
        echo "
            ";
        // line 161
        if (($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "statusCodes", array(), "any", true, true) && (!twig_test_empty($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "statusCodes"))))) {
            // line 162
            echo "                <h4>Status Codes</h4>
                <table class=\"fullwidth\">
                    <thead>
                    <tr>
                        <th>Status Code</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    ";
            // line 171
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "statusCodes"));
            foreach ($context['_seq'] as $context["status_code"] => $context["descriptions"]) {
                // line 172
                echo "                        <tr>
                            <td><a href=\"http://en.wikipedia.org/wiki/HTTP_";
                // line 173
                echo twig_escape_filter($this->env, (isset($context["status_code"]) ? $context["status_code"] : $this->getContext($context, "status_code")), "html", null, true);
                echo "\" target=\"_blank\">";
                echo twig_escape_filter($this->env, (isset($context["status_code"]) ? $context["status_code"] : $this->getContext($context, "status_code")), "html", null, true);
                echo "<a/></td>
                            <td>
                                <ul>
                                    ";
                // line 176
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["descriptions"]) ? $context["descriptions"] : $this->getContext($context, "descriptions")));
                foreach ($context['_seq'] as $context["_key"] => $context["description"]) {
                    // line 177
                    echo "                                        <li>";
                    echo twig_escape_filter($this->env, (isset($context["description"]) ? $context["description"] : $this->getContext($context, "description")), "html", null, true);
                    echo "</li>
                                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['description'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 179
                echo "                                </ul>
                            </td>
                        </tr>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['status_code'], $context['descriptions'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 183
            echo "                    </tbody>
                </table>
            ";
        }
        // line 186
        echo "
            ";
        // line 187
        if (($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "cache", array(), "any", true, true) && (!twig_test_empty($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "cache"))))) {
            // line 188
            echo "                <h4>Cache</h4>
                <div>";
            // line 189
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "cache"), "html", null, true);
            echo "s</div>
            ";
        }
        // line 191
        echo "
            </div>

            ";
        // line 194
        if ((isset($context["enableSandbox"]) ? $context["enableSandbox"] : $this->getContext($context, "enableSandbox"))) {
            // line 195
            echo "                <div class=\"pane sandbox\">
                    <form method=\"";
            // line 196
            echo twig_escape_filter($this->env, twig_upper_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "method")), "html", null, true);
            echo "\" action=\"";
            if ($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "host", array(), "any", true, true)) {
                echo (($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "https")) ? ("https://") : ("http://"));
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "host"), "html", null, true);
            }
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "uri"), "html", null, true);
            echo "\">
                        <fieldset class=\"parameters\">
                            <legend>Input</legend>
                            ";
            // line 199
            if ($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "requirements", array(), "any", true, true)) {
                // line 200
                echo "                                <h4>Requirements</h4>
                                ";
                // line 201
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "requirements"));
                foreach ($context['_seq'] as $context["name"] => $context["infos"]) {
                    // line 202
                    echo "                                    <p class=\"tuple\">
                                        <input type=\"text\" class=\"key\" value=\"";
                    // line 203
                    echo twig_escape_filter($this->env, (isset($context["name"]) ? $context["name"] : $this->getContext($context, "name")), "html", null, true);
                    echo "\" placeholder=\"Key\" />
                                        <span>=</span>
                                        <input type=\"text\" class=\"value\" placeholder=\"";
                    // line 205
                    if ($this->getAttribute((isset($context["infos"]) ? $context["infos"] : null), "description", array(), "any", true, true)) {
                        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "description"), "html", null, true);
                    } else {
                        echo "Value";
                    }
                    echo "\" ";
                    if ($this->getAttribute((isset($context["infos"]) ? $context["infos"] : null), "default", array(), "any", true, true)) {
                        echo " value=\"";
                        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "default"), "html", null, true);
                        echo "\" ";
                    }
                    echo "/> <span class=\"remove\">-</span>
                                    </p>
                                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['name'], $context['infos'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 208
                echo "                            ";
            }
            // line 209
            echo "                            ";
            if ($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "filters", array(), "any", true, true)) {
                // line 210
                echo "                                <h4>Filters</h4>
                                ";
                // line 211
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "filters"));
                foreach ($context['_seq'] as $context["name"] => $context["infos"]) {
                    // line 212
                    echo "                                    <p class=\"tuple\">
                                        <input type=\"text\" class=\"key\" value=\"";
                    // line 213
                    echo twig_escape_filter($this->env, (isset($context["name"]) ? $context["name"] : $this->getContext($context, "name")), "html", null, true);
                    echo "\" placeholder=\"Key\" />
                                        <span>=</span>
                                        <input type=\"text\" class=\"value\" placeholder=\"";
                    // line 215
                    if ($this->getAttribute((isset($context["infos"]) ? $context["infos"] : null), "description", array(), "any", true, true)) {
                        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "description"), "html", null, true);
                    } else {
                        echo "Value";
                    }
                    echo "\" ";
                    if ($this->getAttribute((isset($context["infos"]) ? $context["infos"] : null), "default", array(), "any", true, true)) {
                        echo " value=\"";
                        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "default"), "html", null, true);
                        echo "\" ";
                    }
                    echo "/> <span class=\"remove\">-</span>
                                    </p>
                                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['name'], $context['infos'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 218
                echo "                            ";
            }
            // line 219
            echo "                            ";
            if ($this->getAttribute((isset($context["data"]) ? $context["data"] : null), "parameters", array(), "any", true, true)) {
                // line 220
                echo "                                <h4>Parameters</h4>
                                ";
                // line 221
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["data"]) ? $context["data"] : $this->getContext($context, "data")), "parameters"));
                foreach ($context['_seq'] as $context["name"] => $context["infos"]) {
                    // line 222
                    echo "                                ";
                    if ((!$this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "readonly"))) {
                        // line 223
                        echo "                                    <p class=\"tuple\">
                                        <input type=\"text\" class=\"key\" value=\"";
                        // line 224
                        echo twig_escape_filter($this->env, (isset($context["name"]) ? $context["name"] : $this->getContext($context, "name")), "html", null, true);
                        echo "\" placeholder=\"Key\" />
                                        <span>=</span>
                                        <input type=\"text\" class=\"value\" placeholder=\"";
                        // line 226
                        if ($this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "dataType")) {
                            echo "[";
                            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "dataType"), "html", null, true);
                            echo "] ";
                        }
                        if ($this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "format")) {
                            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "format"), "html", null, true);
                        }
                        if ($this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "description")) {
                            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "description"), "html", null, true);
                        } else {
                            echo "Value";
                        }
                        echo "\" ";
                        if ($this->getAttribute((isset($context["infos"]) ? $context["infos"] : null), "default", array(), "any", true, true)) {
                            echo " value=\"";
                            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["infos"]) ? $context["infos"] : $this->getContext($context, "infos")), "default"), "html", null, true);
                            echo "\" ";
                        }
                        echo "/> <span class=\"remove\">-</span>
                                    </p>
                                ";
                    }
                    // line 229
                    echo "                                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['name'], $context['infos'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 230
                echo "                                <button class=\"add\">New parameter</button>
                            ";
            }
            // line 232
            echo "
                        </fieldset>

                        <fieldset class=\"headers\">
                            <legend>Headers</legend>

                            ";
            // line 238
            if ((isset($context["acceptType"]) ? $context["acceptType"] : $this->getContext($context, "acceptType"))) {
                // line 239
                echo "                                <p class=\"tuple\">
                                    <input type=\"text\" class=\"key\" value=\"Accept\" />
                                    <span>=</span>
                                    <input type=\"text\" class=\"value\" value=\"";
                // line 242
                echo twig_escape_filter($this->env, (isset($context["acceptType"]) ? $context["acceptType"] : $this->getContext($context, "acceptType")), "html", null, true);
                echo "\" /> <span class=\"remove\">-</span>
                                </p>
                            ";
            }
            // line 245
            echo "
                            <p class=\"tuple\">
                                <input type=\"text\" class=\"key\" placeholder=\"Key\" />
                                <span>=</span>
                                <input type=\"text\" class=\"value\" placeholder=\"Value\" /> <span class=\"remove\">-</span>
                            </p>

                            <button class=\"add\">New header</button>
                        </fieldset>

                        <fieldset class=\"request-content\">
                            <legend>Content</legend>

                            <textarea class=\"content\" placeholder=\"Content set here will override the parameters that do not match the url\"></textarea>

                            <p class=\"tuple\">
                                <input type=\"text\" class=\"key content-type\" value=\"Content-Type\" disabled=\"disabled\" />
                                <span>=</span>
                                <input type=\"text\" class=\"value\" placeholder=\"Value\" />
                                <button class=\"set-content-type\">Set header</button> <small>Replaces header if set</small>
                            </p>
                        </fieldset>

                        <div class=\"buttons\">
                            <input type=\"submit\" value=\"Try!\" />
                        </div>
                    </form>

                    <script type=\"text/x-tmpl\" class=\"tuple_template\">
                    <p class=\"tuple\">
                        <input type=\"text\" class=\"key\" placeholder=\"Key\" />
                        <span>=</span>
                        <input type=\"text\" class=\"value\" placeholder=\"Value\" /> <span class=\"remove\">-</span>
                    </p>
                    </script>

                    <div class=\"result\">
                        <h4>Request URL</h4>
                        <pre class=\"url\"></pre>

                        <h4>Response Headers&nbsp;<small>[<a href=\"\" class=\"to-expand\">Expand</a>]</small></h4>
                        <pre class=\"headers to-expand\"></pre>

                        <h4>Response Body&nbsp;<small>[<a href=\"\" class=\"to-raw\">Raw</a>]</small></h4>
                        <pre class=\"response prettyprint\"></pre>
                    </div>
                </div>
            ";
        }
        // line 293
        echo "        </div>
    </div>
</li>
";
    }

    public function getTemplateName()
    {
        return "NelmioApiDocBundle::method.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  671 => 293,  621 => 245,  615 => 242,  610 => 239,  608 => 238,  600 => 232,  596 => 230,  590 => 229,  566 => 226,  561 => 224,  558 => 223,  555 => 222,  551 => 221,  548 => 220,  545 => 219,  542 => 218,  523 => 215,  518 => 213,  515 => 212,  511 => 211,  508 => 210,  505 => 209,  502 => 208,  483 => 205,  478 => 203,  475 => 202,  471 => 201,  454 => 196,  451 => 195,  449 => 194,  444 => 191,  439 => 189,  436 => 188,  434 => 187,  431 => 186,  426 => 183,  417 => 179,  408 => 177,  404 => 176,  396 => 173,  393 => 172,  389 => 171,  378 => 162,  376 => 161,  373 => 160,  368 => 157,  359 => 154,  355 => 153,  351 => 152,  344 => 150,  340 => 149,  327 => 138,  325 => 137,  322 => 136,  317 => 133,  311 => 132,  305 => 129,  301 => 128,  297 => 127,  293 => 126,  289 => 125,  286 => 124,  283 => 123,  279 => 122,  265 => 110,  263 => 109,  260 => 108,  255 => 105,  246 => 101,  237 => 98,  233 => 97,  230 => 96,  226 => 95,  220 => 92,  217 => 91,  213 => 90,  202 => 81,  200 => 80,  197 => 79,  192 => 76,  183 => 73,  179 => 72,  175 => 71,  171 => 70,  168 => 69,  164 => 68,  151 => 57,  149 => 56,  146 => 55,  134 => 51,  131 => 50,  126 => 48,  123 => 47,  121 => 46,  111 => 40,  109 => 39,  99 => 36,  94 => 33,  88 => 31,  86 => 30,  79 => 26,  74 => 23,  72 => 22,  68 => 20,  62 => 18,  59 => 17,  53 => 14,  50 => 13,  44 => 9,  42 => 8,  36 => 5,  26 => 2,  19 => 1,  520 => 44,  513 => 403,  510 => 402,  506 => 400,  501 => 398,  496 => 397,  479 => 383,  474 => 382,  468 => 200,  466 => 199,  347 => 151,  341 => 260,  335 => 258,  333 => 257,  273 => 200,  139 => 53,  137 => 67,  115 => 42,  110 => 45,  108 => 44,  104 => 42,  101 => 41,  92 => 36,  89 => 35,  85 => 33,  76 => 24,  71 => 27,  67 => 26,  56 => 21,  47 => 17,  39 => 12,  33 => 9,  20 => 1,  161 => 32,  157 => 30,  154 => 29,  136 => 52,  122 => 23,  119 => 22,  102 => 21,  96 => 38,  90 => 15,  84 => 13,  82 => 32,  78 => 30,  60 => 22,  55 => 15,  52 => 6,  49 => 5,  31 => 4,  28 => 7,);
    }
}
