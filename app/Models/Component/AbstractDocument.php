<?php

namespace App\Models\Component;

use App\Utils\DateUtils;
use Illuminate\Database\Eloquent\Model;

/*
* @property int $id
* @property string $number
* @property ?string $title
* @property \DateTime $date
* @property string $file_path
*/
abstract class AbstractDocument extends Model
{

    protected $fillable = [
        "number",
        "date",
        "title",
    ];

    public function label()
    {
        return sprintf('%s № %s%s от %s',
            $this->documentName(),
            $this->number,
            $this->title !== null ? ' ' . $this->title : '',
            DateUtils::dateToDisplayFormat($this->date)
        );
    }

    protected abstract function documentName(): string;
}
