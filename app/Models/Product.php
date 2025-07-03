<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Pastikan ini juga diimpor
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory; // Tambahkan ini jika belum ada

    // Tambahkan properti $fillable ini untuk mengizinkan mass assignment
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
    ];
}