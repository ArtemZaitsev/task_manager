<?php

namespace App\Lib\Grid\Field;

use App\Models\GridSettings;
use Illuminate\Support\Facades\Auth;

class FieldSet
{
    /** @var Field[] */
    private array $fields;

    public function __construct(
        array          $fields,
        private string $saveIdentifier)
    {
        /** @var Field $field */
        foreach ($fields as $field) {
            if (isset($this->fields[$field->getName()])) {
                throw new \LogicException();
            }
            $this->fields[$field->getName()] = $field;
        }
    }

    public function save(array $fields)
    {
        $existingSettingsCollection = GridSettings::query()
            ->where('user_id', Auth::id())
            ->where('grid', $this->saveIdentifier)
            ->get();
        if($existingSettingsCollection->count() > 0) {
            $existingSettings = $existingSettingsCollection->get(0);
        } else {
            $existingSettings = (new GridSettings());
            $existingSettings->fill([
                'user_id' => Auth::id(),
                'grid' => $this->saveIdentifier
            ]);
        }

        $existingSettings->fill(['settings_data' => $fields]);
        $existingSettings->save();

     //   session()->put($this->saveIdentifier, $fields);
    }

    public function getField(string $fieldName): ?Field {
        return $this->fields[$fieldName] ?? null;
    }

    public function load()
    {
        //$fields = session()->get($this->saveIdentifier, []);
        $settingsCollection =  GridSettings::query()
            ->where('user_id', Auth::id())
            ->where('grid', $this->saveIdentifier)
            ->get();
        if($settingsCollection->count() > 0) {
            $fields = $settingsCollection->get(0)->settings_data;
        } else {
            $fields = [];
        }

        $visitedFields = [];

        foreach ($fields as $fieldName) {
            if (isset($this->fields[$fieldName])) {
                $this->fields[$fieldName]->setNeedDisplay(true);
                $visitedFields[$fieldName] = true;
            }
        }

        if(count($fields) > 0) {
            foreach ($this->fields as $fieldName => $field) {
                if (!isset($visitedFields[$fieldName])) {
                    $field->setNeedDisplay(false);
                }
            }
        }
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function needDisplay(string $fieldName): bool
    {
        if (!isset($this->fields[$fieldName])) {
            return false;
        }
        return $this->fields[$fieldName]->isNeedDisplay();
    }


}
