<?php
/**
 * NeatlineMerge
 *
 * @copyright Copyright 2018 King's College London Department of Digital Humanities
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */


class NeatlineMerge_MergeForm extends Omeka_Form
{
    /**
     * @throws Zend_Form_Exception
     */
    public function init()
    {
        parent::init();

        $this->addElement('text', 'title', array(
            'label' => __('Title'),
            'description' => __('The name of the merged exhibit.'),
            'required' => true
        ));

        $this->addElement('text', 'slug', array(
            'label' => __('URL Slug'),
            'description' => __('The slug of the merged exhibit.'),
            'required' => true
        ));

        // The pick an item drop-down select:
        $select = $this->createElement('select', 'items', [
            'label' => __('Items'),
            'description' => __('Neatline items to merge'),
            'multiple' => 'multiple',
            'multiOptions' => get_table_options('NeatlineExhibit'),
            'required' => true,
            'size' => 20
        ]);
        $select->setRegisterInArrayValidator(false);
        $this->addElement($select);

        $this->addElement('submit', 'submit', [
            'label' => __('Merge Items')
        ]);

        $this->addDisplayGroup(['title', 'slug', 'items'], 'neatline-merge_info');
        $this->addDisplayGroup(['submit'], 'neatline-merge_submit');
    }
}