<?php

class CommonView extends AbstractView
{
    /* @var $model CommonModel */
    public $model;

    public $content;

    public $head;
    public $menu;
    public $footer;

    public function showPage()
    {
        $parts_root = Core::$config['template_root'] . '_parts' . DIRECTORY_SEPARATOR;

        $t = new Template($parts_root . 'head.phtml');
        $this->head = $t->render();

        $t = new Template($parts_root . 'menu.phtml', $this->model);
        $this->menu = $t->render();

        $t = new Template($parts_root . 'footer.phtml');
        $this->footer = $t->render();

        $t = new Template(Core::$config['template_root'] . 'layout.phtml');
        $this->content = $t->render();
    }
}