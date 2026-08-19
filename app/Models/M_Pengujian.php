<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\M_Nilai_Kombinasi;

class M_Pengujian extends Model
{
    protected $table = "tbl_pengujian";

    protected $fillable = [
        'kd_pengujian',
        'nama_penguji',
        'min_supp',
        'min_confidence',
        'execution_time_ms',
        'total_frequent_itemsets',
        'total_rules',
        'api_status'
    ];

    public function totalPolaProduk($kdPengujian, $confidence)
    {
        return M_Nilai_Kombinasi::where('kd_pengujian', $kdPengujian)
            ->where('confidence', '>=', $confidence)
            ->count();
    }
}
