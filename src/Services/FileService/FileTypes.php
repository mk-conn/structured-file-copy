<?php

declare(strict_types=1);

namespace MkConn\Sfc\Services\FileService;

use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Mime\MimeTypes;

class FileTypes {
    public function __construct(private readonly MimeTypes $mimeTypes) {}

    /**
     * @return array<string>
     */
    public static function getFileTypes(): array {
        return array_keys(self::$typeMap);
    }

    public function getTypeForFile(SplFileInfo $fileInfo): string {
        $mimeType = $this->mimeTypes->getMimeTypes($fileInfo->getExtension());
        $mimeType = $mimeType[0] ?? '';

        $type = array_filter(self::$typeMap, fn ($type) => in_array($mimeType, $type));

        if ($type) {
            return array_key_first($type);
        }

        return 'miscellaneous';
    }

    /**
     * @var array<string, array<string>>
     */
    public static array $typeMap = [
        // Archive formats
        'archive' => [
            'application/x-bz2',
            'application/x-gzip',
            'application/x-java-archive',
            'application/x-rar-compressed',
            'application/x-stuffit',
            'application/x-tar',
            'application/xml',
            'application/zip',
            'application/x-7z-compressed',
            'application/x-ace-compressed',
            'application/x-apple-diskimage',
            'application/x-arj',
            'application/x-lzh',
            'application/x-lzx',
            'application/x-iso9660-image',
            'application/x-cpio',
            'application/x-shar',
            'application/vnd.rar',
            'application/x-zip-compressed',
        ],
        // Audio formats
        'audio' => [
            'audio/midi',
            'audio/mp4',
            'audio/mpeg',
            'audio/ogg',
            'audio/wav',
            'audio/x-aiff',
            'audio/x-mpegurl',
            'audio/x-ms-wma',
            'audio/x-ms-wmv',
            'audio/x-flac',
            'audio/x-m4a',
            'audio/aac',
            'audio/webm',
            'audio/3gpp',
            'audio/3gpp2',
            'audio/x-wav',
            'audio/x-opus+ogg',
            'audio/x-silk',
        ],
        // Video formats
        'video' => [
            'video/avi',
            'video/mp4',
            'video/mpeg',
            'video/quicktime',
            'video/x-flv',
            'video/x-matroska',
            'video/x-msvideo',
            'video/x-ms-wmv',
            'video/webm',
            'video/3gpp',
            'video/3gpp2',
            'video/h264',
            'video/h265',
            'video/ogg',
            'video/x-ms-asf',
            'video/x-ms-dvr',
        ],
        // Image formats
        'image' => [
            'image/bmp',
            'image/gif',
            'image/jpeg',
            'image/heic',
            'image/png',
            'image/svg+xml',
            'image/tiff',
            'image/x-icon',
            'image/webp',
            'image/vnd.adobe.photoshop',
            'image/x-xcf',
            'image/x-tga',
            'image/x-bitmap',
            'image/x-pcx',
            'image/x-xpixmap',
            'image/x-hdr',
        ],
        // Font formats
        'font' => [
            'application/x-font-truetype',
            'application/font-woff',
            'application/font-woff2',
            'application/vnd.ms-fontobject',
            'application/x-font-otf',
            'application/x-font-ttf',
            'font/ttf',
            'font/otf',
            'font/woff',
            'font/woff2',
            'application/x-font-bdf',
        ],
        // Office documents
        'office' => [
            'application/msword',
            'application/vnd.ms-excel',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.oasis.opendocument.text',
            'application/vnd.oasis.opendocument.spreadsheet',
            'application/vnd.oasis.opendocument.presentation',
            'application/vnd.google-apps.document',
            'application/vnd.google-apps.spreadsheet',
            'application/vnd.google-apps.presentation',
        ],
        // PDF
        'pdf' => ['application/pdf'],
        // Postscript
        'postscript' => ['application/postscript'],
        // Richtext
        'richtext' => ['application/rtf'],
        // PHP files
        'php' => ['application/x-php'],
        // Email messages
        'message' => ['message/rfc822'],
        // Text formats
        'text' => [
            'text/css',
            'text/csv',
            'text/html',
            'text/javascript',
            'text/plain',
            'text/x-vcard',
            'text/markdown',
            'text/xml',
            'text/yaml',
            'text/tab-separated-values',
            'text/x-log',
            'text/x-shellscript',
            'text/x-nfo',
        ],
        // Application-specific formats
        'application' => [
            'application/javascript',
            'application/json',
            'application/ld+json',
            'application/x-sh',
            'application/x-httpd-php',
            'application/x-msdownload',
            'application/octet-stream',
            'application/x-pkcs12',
            'application/x-pkcs7-certificates',
            'application/x-pkcs7-certreqresp',
            'application/x-shockwave-flash',
            'application/vnd.android.package-archive',
            'application/x-deb',
            'application/x-rpm',
            'application/x-tar',
        ],
        // XML-based formats
        'xml' => [
            'application/rss+xml',
            'application/atom+xml',
            'application/xslt+xml',
            'application/xml',
        ],
        // Programming source code
        'source' => [
            'text/x-c',
            'text/x-c++',
            'text/x-java-source',
            'application/x-python',
            'application/x-ruby',
            'application/x-perl',
            'application/x-php',
            'text/x-swift',
            'application/x-swift',
            'application/x-csharp',
            'text/x-csharp',
            'text/x-go',
            'text/x-kotlin',
            'text/x-lua',
            'text/x-objective-c',
            'text/x-objective-c++',
            'text/x-pascal',
            'text/x-perl',
            'text/x-php',
            'text/x-rust',
            'application/x-ruby',
            'text/x-ruby',
            'text/x-scala',
        ],
        // Configuration files
        'config' => [
            'application/x-yaml',
            'application/json',
            'text/x-ini',
            'application/x-properties',
            'text/x-env',
        ],
    ];
}
