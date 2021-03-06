<?php
/*
############################################################################
#  This file is part of OurBank.
############################################################################
#  OurBank is free software: you can redistribute it and/or modify
#  it under the terms of the GNU Affero General Public License as
#  published by the Free Software Foundation, either version 3 of the
#  License, or (at your option) any later version.
############################################################################
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU Affero General Public License for more details.
############################################################################
#  You should have received a copy of the GNU Affero General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
############################################################################
*/
class User_Indexcontroller extends Zend_Controller_Action
{
    public function init() 
    {
        $this->view->pageTitle='User';
       $storage = new Zend_Auth_Storage_Session();
       $data = $storage->read();
       if(!$data){
               $this->_redirect('index/login'); // once session get expired it will redirect to Login page
       }

       $sessionName = new Zend_Session_Namespace('ourbank');
       $userid=$this->view->createdby = $sessionName->primaryuserid; // get the stored session id

       $login=new App_Model_Users();
       $loginname=$login->username($userid);
       foreach($loginname as $loginname) {
           $this->view->username=$loginname['username']; // get the user name
       }
 
    //         if (($this->view->globalvalue[0]['id'] == 0)) {
    //              $this->_redirect('index/logout');
    //         }
        $this->view->adm = new App_Model_Adm();

    }

    public function indexAction() 

    {
        $this->view->title = "User";
        // calling form
        $searchForm = new User_Form_Search();
        $this->view->form = $searchForm;
        //calling  model
        $userdetail = new User_Model_User();
        $result = $userdetail->getUserDetails();
        //listing designation
                $designation = $this->view->adm->viewRecord("ourbank_master_designation","id","DESC");
        foreach($designation as $designation){
                        $searchForm->designation->addMultiOption($designation['id'],$designation['name']);
                }
        //listing grants
        $grant = $this->view->adm->viewRecord("ourbank_grant","id","DESC");
        foreach($grant as $grant){
            $searchForm->grant_id ->addMultiOption($grant['id'],$grant['name']);
        }
        //listing office
        $user = new User_Model_User();
        $bankname = $this->view->adm->viewRecord("ourbank_office","id","DESC");
        foreach($bankname as $bankname){
            $searchForm->bank->addMultiOption($bankname['id'],$bankname['name']);
        }
        //pagination
        $page = $this->_getParam('page',1);
        $paginator = Zend_Paginator::factory($result);
        $paginator->setItemCountPerPage(5);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
        //search action
        if ($this->_request->isPost() && $this->_request->getPost('Search')){
            if ($this->_request->isPost()){
                if ($searchForm->isValid($this->_request->getPost())){
                    $result = $userdetail->userSearch($searchForm->getValues());
                    $page = $this->_getParam('page',1);
                    $paginator = Zend_Paginator::factory($result);
                    $paginator->setItemCountPerPage(5);
                    $paginator->setCurrentPageNumber($page);
                    $this->view->paginator = $paginator;
                } 
                //error message
                if (!$result) {
                        echo "<font color='RED'>Records Not Found Try Again...</font>";
                }
            }
        }

    }

    //add action
    public function addAction() 
    {
        //calling add form
        $addForm = new User_Form_User();
        $this->view->form=$addForm;
        //listing designation
        $designation = $this->view->adm->viewRecord("ourbank_master_designation","id","DESC");
        foreach($designation as $designation){
                        $addForm->designation->addMultiOption($designation['id'],$designation['name']);
                }
        //listing institution
        $bankname = $this->view->adm->viewRecord("ourbank_office","id","DESC");
        foreach($bankname as $bankname){
                $addForm->bank_id->addMultiOption($bankname['id'],$bankname['name']);
                }
        //listing grants
                $grant = $this->view->adm->viewRecord("ourbank_grant","id","DESC");
                foreach($grant as $grant){
                        $addForm->grant_id ->addMultiOption($grant['id'],$grant['name']);
                }
$department = $this->view->adm->viewRecord("ourbank_master_department","id","DESC");
        foreach($department as $department){
            $addForm->department->addMultiOption($department['id'],$department['name']);
        }
        //listing gender
                $gender = $this->view->adm->viewRecord("ourbank_master_gender","id","DESC");
                foreach($gender as $gender){
                        $addForm->gender->addMultiOption($gender['id'],$gender['name']);
                }
        //submit action
        if ($this->_request->isPost() && $this->_request->getPost('Submit')) { 
            $formData = $this->_request->getPost();
            if ($addForm->isValid($formData)) { 
                    //adding datas to database
                $result = $this->view->adm->addRecord("ourbank_user",$addForm->getValues());
                $this->_redirect('user/index');
            }
    }}
        //edit action
    public function edituserdetailAction() 
    {
        //calling the form
        $addForm = new User_Form_User();
        $this->view->form=$addForm;
        //listing designation
        $designation = $this->view->adm->viewRecord("ourbank_master_designation","id","DESC");
        foreach($designation as $designation){
            $addForm->designation->addMultiOption($designation['id'],$designation['name']);
        }
        //;isting institution
        $bankname = $this->view->adm->viewRecord("ourbank_office","id","DESC");
        foreach($bankname as $bankname){
            $addForm->bank_id->addMultiOption($bankname['id'],$bankname['name']);
        }
 $department = $this->view->adm->viewRecord("ourbank_master_department","id","DESC");
        foreach($department as $department){
            $addForm->department->addMultiOption($department['id'],$department['name']);
        }
        //listing grants
        $grant = $this->view->adm->viewRecord("ourbank_grant","id","DESC");
        foreach($grant as $grant){
            $addForm->grant_id ->addMultiOption($grant['id'],$grant['name']);
        }
        //listing gender
        $gender = $this->view->adm->viewRecord("ourbank_master_gender","id","DESC");
        foreach($gender as $gender){
            $addForm->gender->addMultiOption($gender['id'],$gender['name']);
        }
        $this->view->title = "Edit User";
                        //Acl
        //         $access = new App_Model_Access();
        //         $checkaccess = $access->accessRights('User',$this->view->globalvalue[0]['name'],'edituserdetail');
        //        	if (($checkaccess != NULL)) {
        //getting the id
        $id = $this->_getParam('id');
        $this->view->id = $id;
        //displaying datas to be edited 
        $userdetails = $this->view->adm->editRecord("ourbank_user",$id);
        $addForm->populate($userdetails[0]);
        //submit action
        if ($this->_request->isPost() && $this->_request->getPost('Update')) {  
                $id = $this->_getParam('id');
                $formData = $this->_request->getPost();
                if ($addForm->isValid($formData)) {
                    //editing record
                    $previousdata = $this->view->adm->editRecord("ourbank_user",$id);
                    //echo  "<pre>"; print_r($previousdata);
                    $this->view->adm->updateLog("ourbank_user_log",$previousdata[0],$id);
                    $this->view->adm->updateRecord("ourbank_user",$id,$addForm->getValues());
                                                 $this->_redirect('user');
                }
        // } else {
        //            $this->_redirect('index/index');
        }
    }
    public function commonviewAction() 
    {
        $this->view->title = "View user";
                        //Acl
        //         $access = new App_Model_Access();
        //         $checkaccess = $access->accessRights('User',$this->view->globalvalue[0]['name'],'commonviewAction');
        //        	if (($checkaccess != NULL)) {
        //calling the form
        $SectForm = new Sectors_Form_Search();
        $this->view->form = $SectForm;
        $id = (int)$this->_getParam('id');
        $this->view->id = $id;
        //calling the model
        $userdetails=new User_Model_User();
        $user_details=$userdetails->getuser($id);
        $module=$userdetails->getmodule('User');
        foreach($module as $module_id){ }
        $this->view->mod_id=$module_id['parent'];
        $this->view->sub_id=$module_id['module_id'];
        $this->view->userdetails = $user_details;
        $this->view->address = $this->view->adm->getModule("address",$id,"Individual");
        $personal=$userdetails->getpersonal($id);
        $this->view->personal=$personal;
        //         } else {
        //            		 $this->_redirect('index/error');
        // 		}
        // 	}
        // /
    }
    public function deleteAction() 
    {
        $delform = new User_Form_Delete();
        $this->view->deleteform = $delform;
        $userdetails=new User_Model_User();
        $id = (int)$this->_getParam('id');
        $this->view->id = $id;
        $user_details=$userdetails->getuser($id); 
        $this->view->userdetails = $user_details;
        if ($this->_request->isPost() && $this->_request->getPost('Delete')) {  
            $redirect = $this->view->adm->deleteRecord("ourbank_user",$id);
            $this->_redirect('/user');
        }
    }
}

 

	