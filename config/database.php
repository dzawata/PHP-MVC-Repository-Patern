<?php

function getDatabaseConfig()
{
    return [
        "database" => [
            "dev" => [
                "url" => "mysql:host=localhost:3306;dbname=db_dev",
                "username" => "root",
                "password" => ""
            ],
            "prod" => [
                "url" => "mysql:host=localhost:3306;db_name=db_prod",
                "username" => "root",
                "password" => ""
            ]
        ]
    ];
}