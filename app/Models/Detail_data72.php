<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_data72 extends Model
{
    use HasFactory;

    public $table = 'detail_data72';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_user',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'id_agama',
        'foto_ktp',
        'umur'
    ];

    public function user()
    {
        return $this->belongsTo(User72::class, 'id_user', 'id');
    }

    public function agama()
    {
        return $this->belongsTo(Agama72::class, 'id_agama', 'id');
    }
}
