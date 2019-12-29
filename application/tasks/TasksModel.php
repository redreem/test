<?php

class TasksModel extends AbstractModel
{
    public $tasks;
    public $tasks_per_page = 10;
    public $tasks_page = 1;
    public $tasks_pages_cnt = 0;

    public $task_user_name;
    public $task_user_email;
    public $task_description;
    public $task_status;
    public $task_id;

    public $is_admin = false;
    public $bad_login = false;

    public $task_list_order_field = 'user_name';
    public $task_list_order_direct = 'asc';

    public $validate_pass = true;

    public $errors = [
        'user_name_empty'   => [
            'descr' => 'Не заполнено имя пользователя',
            'status' => false
        ],
        'email_empty'       => [
            'descr' => 'Не заполнен email',
            'status' => false
        ],
        'email_invalid'     => [
            'descr' => 'Не верный формат email',
            'status' => false
        ],
        'description_empty' => [
            'descr' => 'Не заполнено описание',
            'status' => false
        ],
    ];

    protected function dataProcess()
    {
        require_once Core::$config['application_root'] . 'tasks' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'TasksPaginationHelper.php';

        session_start();

        $this->getParams();
        $this->checkAdmin();

        switch (Application::$action) {

            default:
                $this->getTasks();
                $this->tasks_pages_cnt = ceil(TasksPaginationHelper::getPagesCount() / $this->tasks_per_page);
                break;

            case 'login':
                $this->adminLogin();
                break;

            case 'logout':
                $this->adminLogout();
                break;

            case 'add':
                if (!$this->addTask()) {

                    $this->getTasks();
                }
                break;

            case 'edit':

                if (!$this->editTaskDescription()) {

                    $this->getTasks();
                }
                break;

            case 'setstatus':

                $this->setStatus();
                break;
        }
    }

    protected function getParams()
    {
        $this->tasks_page               = isset($_REQUEST['tasks_page'])        ? (int)$_REQUEST['tasks_page'] : 1;
        $this->task_user_name           = isset($_REQUEST['task_user_name'])    ? trim($_REQUEST['task_user_name']) : '';
        $this->task_user_email          = isset($_REQUEST['task_user_email'])   ? trim($_REQUEST['task_user_email']) : '';
        $this->task_description         = isset($_REQUEST['task_description'])  ? trim($_REQUEST['task_description']) : '';
        $this->task_status              = isset($_REQUEST['task_status'])       ? (int)$_REQUEST['task_status'] : 1;
        $this->task_id                  = isset($_REQUEST['task_id'])           ? (int)$_REQUEST['task_id'] : 0;
        $this->task_list_order_field    = isset($_REQUEST['order_field'])       ? $_REQUEST['order_field'] : $this->task_list_order_field;
        $this->task_list_order_direct   = isset($_REQUEST['order_direct'])      ? $_REQUEST['order_direct'] : $this->task_list_order_direct;
    }

    protected function getTasks()
    {
        $sql = "
            select
                t.*, s.status_description
            from " . Core::$config['db']['base'] . ".tasks t
            join " . Core::$config['db']['base'] . ".d_task_status s on s.id = t.task_status
            order by " . $this->task_list_order_field . " " . $this->task_list_order_direct . "
            limit " . (($this->tasks_page - 1) * $this->tasks_per_page) . ", " . $this->tasks_per_page . "
        ";

        $query = Application::$db->query($sql);

        while ($row = $query->fetchAssocArray()) {

            $this->tasks[] = $row;
        }

        FE::addData('tasks_page', $this->tasks_page);
     }

    protected function XSSProtect($str)
    {
        /* TODO это метод, естественно, надо сделать более корректным, тут же просто сделал саму суть */

        $str = str_replace('<script>', '', $str);
        $str = str_replace('</script>', '', $str);

        $str = htmlentities($str, ENT_QUOTES, "UTF-8");
        $str = htmlspecialchars($str, ENT_QUOTES);

        return $str;
    }

    protected function validateUserName()
    {
        $this->task_user_name = $this->XSSProtect($this->task_user_name);

        if (empty($this->task_user_name)) {

            $this->validate_pass = false;
            $this->errors['user_name_empty']['status'] = true;
        }
    }

    protected function validateEmail()
    {
        if (empty($this->task_user_email)) {

            $this->validate_pass = false;
            $this->errors['email_empty']['status'] = true;

        } elseif (filter_var($this->task_user_email, FILTER_VALIDATE_EMAIL) === false) {

            $this->validate_pass = false;
            $this->errors['email_invalid']['status'] = true;
        }
    }

    protected function validateDescription()
    {
        $this->task_description = $this->XSSProtect($this->task_description);

        if (empty($this->task_description)) {

            $this->validate_pass = false;
            $this->errors['description_empty']['status'] = true;
        }
    }

    protected function error()
    {
        FE::addData('errors', $this->errors);
        FE::addData('error', 1);
        FE::addData('action', Application::$action);
    }

    protected function addTask()
    {
        $this->validateUserName();
        $this->validateEmail();
        $this->validateDescription();

        if (!$this->validate_pass) {

            $this->error();

            FE::addData('user_name', $this->task_user_name);
            FE::addData('user_email', $this->task_user_email);
            FE::addData('task_description', $this->task_description);

            return false;
        }

        $sql = "
            insert into " . Core::$config['db']['base'] . ".tasks 
            set
                user_name = '" . Application::$db->escape( $this->task_user_name ). "',
                user_email = '" . Application::$db->escape( $this->task_user_email ). "',
                task_status = 1,
                task_description = '" . Application::$db->escape( $this->task_description ). "',
                task_description_edited = 0
        ";

        Application::$db->query($sql);

        Application::redirect( '/tasks?' . $this->getFilterParamsURI());
    }

    protected function editTaskDescription()
    {
        $this->validateDescription();

        if (!$this->validate_pass) {

            $this->error();

            FE::addData('task_id', $this->task_id);
            return false;
        }

        $sql = "
            update " . Core::$config['db']['base'] . ".tasks 
            set
                task_description = '" . Application::$db->escape( $this->task_description ). "',
                task_description_edited = 1
            where 
                id = " . $this->task_id . "
        ";

        Application::$db->query($sql);

        Application::redirect( '/tasks?' . $this->getFilterParamsURI());
    }

    protected function setStatus()
    {
        $sql = "
            update " . Core::$config['db']['base'] . ".tasks 
            set
                task_status = " . $this->task_status . "
            where 
                id = " . $this->task_id . "
        ";

        Application::$db->query($sql);

        Application::redirect( '/tasks?' . $this->getFilterParamsURI());
    }

    public function getFilterParamsURI()
    {
        $uri = 'tasks_page=' . $this->tasks_page;
        $uri .= '&order_field=' . $this->task_list_order_field;
        $uri .= '&order_direct=' . $this->task_list_order_direct;

        return $uri;
    }

    protected function checkAdmin()
    {
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {

            $this->is_admin = true;

        } elseif (isset($_SESSION['bad_login']) && $_SESSION['bad_login'] === true) {

            FE::addData('bad_login', 1);

            $_SESSION['bad_login'] = false;

            session_destroy ();
            session_unset();
        }
    }

    protected function adminLogin()
    {
        if (
            (isset($_REQUEST['login']) && $_REQUEST['login'] == Core::$config['administrator']['login'])
            &&
            (isset($_REQUEST['password']) && $_REQUEST['password'] == Core::$config['administrator']['password'])
        ) {

            $_SESSION['is_admin'] = true;
            $_SESSION['bad_login'] = false;

        } else {

            $_SESSION['bad_login'] = true;
        }

        Application::redirect('/');
    }

    protected function adminLogout()
    {
        session_destroy ();
        session_unset();

        Application::redirect('/');
    }
}