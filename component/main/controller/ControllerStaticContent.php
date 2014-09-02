<?php

class ControllerStaticContent extends Controller {

  /**
     * @var model
     * */
    public $model;

    public function IndexAction() {


if (isset($_REQUEST['id'])) {
$content=$this->model->GetItem($_REQUEST['id']);
$this->view_param['title']=$content['title'];
$this->view_param['content']=$content;

 $left_menu = new menu_model('galereya_rub', 'id');
            $modules=$left_menu->get_rub_tree($_REQUEST['id']);
        $this->view_param['menu'] = $modules;
$this->view_shablon['content']='static_content/static_content_view.phtml';

    }
    }

}