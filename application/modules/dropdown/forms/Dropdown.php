<?php class Dropdown_Form_Dropdown extends Zend_Form {


		
	

	public function init() 
	{

		$vtype=array('Alpha','StringLength');
		$formfield = new App_Form_Field ();

// 	$fieldtype,$fieldname,$table,$columnname,$cssname,$labelname,$required,$validationtype,$min,$max,$rows,$cols,$decorator,$value

		
	
        $name = $formfield->field('Text','name','','','','name',true,'','','','','',1,0);
        $table_name = $formfield->field('Hidden','table_name','','','','',true,'','','','','',1,0);
        $id = $formfield->field('Hidden','id','','','','',true,'','','','','',1,0);
        $attr = $formfield->field('Hidden','attr','','','','',true,'','','','','',1,0);
        $description = $formfield->field('Text','description','','','','Description',true,'','','','','',1,0);


        

        // Hidden Feilds 
      

					
            $this->addElements(array($name,$table_name,$id,$attr,$description));
    }
}
