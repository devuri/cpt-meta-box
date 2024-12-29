<?php

/*
 * This file is part of the cptMeta package.
 *
 * (c) Uriel Wilson
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urisoft\PostMeta\Form;

use Urisoft\PostMeta\Form\Element\Category;
use Urisoft\PostMeta\Form\Element\Data;
use Urisoft\PostMeta\Form\Element\Image;
use Urisoft\PostMeta\Form\Element\Input;
use Urisoft\PostMeta\Form\Element\Nonce;
use Urisoft\PostMeta\Form\Element\Select;
use Urisoft\PostMeta\Form\Element\Table;
use Urisoft\PostMeta\Form\Element\TextArea;

class Form
{
    use Category;
    use Data;
    use Image;
    use Input;
    use Nonce;
    use Select;
    use Table;
    use TextArea;

    public static $version = '1.4.2';
    protected bool $processing = false;
    protected array $context;
    protected array $fields;
    protected string $wpnonce;

    public function __construct(?array $context = [])
    {
        $this->context = $context;
    }

    public function getFields(): ?array
    {
        return $this->fields;
    }

    protected static function hashId(array $inputParams): string
    {
        return hash('fnv1a64', serialize($inputParams));
    }

    protected function addField(array $inputParams): void
    {
        $inputID = self::hashId($inputParams);

        if (\array_key_exists($inputID, $this->fields)) {
            return;
        }

        $this->fields[$inputID] = $inputParams;
    }
}
