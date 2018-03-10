<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductDiagram Entity
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property string $file_name
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\ParentProductDiagram $parent_product_diagram
 * @property \App\Model\Entity\ChildProductDiagram[] $child_product_diagrams
 */
class ProductDiagram extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
