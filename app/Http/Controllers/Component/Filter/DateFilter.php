<?php

namespace App\Http\Controllers\Component\Filter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DateFilter implements Filter
{
    public function __construct(
        private string  $fieldName,
        private ?string $name = null,
    )
    {
        if ($this->name === null) {
            $this->name = $this->fieldName;
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }


    public function template(): string
    {
        return 'lib.filters.date_filter';
    }

    public function isEnable(): bool
    {
        $request = request();
        if(!$request->query->has('filters')) {
            return false;
        }
        $filters = $request->query->get('filters');
        if(!isset($filters[$this->name])) {
            return false;
        }
        $filterData = $filters[$this->name];

        switch ($filterData['mode']) {
            case \App\Http\Controllers\Filters\DateFilter::MODE_TODAY:
                return true;
            case \App\Http\Controllers\Filters\DateFilter::MODE_RANGE:
                return !empty($filterData['start']) || !empty($filterData['end']);
            default:
                return false;
        }
    }

    public function apply(Builder $query, mixed $data): void
    {
        if (empty($data)) {
            return;
        }

        $currentDate = new \DateTime();

        switch ($data['mode']) {
            case '':
                break;
            case \App\Http\Controllers\Filters\DateFilter::MODE_TODAY:
                $query->where($this->fieldName, '>=', $currentDate->format('Y-m-d') . ' 00:00:00')
                    ->where($this->fieldName, '<=', $currentDate->format('Y-m-d') . ' 23:59:59');
                break;
            case \App\Http\Controllers\Filters\DateFilter::MODE_RANGE:
                if (isset($data['start']) && $data['start'] !== '' && $data['start'] !== null) {
                    $query->where($this->fieldName, '>=', $data['start'] . ' 00:00:00');
                }
                if (isset($data['end']) && $data['end'] !== '' && $data['end'] !== null) {
                    $query->where($this->fieldName, '<=', $data['end'] . ' 23:59:59');
                }
                break;
            default:
                throw new \Exception('Invalid mode');
        }
    }


    public function templateData(Request $request): array
    {
        if (!$request->query->has('filters')) {
            return [
                'mode' => null,
                'start' => null,
                'end' => null
            ];
        }
        $filters = $request->query->get('filters');
        if (!isset($filters[$this->name])) {
            return [
                'mode' => null,
                'start' => null,
                'end' => null
            ];
        }
        return $filters[$this->name];
    }
}
