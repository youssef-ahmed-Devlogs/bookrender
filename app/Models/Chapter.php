<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'title',
        'subtitle',
        'license',
        'doi',
        'content',
        'type',
        'chapter_number',
    ];

    protected $casts = [
        'chapter_number' => 'integer',
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
