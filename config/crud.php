<?php

return [
    'filters' => [
        'eq' => [
            'integer',
            'tinyInteger',
            'smallInteger',
            'mediumInteger',
            'bigInteger',
            'unsignedInteger',
            'unsignedTinyInteger',
            'unsignedSmallInteger',
            'unsignedMediumInteger',
            'unsignedBigInteger',
            'float',
            'decimal',
            'boolean',
            'smallint',
            'mediumint',
            'int',
            'bigint',
            'double'
        ],
        'like' => [
            'string',
            'text',
            'char',
            'varchar'
        ],
        'date' => [
            'date',
            'datetime',
            'timestamp'
        ]
    ],
    'upload_rules' => [
        'file' => 'required|file'
    ]
];
