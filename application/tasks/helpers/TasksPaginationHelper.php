<?php

class TasksPaginationHelper
{
    public static function getPagesCount()
    {
        $sql = "
            select
                count(1) pages_cnt
            from " . Core::$config['db']['base'] . ".tasks t
        ";

        $query = Application::$db->query($sql);

        $row = $query->fetchAssocArray();

        return $row['pages_cnt'];
    }
}