<?php

namespace MauticPlugin\TheCodeineEmbedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Widget
{
    const TYPE_IFRAME = 'iframe';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $data;

    /**
     * @var string
     */
    private $embed;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $ordering;

    /**
     * @var string
     */
    private $type;

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('plugin_thecodeine_embed_widgets')
            ->setCustomRepositoryClass('MauticPlugin\TheCodeineEmbedBundle\Entity\WidgetRepository')
            ->addIndex(['embed'], 'embed_widget_embed');
        $builder->addId();
        $builder->addNamedField('name', 'string', 'name');
        $builder->addNamedField('data', 'text', 'data', true);
        $builder->addNamedField('embed', 'string', 'embed');
        $builder->addNamedField('width', 'integer', 'width');
        $builder->addNamedField('height', 'integer', 'height');
        $builder->addNamedField('ordering', 'integer', 'ordering', true);
        $builder->addNamedField('type', 'string', 'type');
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new NotBlank([
            'message' => 'mautic.core.type.required',
        ]));
        $metadata->addPropertyConstraint('data', new NotBlank([
            'message' => 'mautic.core.type.required',
        ]));
        $metadata->addPropertyConstraint('type', new NotBlank([
            'message' => 'mautic.core.type.required',
        ]));
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Widget
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     *
     * @return Widget
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmbed()
    {
        return $this->embed;
    }

    /**
     * @param string $embed
     *
     * @return Widget
     */
    public function setEmbed($embed)
    {
        $this->embed = $embed;

        return $this;
    }

    /**
     * Set width.
     *
     * @param int $width
     *
     * @return Widget
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width.
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height.
     *
     * @param int $height
     *
     * @return Widget
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height.
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set ordering.
     *
     * @param int $ordering
     *
     * @return Widget
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering.
     *
     * @return int
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Widget
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
