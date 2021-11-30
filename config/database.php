<?php

function getDatabaseConfig()
{
    return [
        "database" => [
            "dev" => [
                "url" => "mysql:host=localhost:3306;dbname=pzn_login_test",
                "username" => "root",
                "password" => ""
            ],
            "prod" => [
                "url" => "mysql:host=localhost:3306;db_name=pzn_login",
                "username" => "root",
                "password" => ""
            ]
        ]
    ];
}