<?php

namespace Config;

use CodeIgniter\Config\Publisher as BasePublisher;

class Publisher extends BasePublisher
{
    /**
     * Restrictions
     *
     * @var array<string, string>
     */
    public $restrictions = [
        ROOTPATH => '*',
        FCPATH   => '#\.(s?css|js|map|html?|xml|json|webmanifest|ttf|eot|woff2?|gif|jpe?g|tiff?|png|webp|bmp|ico|svg)$#i',
    ];
}

