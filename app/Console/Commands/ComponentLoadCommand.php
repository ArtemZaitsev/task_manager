<?php

namespace App\Console\Commands;

use App\Models\Component\Component;
use App\Models\Component\ComponentSourceType;
use App\Models\Component\ComponentVersion;
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
            $this->processRecord($record);
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

    private function processRecord(array $record): void
    {
        $relativeComponentId = $this->findOrCreateRelativeComponent($record['Относится к']);
      //  $sourceType = $this->convertEnum($record['Тип элемента'], ComponentSourceType::LABELS);
      //  $version = $this->convertEnum($record['Версия компонента'], ComponentVersion::LABELS);

        $entity = (new Component())
            ->fill([
                'is_highlevel' => 0,
                'identifier' => $record['Идентификатор (можно с ревизией)'],
                'title' => $record['Наименование'],
                'entry_level' => $record['Уровень входимости'],
                'relative_component_id' => $relativeComponentId,
                'constructor_id' => $this->findUser($record['УГК - Ответственный УГК'])
            ]);
        $entity->save();
        echo sprintf("Created component %s\n", $entity->title);
    }

    private function findOrCreateRelativeComponent(string $title): int
    {
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
                ->fill(['title' => $title, 'is_highlevel' => 1]);
            $entity->save();
            return $entity->id;
        }
        if (count($components) > 1) {
            throw new \Exception(sprintf('Multiple component found - %s', $title));
        }

    }

    private function convertEnum(string $label, array $values): int
    {
        $values = array_flip($values);
        if (!isset($values[$label])) {
            throw new \Exception(sprintf('Invalid enum value - %s', $label));
        }
        return $values[$label];
    }

    private function findUser(string $fio): int
    {
        $fioParts = explode(' ', $fio);
        $fioParts = array_map('trim', $fioParts);

        $users = User::query()
            ->where('surname', $fioParts[0])
            ->get()
            ->all();
        return match (count($users)) {
            0 => throw new \Exception(sprintf('No user %s', $fio)),
            1 => $users[0]->id,
            default => throw  new \Exception(sprintf('Too many users for %s', $fio))
        };
    }

}
