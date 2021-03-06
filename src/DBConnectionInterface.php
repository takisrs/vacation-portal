<?php
namespace takisrs;

interface DBConnectionInterface
{
    public function query($query, $params = []);

    public function getConnection();
}