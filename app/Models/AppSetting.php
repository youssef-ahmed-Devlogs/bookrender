<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'label', 'value', 'status'];

    public function setSetting(string $key, string $value): bool
    {
        return $this->where('key', $key)->update(['value' => $value]);
    }
}
