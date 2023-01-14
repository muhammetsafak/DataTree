<?php
/**
 * TreeGenerator.php
 *
 * This file is part of DataTree.
 *
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2022 Muhammet ŞAFAK
 * @license    ./LICENSE  MIT
 * @version    0.1
 * @link       https://www.muhammetsafak.com.tr
 */

namespace MuhammetSafak\DataTree;

use MuhammetSafak\DataTree\Exceptions\TreeGeneratorException;

class TreeGenerator
{

    private array $data;

    private array $relation = [];

    private array $reNames = [];

    private string $childNodeName = '@childrens';


    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function __destruct()
    {
        unset($this->data, $this->relations, $this->reNames, $this->childNodeName);
    }

    public function setRelation(string $rootKey, string $childKey): self
    {
        $this->relation = [$rootKey => $childKey];

        return $this;
    }

    public function setReNames(array $reNames): self
    {
        $this->reNames = $reNames;

        return $this;
    }

    public function setReName(string $key, string $rename): self
    {
        $this->reNames[$key] = $rename;

        return $this;
    }

    public function setChildNodeName(string $childNodeName = '@childrens'): self
    {
        $this->childNodeName = $childNodeName;

        return $this;
    }

    public function toTree(): array
    {
        return $this->createTree();
    }

    private function createTree()
    {
        $relationRootKey = key($this->relation);
        $relationChildKey = current($this->relation);

        if (empty($relationRootKey) || empty($relationChildKey)) {
            throw new TreeGeneratorException('Try using the "setRelation()" method so that the relationships can be established.');
        }



        try {

            function createBranch(&$parents, $children, $rootKey, $childKey, $childNodeName): array
            {
                $tree = [];
                foreach ($children as $key => $child) {
                    if (isset($parents[$child[$rootKey]])) {
                        $child[$childNodeName] = createBranch($parents, $parents[$child[$rootKey]], $rootKey, $childKey, $childNodeName);
                    }
                    $tree[] = $child;
                }
                return $tree;
            }

            function keyReName(array &$node, array $reName)
            {
                $new = [];
                foreach ($node as $key => $value) {
                    $new[($reName[$key] ?? $key)] = $value;
                }
                $node = $new;
            }

            $parents = [];

            $isRenamed = !empty($this->reNames);

            foreach ($this->data as &$datum) {
                $isRenamed && keyReName($datum, $this->reNames);
                @$parents[$datum[$relationChildKey]][] = $datum;
            }

            return createBranch($parents, reset($parents), $relationRootKey, $relationChildKey, $this->childNodeName);
        } catch (\Throwable $e) {
            throw new TreeGeneratorException($e->getMessage(), (int)$e->getCode(), $e->getPrevious());
        }

    }

}
