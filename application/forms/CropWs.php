<?php

class Application_Form_CropWs extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

        $name = $this->createElement('text', 'name');
        $name->setLabel('Enter crop name:');
        $name->setRequired(TRUE);
        $name->setAttrib('size', 30);
        $this->addElement($name);

        $shelf_life_air = $this->createElement('text', 'shelf_life_air');
        $shelf_life_air->setLabel('Enter Air Shelf Life:');
        $shelf_life_air->setRequired(TRUE);
        $shelf_life_air->setAttrib('size', 30);
        $this->addElement($shelf_life_air);

        $shelf_life_fridge = $this->createElement('text', 'shelf_life_fridge');
        $shelf_life_fridge->setLabel('Enter Fridge Shelf Life:');
        $shelf_life_fridge->setRequired(TRUE);
        $shelf_life_fridge->setAttrib('size', 30);
        $this->addElement($shelf_life_fridge);

        $shelf_life_freezer = $this->createElement('text', 'shelf_life_freezer');
        $shelf_life_freezer->setLabel('Enter Freezer Shelf Life:');
        $shelf_life_freezer->setRequired(TRUE);
        $shelf_life_freezer->setAttrib('size', 30);
        $this->addElement($shelf_life_freezer);

        $date_available = $this->createElement('text', 'date_available');
        $date_available->setLabel('Date crop will be available (mm-dd-yyyy):');
        $date_available->setRequired(TRUE);
        $date_available->addValidator(new Zend_Validate_Date('MM-DD-YYYY'));
        $date_available->setAttrib('size', 30);
        $this->addElement($date_available);

        $this->addElement('submit', 'submit', array('label' => 'Submit'));

        $id = $this->createElement('hidden', 'id');
        $this->addElement($id);
     }


}

