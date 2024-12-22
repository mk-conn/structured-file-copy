<?php

declare(strict_types=1);

namespace MkConn\Sfc\Services\FileService;

class FileTypes {
    /**
     * @var array<string, string[]>
     */
    public static array $typeMap = [
        'pdf'        => ['application/pdf'],
        'postscript' => ['application/postscript'],
        'richtext'   => ['application/rtf'],
        'application/stuffit',
        'office' => [
            'application/msword',
            'application/vnd.ms-excel',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ],
        'application/x-apple-diskimage',
        'font'    => ['application/x-font-truetype'],
        'archive' => [
            'application/x-bz2',
            'application/x-gzip',
            'application/x-java-archive',
        ],
        'application/x-ms-dos-executable',
        'application/x-msaccess',
        'php' => ['application/x-php'],
        'application/x-rar-compressed',
        'application/x-stuffit',
        'application/x-tar',
        'application/xml',
        'application/zip',
        'audio' => [
            'audio/midi',
            'audio/midi',
            'audio/mp4',
            'audio/mpeg',
            'audio/ogg',
            'audio/wav',
            'audio/x-aiff',
            'audio/x-mpegurl',
            'audio/x-ms-wma',
            'audio/x-ms-wmv',
        ],
        'image' => [
            'image/bmp',
            'image/gif',
            'image/jpeg',
            'image/jpeg',
            'image/heic',
            'image/png',
            'image/svg+xml',
            'image/tiff',
        ],
        'message' => ['message/rfc822'],
        'text'    => [
            'text/css',
            'text/csv',
            'text/html',
            'text/javascript',
            'text/plain',
            'text/x-vcard',
        ],
        'video' => [
            'video/avi',
            'video/mp4',
            'video/mpeg',
            'video/mpeg',
            'video/quicktime',
            'video/x-flv',
        ],
    ];
}
