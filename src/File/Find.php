<?php
declare(strict_types=1);

namespace MkConn\Sfc\File;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Mime\MimeTypes;

class Find {
    /**
     * @var array<string, string[]>
     */
    protected static array $typeMap = [
        'pdf'        => ['application/pdf'],
        'postscript' => ['application/postscript'],
        'richtext'   => ['application/rtf'],
        'application/stuffit',
        'office'     => [
            'application/msword',
            'application/vnd.ms-excel',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ],
        'application/x-apple-diskimage',
        'font'       => ['application/x-font-truetype'],
        'archive'    => [
            'application/x-bz2',
            'application/x-gzip',
            'application/x-java-archive',
        ],
        'application/x-ms-dos-executable',
        'application/x-msaccess',
        'php'        => ['application/x-php'],
        'application/x-rar-compressed',
        'application/x-stuffit',
        'application/x-tar',
        'application/xml',
        'application/zip',
        'audio'      => [
            'audio/midi',
            'audio/midi',
            'audio/mp4',
            'audio/mpeg',
            'audio/ogg',
            'audio/wav',
            'audio/x-aiff',
            'audio/x-mpegurl',
            'audio/x-ms-wma',
            'audio/x-ms-wmv'
        ],
        'image'      => [
            'image/bmp',
            'image/gif',
            'image/jpeg',
            'image/jpeg',
            'image/heic',
            'image/png',
            'image/svg+xml',
            'image/tiff'
        ],
        'message'    => ['message/rfc822'],
        'text'       => [
            'text/css',
            'text/csv',
            'text/html',
            'text/javascript',
            'text/plain',
            'text/x-vcard',
        ],
        'video'      => [
            'video/avi',
            'video/mp4',
            'video/mpeg',
            'video/mpeg',
            'video/quicktime',
            'video/x-flv',
        ]
    ];
    protected Finder $finder;

    public function __construct(protected string $root, protected array $types = [], protected array $exts = [], protected array $exclude = []) {
        $this->finder = new Finder();

        $this->prepareExtensions();
    }

    /**
     * @return string[]|null
     */
    protected static function findMimeTypes(string $type): ?array {
        return self::$typeMap[$type] ?? null;
    }

    public function files(): Finder {
        return $this->finder->name($this->exts)
                            ->files()
                            ->in($this->root);
    }

    protected function prepareExtensions(): void {
        if ($this->exts === [] && $this->types === []) {
            return;
        }

        if ($this->types !== []) {
            $mimeTypes = new MimeTypes();
            $exts = [];
            $types = [];

            foreach ($this->types as $type) {
                if (!str_contains((string) $type, '/')) {
                    $typesFromMap = self::findMimeTypes($type);

                    if ($typesFromMap) {
                        $types = $typesFromMap;
                    }
                } else {
                    $types = [$type];
                }

                foreach ($types as $fileType) {
                    $exts = array_merge($exts, $mimeTypes->getExtensions($fileType));
                }
            }

            $this->exts = array_merge($this->exts, $exts);
            $this->exts = array_diff($this->exts, $this->exclude);
        }

        array_walk($this->exts, function (&$ext) {
            $ext = "/\.$ext/i";
        });
    }
}
