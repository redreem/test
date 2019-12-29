<?php

class TasksController extends AbstractController
{
    /**
     * @var TasksView
     */
    public $view;

    protected function actDefault()
    {
        $this->view->setDefaultContent();
    }

    protected function actAdd()
    {
        $this->view->setDefaultContent();
    }

    protected function actEdit()
    {
        $this->view->setDefaultContent();
    }

    protected function actSetStatus()
    {
        $this->view->setDefaultContent();
    }

    protected function actLogin()
    {
        $this->view->setDefaultContent();
    }

    protected function actLogout()
    {
        $this->view->setDefaultContent();
    }
}