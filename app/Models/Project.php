<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'image',
        'author',
        'title',
        'second_title',
        'description',
        'treem_size',
        'page',
        'format',
        'bleed_file',
        'category',
        'chapter',
        'text_style',
        'font_size',
        'add_page_num',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function cover()
    {
        if ($this->image && file_exists("storage/{$this->image}")) {
            return asset("storage/{$this->image}");
        }

        return asset('assets/common/images/default.png');
    }
}
