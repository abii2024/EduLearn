<?php

interface ORMinterface
{
    public function save();
    public function delete();
    public function getID();
    public static function findByID($id);
    public static function findAll();
}
