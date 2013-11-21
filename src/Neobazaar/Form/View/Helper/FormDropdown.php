<?php

namespace Neobazaar\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\Element\Select as SelectElement;
use Zend\Form\Exception;
use Zend\Stdlib\ArrayUtils;
use Zend\Form\View\Helper\AbstractHelper;

class FormDropdown extends AbstractHelper
{
    /**
     * Attributes valid for the current tag
     *
     * Will vary based on whether a select, option, or optgroup is being rendered
     *
     * @var array
     */
    protected $validTagAttributes;

    /**
     * Attributes valid for select
     *
     * @var array
     */
    protected $validSelectAttributes = array(
        'name'      => true,
        'autofocus' => true,
        'disabled'  => true,
        'form'      => true,
        'multiple'  => true,
        'required'  => true,
        'size'      => true
    );

    /**
     * Attributes valid for options
     *
     * @var array
     */
    protected $validOptionAttributes = array(
        'disabled' => true,
        'selected' => true,
        'label'    => true,
        'value'    => true,
    );

    /**
     * Attributes valid for option groups
     *
     * @var array
     */
    protected $validOptgroupAttributes = array(
        'disabled' => true,
        'label'    => true,
    );

    protected $translatableAttributes = array(
        'label' => true,
    );

    /**
     * Separator string for captcha and inputs
     *
     * @var string
     */
    protected $separator = '';

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|FormSelect
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * Render a form <select> element from the provided $element
     *
     * @param  ElementInterface $element
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        if (!$element instanceof SelectElement) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type Zend\Form\Element\Select',
                __METHOD__
            ));
        }

        $name   = $element->getName();
        if (empty($name) && $name !== 0) {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }

        $options = $element->getValueOptions();

        if (($emptyOption = $element->getEmptyOption()) !== null) {
            $options = array('' => $emptyOption) + $options;
        }

        $attributes = $element->getAttributes();
        $value      = $this->validateMultiValue($element->getValue(), $attributes);

        $attributes['name'] = $name;
        if (array_key_exists('multiple', $attributes) && $attributes['multiple']) {
            $attributes['name'] .= '[]';
        }
        $this->validTagAttributes = $this->validSelectAttributes;

        $figlet =  sprintf(
            '<div %s>
				<button class="btn btn-primary" type="button">' . $element->getLabel() . '</button>
				<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" type="button"><span class="caret"></span></button>
				<ul class="dropdown-menu">
        			%s
        		</ul>
			</div>',
			$this->createAttributesString($attributes),
            $this->renderOptions($options, $value)
        );

        $separator = $this->getSeparator();

        $captchaInput = $this->renderInputs($element);
        
        $pattern = '%s%s%s';
        return sprintf($pattern, $figlet, $separator, $captchaInput);
    }

    /**
     * Render an array of options
     *
     * Individual options should be of the form:
     *
     * <code>
     * array(
     *     'value'    => 'value',
     *     'label'    => 'label',
     *     'disabled' => $booleanFlag,
     *     'selected' => $booleanFlag,
     * )
     * </code>
     *
     * @param  array $options
     * @param  array $selectedOptions Option values that should be marked as selected
     * @return string
     */
    public function renderOptions(array $options, array $selectedOptions = array())
    {
        $template      = '<li %s><a href="#">%s</a></li>';
        $optionStrings = array();
        $escapeHtml    = $this->getEscapeHtmlHelper();

        foreach ($options as $key => $optionSpec) {
            $value    = '';
            $label    = '';
            $selected = false;
            $disabled = false;

            if (is_scalar($optionSpec)) {
                $optionSpec = array(
                    'label' => $optionSpec,
                    'value' => $key
                );
            }

            if (isset($optionSpec['options']) && is_array($optionSpec['options'])) {
                $optionStrings[] = $this->renderOptgroup($optionSpec, $selectedOptions);
                continue;
            }

            if (isset($optionSpec['value'])) {
                $value = $optionSpec['value'];
            }
            if (isset($optionSpec['label'])) {
                $label = $optionSpec['label'];
            }
            if (isset($optionSpec['selected'])) {
                $selected = $optionSpec['selected'];
            }
            if (isset($optionSpec['disabled'])) {
                $disabled = $optionSpec['disabled'];
            }

            if (ArrayUtils::inArray($value, $selectedOptions)) {
                $selected = true;
            }

            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate(
                    $label, $this->getTranslatorTextDomain()
                );
            }

            $attributes = compact('value', 'selected', 'disabled');

            if (isset($optionSpec['attributes']) && is_array($optionSpec['attributes'])) {
                $attributes = array_merge($attributes, $optionSpec['attributes']);
            }

            $this->validTagAttributes = $this->validOptionAttributes;
            $optionStrings[] = sprintf(
                $template,
                $this->createAttributesString($attributes),
                $escapeHtml($label)
            );
        }

        return implode("\n", $optionStrings);
    }

    /**
     * Render an optgroup
     *
     * See {@link renderOptions()} for the options specification. Basically,
     * an optgroup is simply an option that has an additional "options" key
     * with an array following the specification for renderOptions().
     *
     * @param  array $optgroup
     * @param  array $selectedOptions
     * @return string
     */
    public function renderOptgroup(array $optgroup, array $selectedOptions = array())
    {
        $template = '<optgroup%s>%s</optgroup>';

        $options = array();
        if (isset($optgroup['options']) && is_array($optgroup['options'])) {
            $options = $optgroup['options'];
            unset($optgroup['options']);
        }

        $this->validTagAttributes = $this->validOptgroupAttributes;
        $attributes = $this->createAttributesString($optgroup);
        if (!empty($attributes)) {
            $attributes = ' ' . $attributes;
        }

        return sprintf(
            $template,
            $attributes,
            $this->renderOptions($options, $selectedOptions)
        );
    }

    /**
     * Ensure that the value is set appropriately
     *
     * If the element's value attribute is an array, but there is no multiple
     * attribute, or that attribute does not evaluate to true, then we have
     * a domain issue -- you cannot have multiple options selected unless the
     * multiple attribute is present and enabled.
     *
     * @param  mixed $value
     * @param  array $attributes
     * @return array
     * @throws Exception\DomainException
     */
    protected function validateMultiValue($value, array $attributes)
    {
        if (null === $value) {
            return array();
        }

        if (!is_array($value)) {
            return (array) $value;
        }

        if (!isset($attributes['multiple']) || !$attributes['multiple']) {
            throw new Exception\DomainException(sprintf(
                '%s does not allow specifying multiple selected values when the element does not have a multiple attribute set to a boolean true',
                __CLASS__
            ));
        }

        return $value;
    }
    
    protected function renderInputs(ElementInterface $element)
    {
    	$name = $element->getName();
    	if ($name === null || $name === '') {
    		throw new Exception\DomainException(sprintf(
    				'%s requires that the element has an assigned name; none discovered',
    				__METHOD__
    		));
    	}
    
    	$attributes = $element->getAttributes();
//     	$captcha = $element->getCaptcha();
    
//     	if ($captcha === null || !$captcha instanceof CaptchaAdapter) {
//     		throw new Exception\DomainException(sprintf(
//     				'%s requires that the element has a "captcha" attribute implementing Zend\Captcha\AdapterInterface; none found',
//     				__METHOD__
//     		));
//     	}
    
    	$hidden = $this->renderHidden($element, $attributes);
    
    	return $hidden;
    }

    /**
     * Render the hidden input with the captcha identifier
     *
     * @param  CaptchaAdapter $captcha
     * @param  array          $attributes
     * @return string
     */
    protected function renderHidden(\Zend\Form\Element\Select $element, array $attributes)
    {
        $attributes['type']  = 'select';
        $attributes['name'] .= '[id]';

//         if (isset($attributes['id'])) {
//             $attributes['id'] .= '-hidden';
//         }

        if (method_exists($element, 'getId')) {
            $attributes['value'] = $element->getId();
        } elseif (array_key_exists('value', $attributes)) {
            if (is_array($attributes['value']) && array_key_exists('id', $attributes['value'])) {
                $attributes['value'] = $attributes['value']['id'];
            }
        }
        $closingBracket      = $this->getInlineClosingBracket();
        
        $hidden              = sprintf(
            '<input %s%s',
            $this->createAttributesString($attributes),
            $closingBracket
        );

        return $hidden;
    }

    /**
     * Set separator string for captcha and inputs
     *
     * @param  string $separator
     * @return AbstractWord
     */
    public function setSeparator($separator)
    {
        $this->separator = (string) $separator;
        return $this;
    }

    /**
     * Get separator for captcha and inputs
     *
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }
}
