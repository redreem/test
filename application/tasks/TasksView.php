<?php

class TasksView extends AbstractView
{
    /* @var $model TasksModel */
    public $model;

    public $tasks;
    public $pagination;

    public function setDefaultContent()
    {
        $t = new Template(Core::$config['template_root'] . '_parts' . DIRECTORY_SEPARATOR . 'pagination.phtml');
        $this->pagination = $t->render();

        if (!empty($this->model->tasks)) {

            $task_list_template = ($this->model->is_admin ? 'task_list_admin.phtml' : 'task_list_user.phtml');
        } else {

            $task_list_template = 'empty_task_list.phtml';
        }

        $t = new Template(Core::$config['template_root'] . '_parts' . DIRECTORY_SEPARATOR . $task_list_template);
        $this->tasks = $t->render();

        $t = new Template(Core::$config['template_root'] . 'tasks.phtml');
        $this->content = $t->render();
    }
}