<?php

namespace App\Models\Component;

use App\Utils\DateUtils;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $number
 * @property ?string $title
 * @property \DateTime $date
 * @property string $file_path
 */
class Sz extends Model
{
    protected $table = 'sz';

    protected $fillable = [
        "number",
        "date",
        "title",
    ];

    public function label()
    {
        return sprintf('СЗ № %s%s от %s',
            $this->number,
            $this->title !== null ? ' ' . $this->title : '',
            DateUtils::dateToDisplayFormat($this->date)
        );
    }
}
