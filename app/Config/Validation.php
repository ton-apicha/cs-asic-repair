<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    /**
     * Stores the classes that contain the rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // -------------------------------------------------------------------------
    // Custom Validation Rules
    // -------------------------------------------------------------------------

    /**
     * Customer validation rules
     */
    public array $customer = [
        'name' => [
            'rules'  => 'required|min_length[2]|max_length[255]',
            'errors' => [
                'required'   => 'Customer name is required.',
                'min_length' => 'Customer name must be at least 2 characters.',
                'max_length' => 'Customer name cannot exceed 255 characters.',
            ],
        ],
        'phone' => [
            'rules'  => 'required|min_length[9]|max_length[20]',
            'errors' => [
                'required'   => 'Phone number is required.',
                'min_length' => 'Phone number must be at least 9 characters.',
                'max_length' => 'Phone number cannot exceed 20 characters.',
            ],
        ],
    ];

    /**
     * Asset validation rules
     */
    public array $asset = [
        'brand_model' => [
            'rules'  => 'required|max_length[255]',
            'errors' => [
                'required'   => 'Brand/Model is required.',
                'max_length' => 'Brand/Model cannot exceed 255 characters.',
            ],
        ],
        'serial_number' => [
            'rules'  => 'required|max_length[100]',
            'errors' => [
                'required'   => 'Serial Number is required.',
                'max_length' => 'Serial Number cannot exceed 100 characters.',
            ],
        ],
    ];

    /**
     * Job validation rules
     */
    public array $job = [
        'customer_id' => [
            'rules'  => 'required|is_natural_no_zero',
            'errors' => [
                'required'           => 'Please select a customer.',
                'is_natural_no_zero' => 'Invalid customer selected.',
            ],
        ],
        'symptom' => [
            'rules'  => 'required|min_length[3]',
            'errors' => [
                'required'   => 'Please describe the symptoms.',
                'min_length' => 'Symptom description must be at least 3 characters.',
            ],
        ],
    ];

    /**
     * Inventory Part validation rules
     */
    public array $part = [
        'part_code' => [
            'rules'  => 'required|max_length[50]',
            'errors' => [
                'required'   => 'Part code is required.',
                'max_length' => 'Part code cannot exceed 50 characters.',
            ],
        ],
        'name' => [
            'rules'  => 'required|max_length[255]',
            'errors' => [
                'required'   => 'Part name is required.',
                'max_length' => 'Part name cannot exceed 255 characters.',
            ],
        ],
        'cost_price' => [
            'rules'  => 'required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required'              => 'Cost price is required.',
                'numeric'               => 'Cost price must be a number.',
                'greater_than_equal_to' => 'Cost price cannot be negative.',
            ],
        ],
        'sell_price' => [
            'rules'  => 'required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required'              => 'Sell price is required.',
                'numeric'               => 'Sell price must be a number.',
                'greater_than_equal_to' => 'Sell price cannot be negative.',
            ],
        ],
    ];

    /**
     * User validation rules
     */
    public array $user = [
        'username' => [
            'rules'  => 'required|min_length[3]|max_length[50]|alpha_numeric_punct',
            'errors' => [
                'required'             => 'Username is required.',
                'min_length'           => 'Username must be at least 3 characters.',
                'max_length'           => 'Username cannot exceed 50 characters.',
                'alpha_numeric_punct'  => 'Username can only contain alphanumeric characters and punctuation.',
            ],
        ],
        'password' => [
            'rules'  => 'required|min_length[6]',
            'errors' => [
                'required'   => 'Password is required.',
                'min_length' => 'Password must be at least 6 characters.',
            ],
        ],
        'email' => [
            'rules'  => 'permit_empty|valid_email',
            'errors' => [
                'valid_email' => 'Please enter a valid email address.',
            ],
        ],
    ];
}

