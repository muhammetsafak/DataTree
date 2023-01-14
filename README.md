# DataTree

This simple library/class allows you to get a tree structure using a one-dimensional array.

__Note :__ It took approximately 1.10 seconds for this library to process an array with 1,000,000 elements, using over 400MB of memory. Keep in mind that the larger the data you are processing, the more memory and time you will need.

## Installation

```
composer require muhammetsafak/data-tree
```

## Usage

```php
require_once "vendor/autoload.php";
use MuhammetSafak\DataTree\TreeGenerator;

$data = [
    ['ID' => 1, 'parent_id' => 0, 'random_name' => 'Random #1'],
    ['ID' => 2, 'parent_id' => 0, 'random_name' => 'Random #2'],
    ['ID' => 3, 'parent_id' => 1, 'random_name' => 'Random #3'],
    ['ID' => 4, 'parent_id' => 2, 'random_name' => 'Random #4'],
    ['ID' => 5, 'parent_id' => 0, 'random_name' => 'Random #5'],
    ['ID' => 6, 'parent_id' => 2, 'random_name' => 'Random #6'],
];

$tree = (new TreeGenerator($data))
    ->setRelation('id', 'parentId')
    ->setReName('ID', 'id')
    ->setReName('parent_id', 'parentId')
    ->setReName('random_name', 'name')
    ->setChildNodeName('@childrens')
    ->toTree();

print_r($tree);
```

Output : 

```
Array
(
    [0] => Array
        (
            [id] => 1
            [parentId] => 0
            [name] => Random #1
            [@childrens] => Array
                (
                    [0] => Array
                        (
                            [id] => 3
                            [parentId] => 1
                            [name] => Random #3
                        )

                )

        )

    [1] => Array
        (
            [id] => 2
            [parentId] => 0
            [name] => Random #2
            [@childrens] => Array
                (
                    [0] => Array
                        (
                            [id] => 4
                            [parentId] => 2
                            [name] => Random #4
                        )

                    [1] => Array
                        (
                            [id] => 6
                            [parentId] => 2
                            [name] => Random #6
                        )

                )

        )

    [2] => Array
        (
            [id] => 5
            [parentId] => 0
            [name] => Random #5
        )

)
```

## Credits

- [Muhammet ŞAFAK](https://www.muhammetsafak.com.tr) <<info@muhammetsafak.com.tr>>

## License

Copyright &copy; 2022 [MIT License](./LICENSE)