<?php

namespace Pages\Form;

use Application\Form\BaseForm;

class EditPageForm extends BaseForm
{
	protected function _getElements()
	{
		$elements = array(

			'id' => array(
				'type' => 'hidden',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				)
			),
			'title' => array(
				'type' => 'text',
				'required' => true,
				'options' => array(
					'label' => 'Title',
				),
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array('min' => 2, 'max' => 250, 'encoding' => 'UTF-8'),
					),
				),
			),

			'url_name' => array(
				'type' => 'text',
				'required' => true,
				'options' => array(
					'label' => 'Url Slug',
				),
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'Regex',
						'options' => array('pattern' => '/^[\w\d-]+$/'),
					),
					array(
						'name'    => 'StringLength',
						'options' => array('min' => 1, 'max' => 250, 'encoding' => 'UTF-8'),
					),
				),
			),

			'content' => array(
				'type' => 'textarea',
				'required' => false,
				'options' => array(
					'label' => 'Content',
				),
				'attributes' => array(
					'class' => 'wysiwyg span8',
					'cols'  => 100,
					'rows'  => 15,
				),
				'filters' => array(
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array('max' => 65000, 'encoding' => 'UTF-8'),
					),
				),
			),

			'published' => array(
				'type' => 'checkbox',
				'required' => true,
				'options' => array(
					'label' => 'Published',
				),
			),

		);

		if (!$this->_existsRecord) {
			array_shift($elements);
		}

		return $elements;
	}

}
