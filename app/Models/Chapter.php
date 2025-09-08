<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $fillable = [ 'project_id' , 'title', 'subtitle', 'license', 'doi' , 'content'];
    public function book(){
        return $this->belongsTo(Project::class);
    }
}
