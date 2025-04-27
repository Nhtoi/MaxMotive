<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Product
{
    public static function all($search = null)
    {
        if ($search) {
            return DB::select("
                SELECT * FROM products
                WHERE name ILIKE ? OR category ILIKE ?
            ", ["%$search%", "%$search%"]);
        } else {
            return DB::select("SELECT * FROM products");
        }
    }

    public static function find($id)
    {
        return DB::selectOne("SELECT * FROM products WHERE id = ?", [$id]);
    }

    public static function insert($data)
    {
        return DB::insert("
            INSERT INTO products (name, price, description, imageurl, category, stock_quantity, weight, flavor, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, now(), now())
        ", [
            $data['name'],
            $data['price'],
            $data['description'] ?? null,
            $data['imageurl'] ?? null,
            $data['category'] ?? null,
            $data['stock_quantity'],
            $data['weight'] ?? null,
            $data['flavor'] ?? null,
        ]);
    }

    public static function updateById($id, $data)
    {
        return DB::update("
            UPDATE products
            SET name = ?, price = ?, description = ?, imageurl = ?, category = ?, stock_quantity = ?, weight = ?, flavor = ?, updated_at = now()
            WHERE id = ?
        ", [
            $data['name'],
            $data['price'],
            $data['description'] ?? null,
            $data['imageurl'] ?? null,
            $data['category'] ?? null,
            $data['stock_quantity'],
            $data['weight'] ?? null,
            $data['flavor'] ?? null,
            $id,
        ]);
    }

    public static function deleteById($id)
    {
        return DB::delete("DELETE FROM products WHERE id = ?", [$id]);
    }

    public static function archiveById($id)
    {
        return DB::update("UPDATE products SET category = 'archived', updated_at = now() WHERE id = ?", [$id]);
    }

    public static function upsertMany($products)
    {
        $inserted = 0;

        foreach ($products as $product) {
            if (
                isset($product['id'], $product['name'], $product['price'], $product['description'], 
                      $product['imageurl'], $product['category'], $product['stock_quantity'])
            ) {
                DB::insert("
                    INSERT INTO products (id, name, price, description, imageurl, category, stock_quantity, weight, flavor, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, now(), now())
                    ON CONFLICT (id) 
                    DO UPDATE SET
                        name = EXCLUDED.name,
                        price = EXCLUDED.price,
                        description = EXCLUDED.description,
                        imageurl = EXCLUDED.imageurl,
                        category = EXCLUDED.category,
                        stock_quantity = EXCLUDED.stock_quantity,
                        weight = EXCLUDED.weight,
                        flavor = EXCLUDED.flavor,
                        updated_at = now()
                ", [
                    $product['id'],
                    $product['name'],
                    $product['price'],
                    $product['description'] ?? null,
                    $product['imageurl'] ?? null,
                    $product['category'] ?? null,
                    $product['stock_quantity'],
                    $product['weight'] ?? null,
                    $product['flavor'] ?? null,
                ]);

                $inserted++;
            }
        }

        return $inserted;
    }
}
