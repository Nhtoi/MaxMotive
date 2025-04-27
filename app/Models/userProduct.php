<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class userProduct
{
    public static function getAll()
    {
        return DB::select("SELECT * FROM products");
    }

    public static function search($term)
    {
        return DB::select("
            SELECT * FROM products
            WHERE name ILIKE ? OR category ILIKE ?
        ", ["%$term%", "%$term%"]);
    }

    public static function searchByName($term)
    {
        return DB::select("
            SELECT * FROM products
            WHERE name ILIKE ?
        ", ["%$term%"]);
    }

    public static function find($id)
    {
        return DB::selectOne("SELECT * FROM products WHERE id = ?", [$id]);
    }
}
