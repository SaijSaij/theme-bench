<?php

declare(strict_types=1);

namespace WebCodingCat\ThemeBench\Post;

use WebCodingCat\WpAppFramework\Query\Field\EnumField;
use WebCodingCat\WpAppFramework\Query\Field\IntField;
use WebCodingCat\WpAppFramework\Query\Field\StringField;
use WebCodingCat\WpAppFramework\Query\MetaFields;

#[MetaFields(EventDetails::class)]
final class EventDetailsFields
{
    public static function start(): StringField
    {
        return new StringField(EventDetails::class, 'start');
    }

    public static function capacity(): IntField
    {
        return new IntField(EventDetails::class, 'capacity');
    }

    /**
     * @return EnumField<EventKind>
     */
    public static function kind(): EnumField
    {
        return new EnumField(EventDetails::class, 'kind', EventKind::class);
    }
}
