<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'productName','amountAvailable', 'cost', 'sellerId'
    ];

    public function amount($amount)
    {
        $this->amountAvailable -= $amount;
        return $this->save();
    }

    public function saveProduct($data, $user){
        $this->productName = strip_tags($data['productName']);
        $this->amountAvailable = strip_tags($data['amountAvailable']);
        $this->cost = $data['cost'];
        $this->sellerId = $user->id;
        return $this->save();
    }

    public function updateProduct($data)
    {
        $this->amountAvailable = strip_tags($data['amountAvailable']);
        $this->cost = $data['cost'];
        return $this->save();
    }
}
