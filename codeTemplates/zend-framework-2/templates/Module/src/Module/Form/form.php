<?php
/** @var string $MODULE */
/** @var string $FORMNAME */
/** @var array $FIELDSTITLESE */

echo
'<?php

namespace '.$MODULE.'\Form;

use Application\Form\BaseForm;

class '.$FORMNAME.' extends BaseForm
{
	protected function _getElements()
	{
		$elements = array(
';
if (isset($FIELDSTITLESE['id'])) {
	unset($FIELDSTITLESE['id']);
	echo
'			\'id\' => array(
				\'type\' => \'hidden\',
				\'required\' => false,
				\'filters\' => array(
					array(\'name\' => \'Int\'),
				)
			),
';
}

foreach ($FIELDSTITLESE as $field => $title) {
echo
'			\''.$field.'\' => array(
				\'type\' => \'text\',
				\'required\' => true,
				\'options\' => array(
					\'label\' => \''.$title.'\',
				),
				\'filters\' => array(
					array(\'name\' => \'StringTrim\'),
//					array(\'name\' => \'StripTags\'),
				),
				\'validators\' => array(
					array(
						\'name\'    => \'StringLength\',
						\'options\' => array(\'min\' => 0, \'max\' => 65000, \'encoding\' => \'UTF-8\'),
					),
				),
			),
';
}
echo
'		if (!$this->_existsRecord) {
			unset($elements[\'id\']);
		}

		return $elements;
	}

}

';