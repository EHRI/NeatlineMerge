<?php
/**
 * NeatlineMerge
 *
 * @copyright Copyright 2017 King's College London Department of Digital Humanities
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

include_once dirname(dirname(__FILE__)) . '/forms/NeatlineMerge_MergeForm.php';

/**
 * NeatlineMerge controller
 *
 * @package NeatlineMerge
 */
class NeatlineMerge_IndexController extends Omeka_Controller_AbstractActionController
{

    public function init()
    {
        // Set the model class so this controller can perform some functions,
        // such as $this->findById()
        $this->_helper->db->setDefaultModelName('NeatlineExhibit');
    }

    public function indexAction()
    {
        $form = new NeatlineMerge_MergeForm();
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if (!$form->isValid($_POST)) {
                $this->_helper->_flashMessenger(__('There was an error on the form. Please try again.'), 'error');
                return;
            }

            $this->_mergeItems(
                $form->getValue('title'),
                $form->getValue('slug'),
                $form->getValue('items'));

            $this->_helper->_flashMessenger(__('Item\'s merged successfully.'), 'success');
        }
    }

    private function _mergeItems($name, $slug, $itemIds)
    {
        $item1 = get_db()->getTable('NeatlineExhibit')->find(array_shift($itemIds));
        $exhibit = clone $item1;
        $exhibit->title = $name;
        $exhibit->slug = $slug;
        $exhibit->id = null;
        $exhibit->modified = null;
        $exhibit->added = null;
        $exhibit->published = null;
        $exhibit->save($throwIfInvalid = true);

        foreach ($itemIds as $id) {
            $item = get_db()->getTable('NeatlineExhibit')->find($id);
            $this->_cloneRecords($item, $exhibit);
        }
    }

    private function _cloneRecords(NeatlineExhibit $from, NeatlineExhibit $to)
    {
        foreach (get_db()->getTable('NeatlineRecord')->findBy(['exhibit_id' => $from->id]) as $t) {
            $record = clone $t;
            $record->id = null;
            $record->exhibit_id = $to->id;
            $record->save();
        }
    }
}
