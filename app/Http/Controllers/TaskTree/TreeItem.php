<?php

namespace App\Http\Controllers\TaskTree;

class TreeItem
{
    private ?TreeItem $parent = null;
    private array $childs;
    private $data;

    public function __construct($data, ?TreeItem $parent = null, array $childs = [])
    {
        $this->data = $data;
        $this->parent = $parent;
        $this->childs = $childs;
    }

    public function level(): int {
        if($this->parent === null) {
            return 0;
        }
        return $this->parent->level() + 1;
    }

    public function addChild(TreeItem $child):void{
        $child->setParent($this);
        $this->childs[] = $child;
    }

    public function getParent(): ?TreeItem
    {
        return $this->parent;
    }

    public function removeChild(int $idx) {
        unset($this->childs[$idx]);
    }

    /**
     * @return TreeItem[]
     */
    public function getChilds(): array
    {
        return $this->childs;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setParent(?TreeItem $parent): void
    {
        $this->parent = $parent;
    }




}
