<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Technical Entity
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property string $description
 * @property string $file_name
 * @property string $is_new
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\ParentTechnical $parent_technical
 * @property \App\Model\Entity\ChildTechnical[] $child_technicals
 */
class Technical extends Entity
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
