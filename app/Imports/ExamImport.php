<?php

namespace App\Imports;

use App\Entities\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExamImport implements ToModel, WithHeadingRow
{
    protected $exam;
    protected $type;

    public function __construct($exam, $type)
    {
        $this->exam = $exam;
        $this->type = $type;
    }

    public function model(array $row)
    {
        $content = null;
        $content_number = null;
        if (isset($row['noi_dung'])) {
            $content = $row['noi_dung'];
            $content_number = $row['thu_tu'];
        }
        if (!empty($row['noi_dung_cau_hoi'])){
            return new Question([
                'title' => $row['noi_dung_cau_hoi'],
                'a' => $row["a"],
                'b' => $row['b'],
                'c' => $row['c'],
                'd' => $row['d'],
                'answer' => $row['dap_an'],
                'note' => $row['giai_thich'],
                'exam_id' => $this->exam->id,
                'type' => $this->type,
                'content' => $content,
                'content_number' => $content_number,
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
