<?php

namespace App\Console\Commands;

use App\Models\Component\Component;
use App\Models\Component\Component3dStatus;
use App\Models\Component\ComponentCalcStatus;
use App\Models\Component\ComponentDdStatus;
use App\Models\Component\ComponentManufactorStartWay;
use App\Models\Component\ComponentManufactorStatus;
use App\Models\Component\ComponentPurchaserStatus;
use App\Models\Component\ComponentSourceType;
use App\Models\Component\ComponentType;
use App\Models\Component\ComponentVersion;
use App\Models\Component\PhysicalObject;
use App\Models\User;
use Illuminate\Console\Command;

class ComponentLoadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:components {filePath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filePath = $this->argument('filePath');
        if (!file_exists($filePath)) {
            throw new \Exception(sprintf('File %s not exist', $filePath));
        }

        $iterator = $this->csvRecordIterator($filePath);
        foreach ($iterator as $idx => $record) {
            $this->processRecord($record, $idx + 2);
        }
        return 0;
    }

    private function csvRecordIterator(string $filePath)
    {
        $iterator = $this->csvIterator($filePath);
        $iterator = $this->trimIterator($iterator);

        $header = null;
        foreach ($iterator as $idx => $item) {
            if ($idx === 0) {
                $header = $item;
            } else {
                if (count($header) !== count($item)) {
                    throw new \Exception('Invalid header and row count');
                }
                $record = [];
                for ($i = 0; $i < count($header); $i++) {
                    $record[$header[$i]] = $item[$i];
                }
                yield $record;
            }
        }

    }

    private function trimIterator(iterable $iterator): iterable
    {
        foreach ($iterator as $item) {
            $item = array_map('trim', $item);
            yield $item;
        }
    }

    private function csvIterator(string $filePath): iterable
    {
        $handle = fopen($filePath, "r");
        if ($handle === false) {
            throw new \Exception(sprintf('Cant open file %s', $filePath));
        }

        while (($data = fgetcsv($handle, 0, ";",)) !== false) {
            yield $data;
        }
        fclose($handle);
    }

    private function processRecord(array $record, int $lineNumber): void
    {
        $physicalObjectId = $this->findOrCreatePhysicalObject($record['Объект'], $lineNumber);
        $relativeComponentId = $this->findOrCreateRelativeComponent($record['Относится к'], $physicalObjectId,
            $lineNumber);

        $entity = (new Component())
            ->fill([
                'is_highlevel' => 0,
                'identifier' => $record['Идентификатор (можно с ревизией)'],
                'title' => $record['Наименование'],
                'entry_level' => (int)$record['Уровень входимости'],
                'physical_object_id' => $physicalObjectId,
                'relative_component_id' => $relativeComponentId,
                'quantity_in_object' => (int)$record['Количество для ПС_19л_Сед_PHEV01'],

                'constructor_id' => $this->findUser($record['УГК - Ответственный УГК']),
                'manufactor_id' => $this->findUser($record['ЗОК - Ответственный Производство']),

                'type' => $this->convertEnum($record, 'Тип элемента', ComponentType::LABELS, $lineNumber),
                'version' => $this->convertEnum($record, 'Версия компонента', ComponentVersion::LABELS, $lineNumber),
                'source_type' => $this->convertEnum($record, 'Тип', ComponentSourceType::LABELS, $lineNumber),
                '3d_status' => $this->convertEnum($record, 'УГК - Статус 3D модели', Component3dStatus::LABELS,
                    $lineNumber),
                'dd_status' => $this->convertEnum($record, 'УГК - Статус КД', ComponentDdStatus::LABELS, $lineNumber),
                'calc_status' => $this->convertEnum($record, 'УГК - Статус расчеты', ComponentCalcStatus::LABELS,
                    $lineNumber),
                'manufactor_status' => $this->convertEnum($record, 'ЗОК - Статус Производство',
                    ComponentManufactorStatus::LABELS, $lineNumber),
                'purchase_status' => $this->convertEnum($record, 'Закупки - Статус Закупки',
                    ComponentPurchaserStatus::LABELS, $lineNumber),
                'manufactor_start_way' => $this->convertEnum($record, 'Способ запуска в производство',
                    ComponentManufactorStartWay::LABELS, $lineNumber),

                '3d_date_plan' => $this->convertDate($record['УГК - Планируемая дата подготовки 3D']),
                'dd_date_plan' => $this->convertDate($record['УГК - Планируемая дата подготовки КД']),
                'manufactor_date_plan' => $this->convertDate($record['УГК - Планируемая дата выполнения расчетов']),
                'purchase_date_plan' => $this->convertDate($record['ЗОК - Планируемая дата изготовления']),

                'constructor_comment' => $record['УГК - Примечание'],
                'manufactor_sz_files' => $record['ЗОК - СЗ'],
                'manufactor_sz_date' => $this->convertDate($record['ЗОК - Дата СЗ']),
                'manufactor_sz_quantity' => (int)$record['ЗОК - Дата СЗ'],
                'manufactor_priority' => (int)$record['ЗОК - Приоритет'],

                'purchase_request_files' => $record['Закупки - Заявка'],
                'purchase_request_quantity' => (int)$record['Закупки - Количество в заявке, шт.'],
            ]);
        $entity->save();
        echo sprintf("Created component %s\n", $entity->title);
    }

    private function findOrCreateRelativeComponent(string $title, int $physObjectId, int $lineNumber): int
    {
        if (empty($title)) {
            throw new \Exception(sprintf('Line %d - Empty relative component', $lineNumber));
        }

        $components = Component::query()
            ->where('title', $title)
            ->where('is_highlevel', 1)
            ->get()
            ->all();
        if (count($components) === 1) {
            return $components[0]->id;
        }
        if (count($components) === 0) {
            $entity = (new Component())
                ->fill([
                    'title' => $title,
                    'is_highlevel' => 1,
                    'physical_object_id' => $physObjectId
                ]);
            $entity->save();
            return $entity->id;
        }
        if (count($components) > 1) {
            throw new \Exception(sprintf('Multiple component found - %s', $title));
        }

        throw new \LogicException();

    }

    private function findOrCreatePhysicalObject(string $name, int $lineNumber): int
    {
        if (empty($name)) {
            throw new \Exception(sprintf('Line %d - empty physical object', $lineNumber));
        }
        $po = PhysicalObject::query()
            ->where('name', $name)
            ->where('id', 5)
            ->get()
            ->all();
        if (count($po) === 1) {
            return $po[0]->id;
        }
        if (count($po) === 0) {
            $entity = (new PhysicalObject())
                ->fill(['name' => $name, 'id' => 5]);
            $entity->save();
            return $entity->id;
        }
        if (count($po) > 1) {
            throw new \Exception(sprintf('Multiple phys objects found - %s', $name));
        }
        throw new \LogicException();
    }

    private function convertEnum(array $record, string $fieldName, array $values, int $lineNumber): ?int
    {
        $values = array_flip($values);
        $recordValue = $record[$fieldName];
        if (empty($recordValue)) {
            return null;
        }

        if (!isset($values[$recordValue])) {
            throw new \Exception(sprintf('Line %d, column %s - Invalid enum value - "%s"',
                $lineNumber, $fieldName, $recordValue));
        }
        return $values[$recordValue];
    }

    private function findUser(string $fio): ?int
    {
        $fioParts = explode(' ', $fio);
        $fioParts = array_map('trim', $fioParts);

        $users = User::query()
            ->where('surname', $fioParts[0])
            ->get()
            ->all();
        return match (count($users)) {
            0 => null,
            1 => $users[0]->id,
            default => throw  new \Exception(sprintf('Too many users for %s', $fio))
        };
    }

    private function convertDate(string $value)
    {
        if (empty($value)) {
            return null;
        }
        try {
            return new \DateTime($value);
        } catch (\Exception $e) {
            echo sprintf("Invalid date: %s", $value);
            throw $e;
        }
    }

}
