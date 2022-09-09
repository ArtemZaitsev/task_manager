<?php

namespace App\Lib\Grid\Field;

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
        session()->put($this->saveIdentifier, $fields);
    }

    public function getField(string $fieldName): ?Field {
        return $this->fields[$fieldName] ?? null;
    }

    public function load()
    {
        $fields = session()->get($this->saveIdentifier, []);
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
