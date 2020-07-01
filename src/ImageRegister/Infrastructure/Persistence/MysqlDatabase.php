<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence;

use PDO;

final class MysqlDatabase
{
    public static function instancePDO(): PDO
    {
        return new PDO('mysql:host=mysql;dbname=db', 'root', 'password');
    }
}